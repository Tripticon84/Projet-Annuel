package esgi.pa.ui.home

import android.os.Bundle
import android.util.Log
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.Toast
import androidx.core.view.isVisible
import androidx.fragment.app.Fragment
import androidx.lifecycle.ViewModelProvider
import androidx.recyclerview.widget.LinearLayoutManager
import esgi.pa.databinding.FragmentHomeBinding
import esgi.pa.util.Resource
import esgi.pa.util.SessionManager

class HomeFragment : Fragment() {
    private val TAG = "HomeFragment"
    private var _binding: FragmentHomeBinding? = null
    private val binding get() = _binding!!

    private lateinit var viewModel: HomeViewModel
    private lateinit var sessionManager: SessionManager

    private val eventAdapter = HomeEventAdapter()
    private val activityAdapter = HomeActivityAdapter()

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

        viewModel = ViewModelProvider(this)[HomeViewModel::class.java]
        sessionManager = SessionManager(requireContext())

        setupRecyclerViews()
        setupObservers()
        loadData() // Load data as soon as view is created
    }

    private fun setupRecyclerViews() {
        binding.eventsRecyclerView.apply {
            adapter = eventAdapter
            layoutManager = LinearLayoutManager(requireContext())
        }

        binding.activitiesRecyclerView.apply {
            adapter = activityAdapter
            layoutManager = LinearLayoutManager(requireContext())
        }
    }

    private fun setupObservers() {
        viewModel.events.observe(viewLifecycleOwner) { resource ->
            when (resource) {
                is Resource.Success -> {
                    binding.progressBar.isVisible = false
                    eventAdapter.updateEvents(resource.data ?: emptyList())
                    Log.d(TAG, "Events loaded: ${resource.data?.size ?: 0}")
                }
                is Resource.Error -> {
                    binding.progressBar.isVisible = false
                    Log.e(TAG, "Error loading events: ${resource.message}")
                    Toast.makeText(context, "Erreur: ${resource.message}", Toast.LENGTH_SHORT).show()
                }
                is Resource.Loading -> {
                    binding.progressBar.isVisible = true
                }
            }
        }

        viewModel.activities.observe(viewLifecycleOwner) { resource ->
            when (resource) {
                is Resource.Success -> {
                    binding.progressBar.isVisible = false
                    activityAdapter.updateActivities(resource.data ?: emptyList())
                    Log.d(TAG, "Activities loaded: ${resource.data?.size ?: 0}")
                }
                is Resource.Error -> {
                    binding.progressBar.isVisible = false
                    Log.e(TAG, "Error loading activities: ${resource.message}")
                    Toast.makeText(context, "Erreur: ${resource.message}", Toast.LENGTH_SHORT).show()
                }
                is Resource.Loading -> {
                    binding.progressBar.isVisible = true
                }
            }
        }
    }

    private fun loadData() {
        val userId = sessionManager.getUserId()
        Log.d(TAG, "Loading data for user ID: $userId")

        if (userId > 0) {
            binding.progressBar.isVisible = true
            viewModel.loadUserEvents(userId)
            viewModel.loadUserActivities(userId)
        } else {
            Log.e(TAG, "Invalid user ID: $userId")
            Toast.makeText(context, "ID utilisateur non valide", Toast.LENGTH_SHORT).show()
        }
    }

    override fun onDestroyView() {
        super.onDestroyView()
        _binding = null
    }

    override fun onResume() {
        super.onResume()
        // Reload data when returning to fragment
        loadData()
    }
}