package esgi.pa.ui.activities

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
import esgi.pa.databinding.FragmentActivitiesListBinding
import esgi.pa.util.Resource
import kotlinx.coroutines.launch

class ActivitiesListFragment : Fragment() {
    private var _binding: FragmentActivitiesListBinding? = null
    private val binding get() = _binding!!

    // Fix: Use the ViewModelFactory to provide the required repository parameter
    private val viewModel: ActivityListViewModel by viewModels { ActivityViewModelFactory() }
    private lateinit var adapter: ActivityAdapter

    override fun onCreateView(
        inflater: LayoutInflater,
        container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View {
        _binding = FragmentActivitiesListBinding.inflate(inflater, container, false)
        return binding.root
    }

    override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        super.onViewCreated(view, savedInstanceState)

        setupRecyclerView()
        observeViewModel()
        viewModel.loadAllActivities()
    }

    private fun setupRecyclerView() {
        adapter = ActivityAdapter()
        binding.recyclerViewActivities.adapter = adapter
        binding.recyclerViewActivities.layoutManager = LinearLayoutManager(requireContext())
    }

    private fun observeViewModel() {
        viewLifecycleOwner.lifecycleScope.launch {
            viewLifecycleOwner.repeatOnLifecycle(Lifecycle.State.STARTED) {
                viewModel.activities.collect { resource ->
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