package esgi.pa.ui.planning

import android.os.Bundle
import android.util.Log
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import androidx.fragment.app.Fragment
import androidx.lifecycle.ViewModelProvider
import androidx.lifecycle.lifecycleScope
import androidx.recyclerview.widget.LinearLayoutManager
import esgi.pa.data.repository.AuthRepository
import esgi.pa.databinding.FragmentPlanningBinding
import esgi.pa.util.SessionManager
import kotlinx.coroutines.launch
import java.text.SimpleDateFormat
import java.util.Calendar
import java.util.Date
import java.util.Locale

class PlanningFragment : Fragment() {
    private val TAG = "PlanningFragment"
    private var _binding: FragmentPlanningBinding? = null
    private val binding get() = _binding!!
    private lateinit var viewModel: PlanningViewModel
    private lateinit var sessionManager: SessionManager
    private lateinit var planningAdapter: PlanningItemAdapter

    override fun onCreateView(
        inflater: LayoutInflater,
        container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View {
        _binding = FragmentPlanningBinding.inflate(inflater, container, false)
        sessionManager = SessionManager(requireContext())
        viewModel = ViewModelProvider(this,
            PlanningViewModelFactory(AuthRepository())
        )[PlanningViewModel::class.java]

        setupCalendarView()
        setupRecyclerView()
        loadUserData()

        return binding.root
    }

    private fun setupCalendarView() {
        // Set today's date as selected by default
        val today = Calendar.getInstance().time
        updateSelectedDayEvents(today)

        binding.calendarView.setOnDateChangeListener { _, year, month, dayOfMonth ->
            val calendar = Calendar.getInstance()
            calendar.set(year, month, dayOfMonth)
            val date = calendar.time
            updateSelectedDayEvents(date)
        }
    }

    private fun setupRecyclerView() {
        planningAdapter = PlanningItemAdapter()
        binding.rvDayEvents.layoutManager = LinearLayoutManager(context)
        binding.rvDayEvents.adapter = planningAdapter
    }

    private fun loadUserData() {
        val userId = sessionManager.getCollaborateurId()

        if (userId <= 0) {
            binding.tvNoEvents.visibility = View.VISIBLE
            binding.tvNoEvents.text = "Veuillez vous connecter pour accéder à votre planning"
            return
        }

        binding.progressBar.visibility = View.VISIBLE

        viewLifecycleOwner.lifecycleScope.launch {
            viewModel.loadUserData(userId)
            updateSelectedDayEvents(Calendar.getInstance().time)
            binding.progressBar.visibility = View.GONE
        }
    }

    private fun updateSelectedDayEvents(date: Date) {
        val dateFormat = SimpleDateFormat("yyyy-MM-dd", Locale.getDefault())
        val dateString = dateFormat.format(date)

        // Update the date header
        binding.tvSelectedDate.text = "Événements du ${dateFormat.format(date)}"

        Log.d(TAG, "Selected date: $dateString")

        // Filter activities and events for the selected date
        val activitiesForDay = viewModel.activities.value?.filter {
            it.date == dateString
        } ?: emptyList()

        val eventsForDay = viewModel.events.value?.filter {
            it.date == dateString
        } ?: emptyList()

        Log.d(TAG, "Found ${activitiesForDay.size} activities and ${eventsForDay.size} events for $dateString")

        val combinedItems = mutableListOf<PlanningItem>()

        // Convert activities to planning items
        activitiesForDay.forEach { activity ->
            combinedItems.add(
                PlanningItem(
                    id = activity.activity_id,
                    name = activity.nom,
                    type = "Activité: ${activity.type}",
                    date = activity.date,
                    isEvent = false
                )
            )
        }

        // Convert events to planning items
        eventsForDay.forEach { event ->
            combinedItems.add(
                PlanningItem(
                    id = event.evenement_id,
                    name = event.nom,
                    type = "Événement: ${event.type}",
                    date = event.date,
                    location = event.lieu,
                    isEvent = true
                )
            )
        }

        // Update UI
        if (combinedItems.isEmpty()) {
            binding.tvNoEvents.visibility = View.VISIBLE
            binding.tvNoEvents.text = "Pas d'événements programmés pour cette date"
            binding.rvDayEvents.visibility = View.GONE
        } else {
            binding.tvNoEvents.visibility = View.GONE
            binding.rvDayEvents.visibility = View.VISIBLE
            planningAdapter.updateItems(combinedItems)
        }
    }

    override fun onDestroyView() {
        super.onDestroyView()
        _binding = null
    }
}