package esgi.pa.ui.events

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
import esgi.pa.data.model.Event
import esgi.pa.data.repository.AuthRepository
import esgi.pa.databinding.FragmentEventsListBinding
import esgi.pa.util.Resource
import esgi.pa.util.SessionManager
import kotlinx.coroutines.launch

class EventsListFragment : Fragment() {
    private var _binding: FragmentEventsListBinding? = null
    private val binding get() = _binding!!

    private val viewModel: EventListViewModel by viewModels { EventViewModelFactory() }
    private val authRepository = AuthRepository()
    private var userId: Int = -1
    private lateinit var sessionManager: SessionManager

    private val adapter = EventAdapter(
        onRegisterClick = { event, isRegistered ->
            handleEventRegistration(event, isRegistered)
        }
    )

    override fun onCreateView(
        inflater: LayoutInflater,
        container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View {
        _binding = FragmentEventsListBinding.inflate(inflater, container, false)
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
        binding.recyclerViewEvents.adapter = adapter
        binding.recyclerViewEvents.layoutManager = LinearLayoutManager(requireContext())
    }

    private fun loadData() {
        viewLifecycleOwner.lifecycleScope.launch {
            binding.progressBar.isVisible = true

            // Load all events
            when (val eventsResult = authRepository.getAllEvents()) {
                is Resource.Success -> {
                    adapter.updateEvents(eventsResult.data ?: emptyList())
                }
                is Resource.Error -> {
                    Toast.makeText(context, eventsResult.message, Toast.LENGTH_SHORT).show()
                }
                is Resource.Loading -> {}
            }

            // Get registered events
            when (val userEventsResult = authRepository.getEmployeeEvents(userId)) {
                is Resource.Success -> {
                    adapter.updateRegisteredEvents(userEventsResult.data ?: emptyList())
                }
                is Resource.Error -> {
                    Toast.makeText(context, userEventsResult.message, Toast.LENGTH_SHORT).show()
                }
                is Resource.Loading -> {}
            }

            binding.progressBar.isVisible = false
        }
    }

    private fun handleEventRegistration(event: Event, isRegistered: Boolean) {
        viewLifecycleOwner.lifecycleScope.launch {
            binding.progressBar.isVisible = true

            val result = if (isRegistered) {
                authRepository.unregisterFromEvent(userId, event.evenement_id)
            } else {
                authRepository.registerToEvent(userId, event.evenement_id)
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