package esgi.pa.ui.planning

import android.os.Bundle
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.Toast
import androidx.fragment.app.Fragment
import androidx.lifecycle.ViewModelProvider
import esgi.pa.databinding.FragmentPlanningBinding
import java.text.SimpleDateFormat
import java.util.Calendar
import java.util.Locale

class PlanningFragment : Fragment() {
    private var _binding: FragmentPlanningBinding? = null
    private val binding get() = _binding!!
    private lateinit var viewModel: PlanningViewModel
    private val displayFormatter = SimpleDateFormat("dd/MM/yyyy", Locale.getDefault())

    override fun onCreateView(
        inflater: LayoutInflater,
        container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View {
        _binding = FragmentPlanningBinding.inflate(inflater, container, false)
        return binding.root
    }

    override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        super.onViewCreated(view, savedInstanceState)

        // Initialize ViewModel
        viewModel = ViewModelProvider(this)[PlanningViewModel::class.java]

        setupCalendarView()
        setupObservers()
        viewModel.loadData()
    }

    private fun setupCalendarView() {
        // Using standard Android CalendarView
        binding.simpleCalendarView.setOnDateChangeListener { _, year, month, dayOfMonth ->
            // Note: month is 0-based in Android's CalendarView
            val calendar = Calendar.getInstance()
            calendar.set(year, month, dayOfMonth)

            // Update the UI
            val date = calendar.time
            binding.textEvents.text = "Événements du ${displayFormatter.format(date)}"

            // Tell the ViewModel about the selection
            viewModel.onDateSelected(year, month + 1, dayOfMonth)
        }
    }

    private fun setupObservers() {
        viewModel.eventsForSelectedDate.observe(viewLifecycleOwner) { events ->
            if (events.isEmpty()) {
                binding.textEvents.text = binding.textEvents.text.toString() + "\nAucun événement"
            } else {
                val eventNames = events.joinToString(", ") { it.nom }
                binding.textEvents.text = binding.textEvents.text.toString() + "\nÉvénements: $eventNames"
            }
        }

        viewModel.activitiesForSelectedDate.observe(viewLifecycleOwner) { activities ->
            if (activities.isNotEmpty()) {
                val activityNames = activities.joinToString(", ") { it.nom }
                binding.textEvents.text = binding.textEvents.text.toString() +
                        "\nActivités: $activityNames"
            }
        }

        viewModel.error.observe(viewLifecycleOwner) { error ->
            if (error.isNotEmpty()) {
                Toast.makeText(requireContext(), error, Toast.LENGTH_LONG).show()
            }
        }
    }

    override fun onDestroyView() {
        super.onDestroyView()
        _binding = null
    }
}