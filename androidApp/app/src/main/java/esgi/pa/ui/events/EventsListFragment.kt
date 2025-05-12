package esgi.pa.ui.events

import android.os.Bundle
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.Toast
import androidx.core.view.isVisible
import androidx.fragment.app.Fragment
import androidx.fragment.app.viewModels
import androidx.lifecycle.Lifecycle
import androidx.lifecycle.lifecycleScope
import androidx.lifecycle.repeatOnLifecycle
import androidx.recyclerview.widget.LinearLayoutManager
import esgi.pa.databinding.FragmentEventsListBinding
import esgi.pa.util.Resource
import kotlinx.coroutines.launch

class EventsListFragment : Fragment() {
    private var _binding: FragmentEventsListBinding? = null
    private val binding get() = _binding!!

    private val viewModel: EventListViewModel by viewModels { EventViewModelFactory() }
    private lateinit var adapter: esgi.pa.ui.home.EventAdapter

    override fun onCreateView(
        inflater: LayoutInflater,
        container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View {
        _binding = FragmentEventsListBinding.inflate(inflater, container, false)
        return binding.root
    }

    override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        super.onViewCreated(view, savedInstanceState)

        setupRecyclerView()
        observeViewModel()
        viewModel.loadAllEvents()
    }

    private fun setupRecyclerView() {
        adapter = esgi.pa.ui.home.EventAdapter()
        binding.recyclerViewEvents.adapter = adapter
        binding.recyclerViewEvents.layoutManager = LinearLayoutManager(requireContext())
    }

    private fun observeViewModel() {
        viewLifecycleOwner.lifecycleScope.launch {
            viewLifecycleOwner.repeatOnLifecycle(Lifecycle.State.STARTED) {
                viewModel.events.collect { resource ->
                    when (resource) {
                        is Resource.Loading -> binding.progressBar.isVisible = true
                        is Resource.Success -> {
                            binding.progressBar.isVisible = false
                            adapter.submitList(resource.data)
                        }
                        is Resource.Error -> {
                            binding.progressBar.isVisible = false
                            Toast.makeText(requireContext(), resource.message, Toast.LENGTH_SHORT).show()
                        }
                    }
                }
            }
        }
    }

    override fun onDestroyView() {
        super.onDestroyView()
        _binding = null
    }
}