package esgi.pa.ui.events

import androidx.lifecycle.ViewModel
import androidx.lifecycle.ViewModelProvider
import esgi.pa.data.repository.EventRepository

class EventViewModelFactory : ViewModelProvider.Factory {
    override fun <T : ViewModel> create(modelClass: Class<T>): T {
        if (modelClass.isAssignableFrom(EventListViewModel::class.java)) {
            @Suppress("UNCHECKED_CAST")
            return EventListViewModel(EventRepository()) as T
        }
        throw IllegalArgumentException("Unknown ViewModel class")
    }
}