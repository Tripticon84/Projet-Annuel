// AuthRepository.kt
package esgi.pa.data.repository

import esgi.pa.data.api.NetworkModule
import esgi.pa.data.model.LoginRequest
import esgi.pa.data.model.LoginResponse
import esgi.pa.util.Resource
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.withContext

class AuthRepository {
    private val apiService = NetworkModule.apiService

    suspend fun login(username: String, password: String): Resource<LoginResponse> {
        return withContext(Dispatchers.IO) {
            try {
                val response = apiService.login(LoginRequest(username, password))

                if (response.isSuccessful) {
                    val body = response.body()
                    if (body != null) {
                        Resource.Success(body)
                    } else {
                        Resource.Error("Empty response from server")
                    }
                } else {
                    val errorMsg = when (response.code()) {
                        401 -> "Invalid username or password"
                        403 -> "This collaborator account is deactivated"
                        404 -> "Collaborator not found"
                        else -> "Error ${response.code()}: ${response.message()}"
                    }
                    Resource.Error(errorMsg)
                }
            } catch (e: Exception) {
                Resource.Error("Network error: ${e.message ?: "Unknown error"}")
            }
        }
    }
}