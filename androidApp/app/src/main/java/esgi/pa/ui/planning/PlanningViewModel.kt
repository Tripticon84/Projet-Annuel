package esgi.pa.ui.planning

import android.app.Application
import androidx.lifecycle.AndroidViewModel
import androidx.lifecycle.LiveData
import androidx.lifecycle.MutableLiveData
import androidx.lifecycle.viewModelScope
import esgi.pa.data.model.Activity
import esgi.pa.data.model.Event
import esgi.pa.data.repository.AuthRepository
import esgi.pa.util.Resource
import esgi.pa.util.SessionManager
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.launch
import java.text.SimpleDateFormat
import java.util.Locale

class PlanningViewModel(application: Application) : AndroidViewModel(application) {
    private val repository = AuthRepository()
    private val sessionManager = SessionManager(application)
    private val dateFormatter = SimpleDateFormat("yyyy-MM-dd", Locale.getDefault())

    private val _eventDates = MutableLiveData<Set<String>>()
    val eventDates: LiveData<Set<String>> = _eventDates

    private val _eventsForSelectedDate = MutableLiveData<List<Event>>()
    val eventsForSelectedDate: LiveData<List<Event>> = _eventsForSelectedDate

    private val _activitiesForSelectedDate = MutableLiveData<List<Activity>>()
    val activitiesForSelectedDate: LiveData<List<Activity>> = _activitiesForSelectedDate

    private val _isLoading = MutableLiveData<Boolean>()
    val isLoading: LiveData<Boolean> = _isLoading

    private val _error = MutableLiveData<String>()
    val error: LiveData<String> = _error

    private var allEvents = listOf<Event>()
    private var allActivities = listOf<Activity>()

    fun loadData() {
        val collaborateurId = sessionManager.getCollaborateurId()
        if (collaborateurId != -1) {
            loadEvents(collaborateurId)
            loadActivities(collaborateurId)
        } else {
            _error.value = "ID utilisateur non disponible"
        }
    }

    private fun loadEvents(collaborateurId: Int) {
        _isLoading.value = true
        viewModelScope.launch(Dispatchers.IO) {
            when (val result = repository.getEmployeeEvents(collaborateurId)) {
                is Resource.Success -> {
                    result.data?.let { events ->
                        allEvents = events
                        updateCalendarDates()
                    }
                }
                is Resource.Error -> {
                    _error.postValue(result.message ?: "Erreur lors du chargement des événements")
                }
                is Resource.Loading -> {
                    // Loading state handled by _isLoading
                }
            }
            _isLoading.postValue(false)
        }
    }

    private fun loadActivities(collaborateurId: Int) {
        viewModelScope.launch(Dispatchers.IO) {
            when (val result = repository.getEmployeeActivities(collaborateurId)) {
                is Resource.Success -> {
                    result.data?.let { activities ->
                        allActivities = activities
                        updateCalendarDates()
                    }
                }
                is Resource.Error -> {
                    _error.postValue(result.message ?: "Erreur lors du chargement des activités")
                }
                is Resource.Loading -> {
                    // Loading state handled by _isLoading
                }
            }
        }
    }

    private fun updateCalendarDates() {
        val datesSet = mutableSetOf<String>()

        // Add event dates
        for (event in allEvents) {
            datesSet.add(event.date)
        }

        // Add activity dates
        for (activity in allActivities) {
            datesSet.add(activity.date)
        }

        _eventDates.postValue(datesSet)
    }

    // Called with year, month (1-12), day from the Fragment
    fun onDateSelected(year: Int, month: Int, day: Int) {
        // Format as yyyy-MM-dd for comparison with our database dates
        val selectedDate = String.format("%04d-%02d-%02d", year, month, day)

        // Filter events for selected date
        val filteredEvents = allEvents.filter { it.date == selectedDate }
        _eventsForSelectedDate.postValue(filteredEvents)

        // Filter activities for selected date
        val filteredActivities = allActivities.filter { it.date == selectedDate }
        _activitiesForSelectedDate.postValue(filteredActivities)
    }
}