package esgi.pa.ui.activities

import android.os.Bundle
import android.util.Log
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.Toast
import androidx.core.view.isVisible
import androidx.fragment.app.Fragment
import androidx.fragment.app.viewModels
import androidx.lifecycle.lifecycleScope
import androidx.recyclerview.widget.LinearLayoutManager
import com.google.gson.Gson
import com.google.gson.JsonObject
import esgi.pa.data.model.Activity
import esgi.pa.databinding.FragmentActivitiesListBinding
import esgi.pa.util.Resource
import esgi.pa.util.SessionManager
import kotlinx.coroutines.launch
import okhttp3.ResponseBody
import java.io.IOException

class ActivitiesListFragment : Fragment() {
    private val TAG = "ActivitiesListFragment"
    private var _binding: FragmentActivitiesListBinding? = null
    private val binding get() = _binding!!

    private val viewModel: ActivityListViewModel by viewModels { ActivityViewModelFactory() }
    private var userId: Int = -1
    private lateinit var sessionManager: SessionManager

    private val adapter = ActivityAdapter(
        onRegisterClick = { activity, isRegistered ->
            Log.d(TAG, "Button clicked for activity: ${activity.nom}, ID: ${activity.activity_id}, isRegistered: $isRegistered")
            handleActivityRegistration(activity, isRegistered)
        }
    )

    override fun onCreateView(
        inflater: LayoutInflater,
        container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View {
        _binding = FragmentActivitiesListBinding.inflate(inflater, container, false)
        sessionManager = SessionManager(requireContext())
        userId = sessionManager.getUserId()
        return binding.root
    }

    override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        super.onViewCreated(view, savedInstanceState)
        setupRecyclerView()
        viewModel.loadAllActivities()
        observeViewModel()
        loadRegisteredActivities()
    }

    private fun setupRecyclerView() {
        binding.recyclerViewActivities.adapter = adapter
        binding.recyclerViewActivities.layoutManager = LinearLayoutManager(requireContext())
    }

    private fun observeViewModel() {
        viewLifecycleOwner.lifecycleScope.launch {
            viewModel.activities.collect { resource ->
                when (resource) {
                    is Resource.Loading -> {
                        binding.progressBar.isVisible = true
                    }
                    is Resource.Success -> {
                        binding.progressBar.isVisible = false
                        resource.data?.forEach {
                            Log.d(TAG, "Activity loaded: ${it.nom}, ID: ${it.activity_id}")
                        }
                        adapter.updateActivities(resource.data ?: emptyList())
                    }
                    is Resource.Error -> {
                        binding.progressBar.isVisible = false
                        Toast.makeText(context, resource.message, Toast.LENGTH_LONG).show()
                    }
                }
            }
        }
    }

    private fun loadRegisteredActivities() {
        viewLifecycleOwner.lifecycleScope.launch {
            binding.progressBar.isVisible = true

            // Get registered activities
            val repository = viewModel.getRepository()
            when (val userActivitiesResult = repository.getEmployeeActivities(userId)) {
                is Resource.Success -> {
                    val registeredActivities = userActivitiesResult.data ?: emptyList()
                    Log.d(TAG, "Registered activities count: ${registeredActivities.size}")

                    // Debug registered activities
                    registeredActivities.forEach {
                        Log.d(TAG, "Registered activity: ${it.nom}, ID: ${it.activity_id}")
                    }

                    // Create mapping between activity names that might differ between APIs
                    val nameMapping = mapOf(
                        "sortie d'équipe" to "team building nature",
                        "test" to "atelier test"
                    )

                    // Extract all activity names for improved matching
                    val registeredNames = registeredActivities.map { activity ->
                        val normalizedName = activity.nom.trim().lowercase()
                        // Use mapping if available, otherwise use the original name
                        nameMapping[normalizedName] ?: normalizedName
                    }.toSet()

                    Log.d(TAG, "Registered activity names with mapping: $registeredNames")
                    adapter.updateRegisteredActivities(registeredActivities, registeredNames)
                }
                is Resource.Error -> {
                    Toast.makeText(context, userActivitiesResult.message, Toast.LENGTH_SHORT).show()
                }
                is Resource.Loading -> {
                    // Do nothing, already showing progress bar
                }
            }

            binding.progressBar.isVisible = false
        }
    }

    private fun handleActivityRegistration(activity: Activity, isRegistered: Boolean) {
        Log.d(TAG, "Button clicked for activity: ${activity.nom}, ID: ${activity.activity_id}, isRegistered: $isRegistered")

        val collaborateurId = sessionManager.getCollaborateurId()

        if (collaborateurId <= 0) {
            Toast.makeText(requireContext(), "ID utilisateur invalide", Toast.LENGTH_SHORT).show()
            return
        }

        lifecycleScope.launch {
            try {
                val result = if (!isRegistered) {
                    Log.d(TAG, "Registering to activity: ${activity.nom}, ID: ${activity.activity_id}")
                    viewModel.getRepository().registerToActivity(collaborateurId, activity.activity_id)
                } else {
                    Log.d(TAG, "Unregistering from activity: ${activity.nom}, ID: ${activity.activity_id}")
                    viewModel.getRepository().unregisterFromActivity(collaborateurId, activity.activity_id)
                }

                when (result) {
                    is Resource.Success -> {
                        val message = if (!isRegistered) "Inscription réussie" else "Désinscription réussie"
                        Toast.makeText(requireContext(), message, Toast.LENGTH_SHORT).show()
                        loadRegisteredActivities() // Refresh the registered activities list
                    }
                    is Resource.Error -> {
                        val errorMessage = result.message ?: ""
                        val userMessage = extractErrorMessage(errorMessage, isRegistered)

                        Log.e(TAG, "Registration error: $errorMessage")
                        Toast.makeText(requireContext(), userMessage, Toast.LENGTH_LONG).show()

                        // Always reload to ensure UI is in sync with server state
                        loadRegisteredActivities()
                    }
                    else -> {
                        Toast.makeText(requireContext(), "Opération en cours...", Toast.LENGTH_SHORT).show()
                    }
                }
            } catch (e: Exception) {
                Log.e(TAG, "Exception during registration operation", e)
                Toast.makeText(requireContext(), "Erreur: ${e.message}", Toast.LENGTH_LONG).show()
                loadRegisteredActivities()
            }
        }
    }

    private fun extractErrorMessage(errorMessage: String, isRegistered: Boolean): String {
        // Check if error contains "Already registered" message from server
        return when {
            errorMessage.contains("Already registered", ignoreCase = true) ->
                "Vous êtes déjà inscrit à cette activité"
            errorMessage.contains("Not registered", ignoreCase = true) ->
                "Vous n'êtes pas inscrit à cette activité"
            isRegistered ->
                "Erreur lors de la désinscription"
            else ->
                "Erreur lors de l'inscription"
        }
    }

    override fun onDestroyView() {
        super.onDestroyView()
        _binding = null
    }
}