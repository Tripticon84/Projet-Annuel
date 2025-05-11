package esgi.pa.ui.home

import android.app.Application
import android.util.Log
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
import java.text.ParseException
import java.text.SimpleDateFormat
import java.util.Calendar
import java.util.Locale

class HomeViewModel(application: Application) : AndroidViewModel(application) {
    private val TAG = "HomeViewModel"
    private val repository = AuthRepository()
    private val sessionManager = SessionManager(application)
    private val dateFormatter = SimpleDateFormat("yyyy-MM-dd", Locale.getDefault())
    private val today = Calendar.getInstance().apply {
        set(Calendar.HOUR_OF_DAY, 0)
        set(Calendar.MINUTE, 0)
        set(Calendar.SECOND, 0)
        set(Calendar.MILLISECOND, 0)
    }

    private val _events = MutableLiveData<List<Event>>()
    val events: LiveData<List<Event>> = _events

    private val _error = MutableLiveData<String>()
    val error: LiveData<String> = _error

    private val _isLoading = MutableLiveData<Boolean>()
    val isLoading: LiveData<Boolean> = _isLoading

    private val _activities = MutableLiveData<List<Activity>>()
    val activities: LiveData<List<Activity>> = _activities

    fun loadEmployeeEvents(collaborateurId: Int) {
        _isLoading.value = true
        Log.d(TAG, "Loading events for collaborateur: $collaborateurId")

        viewModelScope.launch(Dispatchers.IO) {
            when (val response = repository.getEmployeeEvents(collaborateurId)) {
                is Resource.Success -> {
                    Log.d(TAG, "API call successful")
                    if (response.data == null) {
                        Log.e(TAG, "Response data is null")
                        _error.postValue("Données de réponse nulles")
                    } else {
                        val filteredEvents = filterUpcomingEvents(response.data)
                        Log.d(TAG, "Total events: ${response.data.size}, Upcoming events: ${filteredEvents.size}")
                        _events.postValue(filteredEvents)
                    }
                    _isLoading.postValue(false)
                }

                is Resource.Error -> {
                    Log.e(TAG, "Error getting events: ${response.message}")
                    _error.postValue(response.message ?: "Impossible de récupérer les événements")
                    _isLoading.postValue(false)
                }

                is Resource.Loading -> {
                    Log.d(TAG, "Resource.Loading state")
                }
            }
        }
    }

    fun loadEmployeeActivities(collaborateurId: Int) {
        Log.d(TAG, "Loading activities for collaborateur: $collaborateurId")

        viewModelScope.launch(Dispatchers.IO) {
            when (val response = repository.getEmployeeActivities(collaborateurId)) {
                is Resource.Success -> {
                    Log.d(TAG, "Activities API call successful")
                    if (response.data == null) {
                        Log.e(TAG, "Activities response data is null")
                    } else {
                        val filteredActivities = filterUpcomingActivities(response.data)
                        Log.d(TAG, "Total activities: ${response.data.size}, Upcoming activities: ${filteredActivities.size}")
                        _activities.postValue(filteredActivities)
                    }
                }

                is Resource.Error -> {
                    Log.e(TAG, "Error getting activities: ${response.message}")
                }

                is Resource.Loading -> {
                    Log.d(TAG, "Activities Resource.Loading state")
                }
            }
        }
    }

    private fun filterUpcomingEvents(events: List<Event>): List<Event> {
        return events.filter { event ->
            try {
                val eventDate = Calendar.getInstance()
                dateFormatter.parse(event.date)?.let { parsedDate ->
                    eventDate.time = parsedDate
                    eventDate.set(Calendar.HOUR_OF_DAY, 0)
                    eventDate.set(Calendar.MINUTE, 0)
                    eventDate.set(Calendar.SECOND, 0)
                    eventDate.set(Calendar.MILLISECOND, 0)
                    eventDate.timeInMillis >= today.timeInMillis
                } ?: run {
                    Log.e(TAG, "Error parsing date for event ${event.nom}: ${event.date}")
                    true // Keep the event if we can't parse the date
                }
            } catch (e: ParseException) {
                Log.e(TAG, "Error parsing date for event ${event.nom}: ${event.date}", e)
                true
            }
        }
    }

    private fun filterUpcomingActivities(activities: List<Activity>): List<Activity> {
        return activities.filter { activity ->
            try {
                val activityDate = Calendar.getInstance()
                dateFormatter.parse(activity.date)?.let { parsedDate ->
                    activityDate.time = parsedDate
                    activityDate.set(Calendar.HOUR_OF_DAY, 0)
                    activityDate.set(Calendar.MINUTE, 0)
                    activityDate.set(Calendar.SECOND, 0)
                    activityDate.set(Calendar.MILLISECOND, 0)
                    activityDate.timeInMillis >= today.timeInMillis
                } ?: run {
                    Log.e(TAG, "Error parsing date for activity ${activity.nom}: ${activity.date}")
                    true // Keep the activity if we can't parse the date
                }
            } catch (e: ParseException) {
                Log.e(TAG, "Error parsing date for activity ${activity.nom}: ${activity.date}", e)
                true
            }
        }
    }

    fun loadCurrentUserData() {
        val collaborateurId = sessionManager.getCollaborateurId()
        Log.d(TAG, "Chargement des données pour l'utilisateur avec ID: $collaborateurId")

        if (collaborateurId != -1) {
            loadEmployeeEvents(collaborateurId)
            loadEmployeeActivities(collaborateurId)
        } else {
            _error.postValue("ID utilisateur non disponible")
        }
    }
}