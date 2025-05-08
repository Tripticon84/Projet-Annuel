package esgi.pa.data.repository

import esgi.pa.data.api.NetworkModule
import esgi.pa.data.model.GetEmployeeEventRequest
import esgi.pa.data.model.GetEmployeeEventResponse
import esgi.pa.data.model.GetOneByCredentialsRequest
import esgi.pa.data.model.GetOneByCredentialsResponse
import esgi.pa.data.model.LoginRequest
import esgi.pa.data.model.LoginResponse
import esgi.pa.util.Resource
import retrofit2.HttpException
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
}