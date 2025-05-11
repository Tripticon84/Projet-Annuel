package esgi.pa.data.repository

import esgi.pa.data.api.NetworkModule
import esgi.pa.data.model.GetOneByCredentialsResponse
import esgi.pa.data.model.LoginRequest
import esgi.pa.data.model.LoginResponse
import esgi.pa.data.model.Event
import esgi.pa.util.Resource
import android.util.Log
import esgi.pa.data.model.Activity
import java.io.IOException

class AuthRepository {
    private val apiService = NetworkModule.apiService

    suspend fun login(username: String, password: String): Resource<LoginResponse> {
        return try {
            val response = apiService.login(LoginRequest(username, password))
            if (response.isSuccessful && response.body() != null) {
                Resource.Success(response.body()!!)
            } else {
                Resource.Error("Échec de connexion: ${response.message()}")
            }
        } catch (e: IOException) {
            Resource.Error("Erreur réseau: ${e.message}")
        } catch (e: Exception) {
            Resource.Error("Erreur: ${e.message}")
        }
    }

    suspend fun getEmployeeByCredentials(username: String, password: String): Resource<GetOneByCredentialsResponse> {
        return try {
            val response = apiService.authentificate(username, password)
            if (response.isSuccessful) {
                response.body()?.let {
                    Resource.Success(it)
                } ?: Resource.Error("Response body is empty")
            } else {
                Resource.Error("Authentication failed: ${response.message()}")
            }
        } catch (e: Exception) {
            Resource.Error("Network error: ${e.message}")
        }
    }

    suspend fun getEmployeeEvents(collaborateurId: Int): Resource<List<Event>> {
        return try {
            val response = apiService.getEmployeeEvent(collaborateurId)
            Log.d("AuthRepository", "Raw API response: ${response.body()}")

            if (response.isSuccessful) {
                val events = response.body() ?: emptyList<Event>()
                Resource.Success(events)
            } else {
                Resource.Error("Échec de récupération des événements: ${response.message()}")
            }
        } catch (e: Exception) {
            Log.e("AuthRepository", "Exception getting events", e)
            Resource.Error("Erreur: ${e.message}")
        }
    }

    suspend fun getEmployeeActivities(collaborateurId: Int): Resource<List<Activity>> {
        return try {
            val response = apiService.getEmployeeActivity(collaborateurId)
            Log.d("AuthRepository", "Raw API response for activities: ${response.body()}")

            if (response.isSuccessful) {
                val activities = response.body() ?: emptyList<Activity>()
                Resource.Success(activities)
            } else {
                Resource.Error("Échec de récupération des activités: ${response.message()}")
            }
        } catch (e: Exception) {
            Log.e("AuthRepository", "Exception getting activities", e)
            Resource.Error("Erreur: ${e.message}")
        }
    }
}