package esgi.pa.data.model

data class GetOneByCredentialsResponse(
    val collaborateur_id: Int,
    val nom: String,
    val prenom: String,
    val username: String,
    val role: String,
    val email: String,
    val id_societe: Int,
    val date_creation: String,
    val date_activite: String,
    val desactivated: Boolean
)
