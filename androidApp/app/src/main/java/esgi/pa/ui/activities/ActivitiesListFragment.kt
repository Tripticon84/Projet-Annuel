package esgi.pa.ui.activities

import android.os.Bundle
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.Toast
import androidx.core.view.isVisible
import androidx.fragment.app.Fragment
import androidx.fragment.app.viewModels
import androidx.lifecycle.lifecycleScope
import androidx.recyclerview.widget.LinearLayoutManager
import esgi.pa.data.model.Activity
import esgi.pa.data.repository.AuthRepository
import esgi.pa.databinding.FragmentActivitiesListBinding
import esgi.pa.ui.adapters.ActivityAdapter
import esgi.pa.util.Resource
import esgi.pa.util.SessionManager
import kotlinx.coroutines.launch

class ActivitiesListFragment : Fragment() {
    private var _binding: FragmentActivitiesListBinding? = null
    private val binding get() = _binding!!

    private val viewModel: ActivityListViewModel by viewModels { ActivityViewModelFactory() }
    private val authRepository = AuthRepository()
    private var userId: Int = -1
    private lateinit var sessionManager: SessionManager

    private val adapter = ActivityAdapter(
        onRegisterClick = { activity, isRegistered ->
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
        loadData()
    }

    private fun setupRecyclerView() {
        binding.recyclerViewActivities.adapter = adapter
        binding.recyclerViewActivities.layoutManager = LinearLayoutManager(requireContext())
    }

    private fun loadData() {
        viewLifecycleOwner.lifecycleScope.launch {
            binding.progressBar.isVisible = true

            // Load all activities
            when (val activitiesResult = authRepository.getAllActivities()) {
                is Resource.Success -> {
                    adapter.updateActivities(activitiesResult.data ?: emptyList())
                }
                is Resource.Error -> {
                    Toast.makeText(context, activitiesResult.message, Toast.LENGTH_SHORT).show()
                }
                is Resource.Loading -> {}
            }

            // Get registered activities
            when (val userActivitiesResult = authRepository.getEmployeeActivities(userId)) {
                is Resource.Success -> {
                    adapter.updateRegisteredActivities(userActivitiesResult.data ?: emptyList())
                }
                is Resource.Error -> {
                    Toast.makeText(context, userActivitiesResult.message, Toast.LENGTH_SHORT).show()
                }
                is Resource.Loading -> {}
            }

            binding.progressBar.isVisible = false
        }
    }

    private fun handleActivityRegistration(activity: Activity, isRegistered: Boolean) {
        viewLifecycleOwner.lifecycleScope.launch {
            binding.progressBar.isVisible = true

            val result = if (isRegistered) {
                authRepository.unregisterFromActivity(userId, activity.activity_id)
            } else {
                authRepository.registerToActivity(userId, activity.activity_id)
            }

            when (result) {
                is Resource.Success -> {
                    Toast.makeText(
                        context,
                        if (isRegistered) "Désinscription réussie" else "Inscription réussie",
                        Toast.LENGTH_SHORT
                    ).show()
                    loadData() // Refresh data to update UI
                }
                is Resource.Error -> {
                    Toast.makeText(context, result.message, Toast.LENGTH_SHORT).show()
                }
                is Resource.Loading -> {}
            }

            binding.progressBar.isVisible = false
        }
    }

    override fun onDestroyView() {
        super.onDestroyView()
        _binding = null
    }
}