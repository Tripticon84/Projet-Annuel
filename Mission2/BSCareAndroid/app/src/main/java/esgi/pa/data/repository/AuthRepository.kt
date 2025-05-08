// AuthRepository.kt
package esgi.pa.data.repository

import esgi.pa.data.api.NetworkModule
import esgi.pa.data.model.LoginRequest
import esgi.pa.data.model.LoginResponse
import esgi.pa.util.Resource
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.withContext
import kotlin.let

class AuthRepository {
    private val apiService = NetworkModule.apiService

    suspend fun login(email: String, password: String): Resource<LoginResponse> {
        return withContext(Dispatchers.IO) {
            try {
                val response = apiService.login(LoginRequest(email, password))

                if (response.isSuccessful) {
                    response.body()?.let {
                        return@withContext Resource.Success(it)
                    } ?: return@withContext Resource.Error("Empty response from server")
                } else {
                    val errorMsg = when (response.code()) {
                        401 -> "Email ou mot de passe invalide"
                        403 -> "Ce compte collaborateur est désactivé"
                        404 -> "Collaborateur non trouvé"
                        else -> "Erreur ${response.code()}: ${response.message()}"
                    }
                    return@withContext Resource.Error(errorMsg)
                }
            } catch (e: Exception) {
                return@withContext Resource.Error("Network error: ${e.message ?: "Unknown error"}")
            }
        }
    }
    
}