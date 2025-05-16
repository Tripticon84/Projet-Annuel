package esgi.pa.ui.activities

import android.util.Log
import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import esgi.pa.data.model.Activity
import esgi.pa.data.repository.ActivityRepository
import esgi.pa.data.repository.AuthRepository
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
    private val TAG = "ActivityListViewModel"
    private val _activities = MutableStateFlow<Resource<List<Activity>>>(Resource.Loading())
    val activities: StateFlow<Resource<List<Activity>>> = _activities

    private val dateFormat = SimpleDateFormat("yyyy-MM-dd", Locale.getDefault())
    private val today = Calendar.getInstance().apply {
        set(Calendar.HOUR_OF_DAY, 0)
        set(Calendar.MINUTE, 0)
        set(Calendar.SECOND, 0)
        set(Calendar.MILLISECOND, 0)
    }.time

    // Added method to expose repository for registration operations
    fun getRepository(): AuthRepository {
        return AuthRepository()
    }

    fun loadAllActivities() {
        viewModelScope.launch {
            _activities.value = Resource.Loading()

            Log.d(TAG, "Loading all activities")
            val result = activityRepository.getAllActivities()

            if (result is Resource.Success) {
                val activities = result.data ?: emptyList()

                // Log all activities to diagnose issues
                activities.forEach { activity ->
                    Log.d(TAG, "Loaded activity: ${activity.nom}, ID: ${activity.activity_id}")
                }

                val filteredAndSortedActivities = activities.filter { activity ->
                    // Filter out activities with invalid IDs
                    val validId = activity.activity_id > 0
                    if (!validId) {
                        Log.w(TAG, "Filtering out invalid activity ID: ${activity.activity_id} for ${activity.nom}")
                    }

                    // Filter by date
                    val validDate = try {
                        val activityDate = dateFormat.parse(activity.date)
                        activityDate != null && activityDate >= today
                    } catch (e: Exception) {
                        Log.e(TAG, "Date parsing error for ${activity.nom}: ${e.message}")
                        true // Include if date can't be parsed
                    }

                    validId && validDate
                }.sortedBy { activity ->
                    try {
                        dateFormat.parse(activity.date)
                    } catch (e: Exception) {
                        Date(Long.MAX_VALUE) // Default to future date if parsing fails
                    }
                }

                Log.d(TAG, "Filtered activities count: ${filteredAndSortedActivities.size}")
                _activities.value = Resource.Success(filteredAndSortedActivities)
            } else {
                Log.e(TAG, "Failed to load activities: ${(result as Resource.Error).message}")
                _activities.value = result
            }
        }
    }
}