package esgi.pa.data.model

import java.sql.Date

data class Event(
    val evenement_id: Int,
    val nom: String,
    val date: Date,
    val lieu: String,
    val type: String,
    val statut: String,
    val id_association: Int
)
