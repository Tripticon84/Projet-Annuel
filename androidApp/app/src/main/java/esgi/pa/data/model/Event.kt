package esgi.pa.data.model


data class Event(
    val evenement_id: Int,
    val nom: String,
    val date: String,  // Changed to String
    val lieu: String,
    val type: String,
    val statut: String,
    val id_association: Int
)