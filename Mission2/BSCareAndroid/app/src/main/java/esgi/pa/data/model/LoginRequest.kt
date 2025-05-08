package esgi.pa.data.model

data class LoginRequest(
    val username: String, // Changed from email
    val password: String
)