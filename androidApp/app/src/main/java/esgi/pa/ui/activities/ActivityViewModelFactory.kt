package esgi.pa.ui.activities

import androidx.lifecycle.ViewModel
import androidx.lifecycle.ViewModelProvider
import esgi.pa.data.repository.ActivityRepository

class ActivityViewModelFactory : ViewModelProvider.Factory {
    override fun <T : ViewModel> create(modelClass: Class<T>): T {
        if (modelClass.isAssignableFrom(ActivityListViewModel::class.java)) {
            @Suppress("UNCHECKED_CAST")
            return ActivityListViewModel(ActivityRepository()) as T
        }
        throw IllegalArgumentException("Unknown ViewModel class")
    }
}