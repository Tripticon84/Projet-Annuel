package esgi.pa.data.model

data class RegisterToActivityRequest(
    val collaborateur_id: Int,
    val id_activite: Int,
    val type: String,
)