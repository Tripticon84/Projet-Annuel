// ActivityAdapter.kt in esgi.pa.ui.adapters package
package esgi.pa.ui.adapters

import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.Button
import android.widget.TextView
import androidx.core.content.ContextCompat
import androidx.recyclerview.widget.RecyclerView
import esgi.pa.R
import esgi.pa.data.model.Activity

class ActivityAdapter(
    private val activities: MutableList<Activity> = mutableListOf(),
    private val registeredActivityIds: MutableSet<Int> = mutableSetOf(),
    private val onRegisterClick: (Activity, Boolean) -> Unit,
    private val showRegisterButton: Boolean = true // New parameter with default value
) : RecyclerView.Adapter<ActivityAdapter.ActivityViewHolder>() {

    // Existing functions remain unchanged
    fun updateActivities(newActivities: List<Activity>) {
        activities.clear()
        activities.addAll(newActivities)
        notifyDataSetChanged()
    }

    fun updateRegisteredActivities(registeredActivities: List<Activity>) {
        registeredActivityIds.clear()
        registeredActivityIds.addAll(registeredActivities.map { it.activity_id })
        notifyDataSetChanged()
    }

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): ActivityViewHolder {
        val view = LayoutInflater.from(parent.context)
            .inflate(R.layout.item_activity, parent, false)
        return ActivityViewHolder(view)
    }

    override fun onBindViewHolder(holder: ActivityViewHolder, position: Int) {
        val activity = activities[position]
        holder.bind(activity, registeredActivityIds.contains(activity.activity_id))
    }

    override fun getItemCount() = activities.size

    inner class ActivityViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {
        private val tvActivityName: TextView = itemView.findViewById(R.id.activityName)
        private val tvActivityDate: TextView = itemView.findViewById(R.id.activityDate)
        private val tvActivityType: TextView = itemView.findViewById(R.id.activityType)
        private val btnRegister: Button = itemView.findViewById(R.id.btn_activity_register)

        fun bind(activity: Activity, isRegistered: Boolean) {
            tvActivityName.text = activity.nom
            tvActivityDate.text = "Date: ${activity.date}"
            tvActivityType.text = "Type: ${activity.type}"

            // Show or hide register button based on showRegisterButton parameter
            btnRegister.visibility = if (showRegisterButton) View.VISIBLE else View.GONE

            if (showRegisterButton) {
                if (isRegistered) {
                    btnRegister.text = "Se désinscrire"
                    btnRegister.setBackgroundColor(ContextCompat.getColor(itemView.context, android.R.color.holo_red_light))
                } else {
                    btnRegister.text = "S'inscrire"
                    btnRegister.setBackgroundColor(ContextCompat.getColor(itemView.context, android.R.color.holo_green_light))
                }

                btnRegister.setOnClickListener {
                    onRegisterClick(activity, isRegistered)
                }
            }
        }
    }
}