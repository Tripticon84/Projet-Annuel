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

class HomeViewModel : ViewModel() {
    private val authRepository = AuthRepository()

    private val _events = MutableLiveData<Resource<List<Event>>>()
    val events: LiveData<Resource<List<Event>>> = _events

    private val _activities = MutableLiveData<Resource<List<Activity>>>()
    val activities: LiveData<Resource<List<Activity>>> = _activities

    fun loadUserEvents(userId: Int) {
        viewModelScope.launch {
            _events.value = Resource.Loading()
            _events.value = authRepository.getEmployeeEvents(userId)
        }
    }

    fun loadUserActivities(userId: Int) {
        viewModelScope.launch {
            _activities.value = Resource.Loading()
            _activities.value = authRepository.getEmployeeActivities(userId)
        }
    }
}