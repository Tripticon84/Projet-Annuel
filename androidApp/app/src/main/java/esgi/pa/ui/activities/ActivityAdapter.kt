package esgi.pa.ui.activities

import android.util.Log
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.Button
import android.widget.TextView
import androidx.core.content.ContextCompat
import androidx.recyclerview.widget.RecyclerView
import esgi.pa.R
import esgi.pa.data.model.Activity
import java.text.SimpleDateFormat
import java.util.Locale

class ActivityAdapter(
    private val activities: MutableList<Activity> = mutableListOf(),
    private val registeredActivityIds: MutableSet<Int> = mutableSetOf(),
    private val registeredActivityNames: MutableSet<String> = mutableSetOf(),
    private val onRegisterClick: (Activity, Boolean) -> Unit,
    private val showRegisterButton: Boolean = true
) : RecyclerView.Adapter<ActivityAdapter.ActivityViewHolder>() {
    private val TAG = "ActivityAdapter"

    // Mapping between API endpoint names
    private val nameMapping = mapOf(
        "sortie d'équipe" to "team building nature",
        "test" to "atelier test"
    )

    fun updateActivities(newActivities: List<Activity>) {
        Log.d(TAG, "Updating activities: ${newActivities.size} items")
        activities.clear()
        activities.addAll(newActivities)
        notifyDataSetChanged()
    }

    fun updateRegisteredActivities(registeredActivities: List<Activity>, registeredNames: Set<String> = emptySet()) {
        Log.d(TAG, "Updating registered activities: ${registeredActivities.size} items")

        // Clear current registration data
        registeredActivityIds.clear()
        registeredActivityNames.clear()

        // Add all IDs from registered activities
        registeredActivities.forEach { activity ->
            if (activity.activity_id > 0) {
                registeredActivityIds.add(activity.activity_id)
            }
        }

        // Add normalized names to the set - both from directly passed activities and from additional names
        val normalizedActivityNames = registeredActivities.map { it.nom.trim().lowercase() }
        registeredActivityNames.addAll(normalizedActivityNames)
        registeredActivityNames.addAll(registeredNames)

        Log.d(TAG, "Registered activity IDs: $registeredActivityIds")
        Log.d(TAG, "Registered activity names: $registeredActivityNames")

        notifyDataSetChanged()
    }

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): ActivityViewHolder {
        val view = LayoutInflater.from(parent.context)
            .inflate(R.layout.item_activity, parent, false)
        return ActivityViewHolder(view)
    }

    override fun onBindViewHolder(holder: ActivityViewHolder, position: Int) {
        val activity = activities[position]

        // Normalize the activity name for comparison
        val normalizedName = activity.nom.trim().lowercase()

        // Check if user is registered for this activity using multiple criteria
        // 1. Check by ID directly
        // 2. Check by normalized name directly
        // 3. Check using name mapping in both directions
        val isRegistered = registeredActivityIds.contains(activity.activity_id) ||
                registeredActivityNames.contains(normalizedName) ||
                nameMapping[normalizedName]?.let { registeredActivityNames.contains(it) } ?: false ||
                nameMapping.entries.any { (key, value) -> value == normalizedName && registeredActivityNames.contains(key) }

        Log.d(TAG, "Binding activity: ${activity.nom}, ID: ${activity.activity_id}, isRegistered: $isRegistered")

        holder.bind(activity, isRegistered)
    }

    override fun getItemCount() = activities.size

    inner class ActivityViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {
        private val nameTextView: TextView = itemView.findViewById(R.id.activityName)
        private val typeTextView: TextView = itemView.findViewById(R.id.activityType)
        private val dateTextView: TextView = itemView.findViewById(R.id.activityDate)
        private val registerButton: Button = itemView.findViewById(R.id.btn_activity_register)

        fun bind(activity: Activity, isRegistered: Boolean) {
            nameTextView.text = activity.nom
            typeTextView.text = activity.type

            // Format date for better readability
            try {
                val inputFormat = SimpleDateFormat("yyyy-MM-dd", Locale.getDefault())
                val outputFormat = SimpleDateFormat("dd/MM/yyyy", Locale.getDefault())

                val date = inputFormat.parse(activity.date)
                dateTextView.text = if (date != null) outputFormat.format(date) else activity.date
            } catch (e: Exception) {
                // Fall back to raw date if parsing fails
                dateTextView.text = activity.date
            }

            // Configure button based on registration status
            if (!showRegisterButton) {
                registerButton.visibility = View.GONE
            } else {
                registerButton.visibility = View.VISIBLE

                if (isRegistered) {
                    registerButton.text = "Se désinscrire"
                    registerButton.setBackgroundColor(
                        ContextCompat.getColor(itemView.context, R.color.purple_500)
                    )
                } else {
                    registerButton.text = "S'inscrire"
                    registerButton.setBackgroundColor(
                        ContextCompat.getColor(itemView.context, R.color.teal_200)
                    )
                }

                registerButton.setOnClickListener {
                    onRegisterClick(activity, isRegistered)
                }
            }
        }
    }
}