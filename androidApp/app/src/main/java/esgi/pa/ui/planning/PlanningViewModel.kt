package esgi.pa.ui.planning

import androidx.lifecycle.ViewModel
import androidx.lifecycle.ViewModelProvider
import androidx.lifecycle.viewModelScope
import esgi.pa.data.model.Activity
import esgi.pa.data.model.Event
import esgi.pa.data.repository.AuthRepository
import esgi.pa.util.Resource
import kotlinx.coroutines.flow.MutableStateFlow
import kotlinx.coroutines.flow.StateFlow
import kotlinx.coroutines.launch

class PlanningViewModel(private val repository: AuthRepository) : ViewModel() {
    private val _activities = MutableStateFlow<List<Activity>>(emptyList())
    val activities: StateFlow<List<Activity>> = _activities

    private val _events = MutableStateFlow<List<Event>>(emptyList())
    val events: StateFlow<List<Event>> = _events

    suspend fun loadUserData(userId: Int) {
        viewModelScope.launch {
            // Load activities
            when (val activitiesResult = repository.getEmployeeActivities(userId)) {
                is Resource.Success -> {
                    _activities.value = activitiesResult.data ?: emptyList()
                }
                else -> {
                    _activities.value = emptyList()
                }
            }

            // Load events
            when (val eventsResult = repository.getEmployeeEvents(userId)) {
                is Resource.Success -> {
                    _events.value = eventsResult.data ?: emptyList()
                }
                else -> {
                    _events.value = emptyList()
                }
            }
        }
    }
}

class PlanningViewModelFactory(private val repository: AuthRepository) : ViewModelProvider.Factory {
    override fun <T : ViewModel> create(modelClass: Class<T>): T {
        if (modelClass.isAssignableFrom(PlanningViewModel::class.java)) {
            @Suppress("UNCHECKED_CAST")
            return PlanningViewModel(repository) as T
        }
        throw IllegalArgumentException("Unknown ViewModel class")
    }
}