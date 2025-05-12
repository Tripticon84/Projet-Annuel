package esgi.pa.data.model

data class RegisterToEventRequest (
    val collaborateur_id: Int,
    val id_evenement: Int,
    val type: String,
)