package esgi.pa.ui.home

import android.os.Bundle
import android.util.Log
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.Toast
import androidx.fragment.app.Fragment
import androidx.lifecycle.ViewModelProvider
import androidx.recyclerview.widget.LinearLayoutManager
import esgi.pa.databinding.FragmentHomeBinding
import esgi.pa.util.SessionManager

class HomeFragment : Fragment() {
    private val TAG = "HomeFragment"
    private var _binding: FragmentHomeBinding? = null
    private val binding get() = _binding!!

    private lateinit var viewModel: HomeViewModel
    private lateinit var eventsAdapter: EventAdapter
    private lateinit var activitiesAdapter: ActivityAdapter
    private lateinit var sessionManager: SessionManager

    override fun onCreateView(
        inflater: LayoutInflater,
        container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View {
        _binding = FragmentHomeBinding.inflate(inflater, container, false)
        return binding.root
    }

    override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        super.onViewCreated(view, savedInstanceState)

        // Initialize ViewModel and SessionManager
        viewModel = ViewModelProvider(this)[HomeViewModel::class.java]
        sessionManager = SessionManager(requireContext())

        setupRecyclerViews()
        setupObservers()
        loadData()
    }

    private fun setupRecyclerViews() {
        // Setup events RecyclerView
        eventsAdapter = EventAdapter()
        binding.eventsRecyclerView.apply {
            adapter = eventsAdapter
            layoutManager = LinearLayoutManager(requireContext())
        }

        // Setup activities RecyclerView
        activitiesAdapter = ActivityAdapter()
        binding.activitiesRecyclerView.apply {
            adapter = activitiesAdapter
            layoutManager = LinearLayoutManager(requireContext())
        }
    }

    private fun setupObservers() {
        // Observe loading state
        viewModel.isLoading.observe(viewLifecycleOwner) { isLoading ->
            Log.d(TAG, "Loading state changed to: $isLoading")
            binding.progressBar.visibility = if (isLoading) View.VISIBLE else View.GONE
        }

        // Observe events
        viewModel.events.observe(viewLifecycleOwner) { events ->
            if (events.isNotEmpty()) {
                eventsAdapter.submitList(events)
            }
        }

        // Observe activities
        viewModel.activities.observe(viewLifecycleOwner) { activities ->
            if (activities.isNotEmpty()) {
                activitiesAdapter.submitList(activities)
            }
        }

        // Observe errors
        viewModel.error.observe(viewLifecycleOwner) { errorMsg ->
            if (errorMsg.isNotEmpty()) {
                Log.e(TAG, "Error received: $errorMsg")
                Toast.makeText(requireContext(), errorMsg, Toast.LENGTH_LONG).show()
            }
        }
    }

    private fun loadData() {
        val collaborateurId = sessionManager.getCollaborateurId()
        if (collaborateurId != -1) {
            Log.d(TAG, "Loading events for valid collaborateurId: $collaborateurId")
            viewModel.loadEmployeeEvents(collaborateurId)
            viewModel.loadEmployeeActivities(collaborateurId)
        } else {
            Log.e(TAG, "Invalid collaborateurId, will retry after delay")
            // Retry after a short delay to allow login process to complete
            view?.postDelayed({
                val retryId = sessionManager.getCollaborateurId()
                if (retryId != -1) {
                    Log.d(TAG, "Retry successful, got valid collaborateurId: $retryId")
                    viewModel.loadEmployeeEvents(retryId)
                    viewModel.loadEmployeeActivities(retryId)
                } else {
                    Log.e(TAG, "Retry failed, still invalid collaborateurId")
                    Toast.makeText(requireContext(), "Erreur: ID utilisateur invalide", Toast.LENGTH_LONG).show()
                }
            }, 1000) // Wait 1 second before retrying
        }
    }

    override fun onDestroyView() {
        super.onDestroyView()
        _binding = null
    }

    override fun onResume() {
        super.onResume()
        loadData()  // Recharge les données à chaque affichage du fragment
    }
}