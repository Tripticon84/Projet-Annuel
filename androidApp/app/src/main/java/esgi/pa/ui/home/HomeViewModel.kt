package esgi.pa.ui.home

import androidx.lifecycle.LiveData
import androidx.lifecycle.MutableLiveData
import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import esgi.pa.data.model.Activity
import esgi.pa.data.model.Event
import esgi.pa.data.repository.AuthRepository
import esgi.pa.util.Resource
import kotlinx.coroutines.launch
import java.text.SimpleDateFormat
import java.util.Calendar
import java.util.Locale

class HomeViewModel : ViewModel() {
    private val TAG = "HomeViewModel"
    private val authRepository = AuthRepository()

    private val _events = MutableLiveData<Resource<List<Event>>>()
    val events: LiveData<Resource<List<Event>>> = _events

    private val _activities = MutableLiveData<Resource<List<Activity>>>()
    val activities: LiveData<Resource<List<Activity>>> = _activities

    // Date formatting and comparison setup
    private val dateFormat = SimpleDateFormat("yyyy-MM-dd", Locale.getDefault())
    private val today = Calendar.getInstance().apply {
        set(Calendar.HOUR_OF_DAY, 0)
        set(Calendar.MINUTE, 0)
        set(Calendar.SECOND, 0)
        set(Calendar.MILLISECOND, 0)
    }.time

    fun loadUserEvents(userId: Int) {
        viewModelScope.launch {
            _events.value = Resource.Loading()
            _events.value = authRepository.getEmployeeEvents(userId)
        }
    }

    fun loadUserActivities(userId: Int) {
        viewModelScope.launch {
            _activities.value = Resource.Loading()

            // Get the raw response
            val response = authRepository.getEmployeeActivities(userId)

            if (response is Resource.Success) {
                // Filter activities by date
                val filteredActivities = response.data?.filter { activity ->
                    try {
                        val activityDate = dateFormat.parse(activity.date)
                        activityDate != null && activityDate >= today
                    } catch (e: Exception) {
                        false // Exclude if date can't be parsed (more strict than Activities)
                    }
                } ?: emptyList()

                _activities.value = Resource.Success(filteredActivities)
            } else {
                _activities.value = response
            }
        }
    }
}