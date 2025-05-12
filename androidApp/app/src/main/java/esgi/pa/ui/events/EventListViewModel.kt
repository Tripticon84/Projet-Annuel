package esgi.pa.ui.events

import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import esgi.pa.data.model.Event
import esgi.pa.data.repository.EventRepository
import esgi.pa.util.Resource
import kotlinx.coroutines.flow.MutableStateFlow
import kotlinx.coroutines.flow.StateFlow
import kotlinx.coroutines.launch
import java.text.SimpleDateFormat
import java.util.Calendar
import java.util.Date
import java.util.Locale

class EventListViewModel(
    private val eventRepository: EventRepository
) : ViewModel() {
    private val _events = MutableStateFlow<Resource<List<Event>>>(Resource.Loading())
    val events: StateFlow<Resource<List<Event>>> = _events

    private val dateFormat = SimpleDateFormat("yyyy-MM-dd", Locale.getDefault())
    private val today = Calendar.getInstance().apply {
        set(Calendar.HOUR_OF_DAY, 0)
        set(Calendar.MINUTE, 0)
        set(Calendar.SECOND, 0)
        set(Calendar.MILLISECOND, 0)
    }.time

    fun loadAllEvents() {
        viewModelScope.launch {
            _events.value = Resource.Loading()
            val result = eventRepository.getAllEvents()

            if (result is Resource.Success) {
                val filteredAndSortedEvents = result.data?.let { events ->
                    events.filter { event ->
                        try {
                            val eventDate = dateFormat.parse(event.date)
                            eventDate != null && eventDate >= today
                        } catch (e: Exception) {
                            true // Include if date can't be parsed
                        }
                    }.sortedBy { event ->
                        try {
                            dateFormat.parse(event.date)
                        } catch (e: Exception) {
                            Date(Long.MAX_VALUE) // Default to future date if parsing fails
                        }
                    }
                } ?: emptyList()

                _events.value = Resource.Success(filteredAndSortedEvents)
            } else {
                _events.value = result
            }
        }
    }
}