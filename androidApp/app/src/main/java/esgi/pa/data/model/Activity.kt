package esgi.pa.data.model

import com.google.gson.annotations.SerializedName

data class Activity(
    @SerializedName("id") // Map the server's "id" field to your "activity_id"
    val activity_id: Int,
    val nom: String,
    val type: String,
    val date: String,
    @SerializedName("id_devis") // Match the server response field
    val is_devis: Int,
    val id_prestataire: Int,
    val id_lieu: Int
)