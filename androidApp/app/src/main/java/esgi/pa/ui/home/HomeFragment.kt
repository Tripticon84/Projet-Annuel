package esgi.pa.ui.home

import android.content.Intent
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
import esgi.pa.ui.login.LoginActivity
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

    // Track if data has been loaded
    private var dataLoaded = false
    private var isFirstLoad = true

    override fun onCreateView(
        inflater: LayoutInflater,
        container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View {
        _binding = FragmentHomeBinding.inflate(inflater, container, false)
        sessionManager = SessionManager(requireContext())

        // Check user ID early
        val userId = sessionManager.getUserId()
        Log.d(TAG, "Initial user ID check in onCreateView: $userId")

        return binding.root
    }

    override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        super.onViewCreated(view, savedInstanceState)

        viewModel = ViewModelProvider(this)[HomeViewModel::class.java]

        setupRecyclerViews()
        setupObservers()

        // Only try to load data if we haven't loaded yet
        if (isFirstLoad) {
            isFirstLoad = false
            checkUserAndLoadData()
        }
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
                    val events = resource.data ?: emptyList()
                    eventAdapter.updateEvents(events)
                    Log.d(TAG, "Events loaded: ${events.size}")
                    dataLoaded = true
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
                    val activities = resource.data ?: emptyList()
                    activityAdapter.updateActivities(activities)
                    Log.d(TAG, "Activities loaded: ${activities.size}")
                    dataLoaded = true
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

    private fun checkUserAndLoadData() {
        val userId = sessionManager.getUserId()
        Log.d(TAG, "Checking user ID for data loading: $userId")

        if (userId > 0) {
            // Valid user ID, load data
            loadData(userId)
        } else {
            // Invalid user ID but we have a token - show message and handle appropriately
            val token = sessionManager.getToken()
            if (token != null) {
                Log.e(TAG, "Valid token but invalid user ID")
                Toast.makeText(context, "Veuillez vous reconnecter pour récupérer vos informations", Toast.LENGTH_LONG).show()

                // Option 1: Just show error and empty state
                binding.progressBar.isVisible = false

                // Option 2: Redirect to login
                // Uncomment this if you want to force a re-login
                /*
                sessionManager.clearSession()
                val intent = Intent(context, LoginActivity::class.java)
                startActivity(intent)
                activity?.finish()
                */
            } else {
                // No token and no user ID, redirect to login
                Log.e(TAG, "No token and invalid user ID, redirecting to login")
                val intent = Intent(context, LoginActivity::class.java)
                startActivity(intent)
                activity?.finish()
            }
        }
    }

    private fun loadData(userId: Int) {
        Log.d(TAG, "Loading data for user ID: $userId")
        binding.progressBar.isVisible = true
        viewModel.loadUserEvents(userId)
        viewModel.loadUserActivities(userId)
    }

    override fun onResume() {
        super.onResume()
        // Always reload data when returning to the fragment
        val userId = sessionManager.getUserId()
        if (userId > 0) {
            Log.d(TAG, "Reloading data on resume")
            viewModel.loadUserEvents(userId)  // Add this to reload events
            viewModel.loadUserActivities(userId)
        }
    }

    override fun onDestroyView() {
        super.onDestroyView()
        _binding = null
    }


}