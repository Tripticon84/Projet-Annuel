package esgi.pa.data.repository

import android.util.Log
import esgi.pa.data.api.NetworkModule
import esgi.pa.data.model.Event
import esgi.pa.util.Resource

class EventRepository {
    private val apiService = NetworkModule.apiService

    suspend fun getAllEvents(): Resource<List<Event>> {
        return try {
            val response = apiService.getAllEvent()
            Log.d("EventRepository", "Raw API response: ${response.body()}")

            if (response.isSuccessful) {
                Resource.Success(response.body() ?: emptyList())
            } else {
                Resource.Error("Failed to retrieve events: ${response.message()}")
            }
        } catch (e: Exception) {
            Log.e("EventRepository", "Exception getting events", e)
            Resource.Error("Error: ${e.message}")
        }
    }
}