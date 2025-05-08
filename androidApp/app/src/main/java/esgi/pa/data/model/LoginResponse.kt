package esgi.pa.data.model

data class LoginResponse(
    val token: String,
    val userId: Int = 0, // Default values for missing fields
    val username: String? = null, // Make nullable
    val email: String? = null,
    val role: String? = null,
    val nom: String? = null,
    val prenom: String? = null,
    val idSociete: Int = 0
)