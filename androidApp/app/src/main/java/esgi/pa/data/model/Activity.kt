package esgi.pa.data.model

data class Activity(
    val activity_id: Int,
    val nom: String,
    val type: String,
    val date: String,
    val is_devis: Int,
    val id_prestataire: Int,
    val id_lieu: Int
)