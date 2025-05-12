package esgi.pa.data.repository

import esgi.pa.data.api.NetworkModule
import esgi.pa.data.model.Activity
import esgi.pa.util.Resource
import android.util.Log

class ActivityRepository {
    private val apiService = NetworkModule.apiService

    suspend fun getAllActivities(): Resource<List<Activity>> {
        return try {
            val response = apiService.getAllActivity()
            Log.d("ActivityRepository", "Raw API response: ${response.body()}")

            if (response.isSuccessful) {
                Resource.Success(response.body() ?: emptyList())
            } else {
                Resource.Error("Failed to retrieve activities: ${response.message()}")
            }
        } catch (e: Exception) {
            Log.e("ActivityRepository", "Exception getting activities", e)
            Resource.Error("Error: ${e.message}")
        }
    }
}