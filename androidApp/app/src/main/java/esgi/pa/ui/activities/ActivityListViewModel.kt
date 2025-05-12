package esgi.pa.ui.activities

import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import esgi.pa.data.model.Activity
import esgi.pa.data.repository.ActivityRepository
import esgi.pa.util.Resource
import kotlinx.coroutines.flow.MutableStateFlow
import kotlinx.coroutines.flow.StateFlow
import kotlinx.coroutines.launch
import java.text.SimpleDateFormat
import java.util.Calendar
import java.util.Date
import java.util.Locale

class ActivityListViewModel(
    private val activityRepository: ActivityRepository
) : ViewModel() {
    private val _activities = MutableStateFlow<Resource<List<Activity>>>(Resource.Loading())
    val activities: StateFlow<Resource<List<Activity>>> = _activities

    private val dateFormat = SimpleDateFormat("yyyy-MM-dd", Locale.getDefault())
    private val today = Calendar.getInstance().apply {
        set(Calendar.HOUR_OF_DAY, 0)
        set(Calendar.MINUTE, 0)
        set(Calendar.SECOND, 0)
        set(Calendar.MILLISECOND, 0)
    }.time

    fun loadAllActivities() {
        viewModelScope.launch {
            _activities.value = Resource.Loading()
            val result = activityRepository.getAllActivities()

            if (result is Resource.Success) {
                val filteredAndSortedActivities = result.data?.let { activities ->
                    activities.filter { activity ->
                        try {
                            val activityDate = dateFormat.parse(activity.date)
                            activityDate != null && activityDate >= today
                        } catch (e: Exception) {
                            true // Include if date can't be parsed
                        }
                    }.sortedBy { activity ->
                        try {
                            dateFormat.parse(activity.date)
                        } catch (e: Exception) {
                            Date(Long.MAX_VALUE) // Default to future date if parsing fails
                        }
                    }
                } ?: emptyList()

                _activities.value = Resource.Success(filteredAndSortedActivities)
            } else {
                _activities.value = result
            }
        }
    }
}