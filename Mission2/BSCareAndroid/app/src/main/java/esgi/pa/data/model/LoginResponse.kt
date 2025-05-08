package esgi.pa.data.model

data class LoginResponse(
    val userId: Int,
    val userName: String,
    val email: String,
    val token: String,
    val role: String,
    // Ajouter d'autres propriétés spécifiques aux collaborateurs si nécessaire
    val nom: String,
    val prenom: String,
    val idSociete: Int
)
