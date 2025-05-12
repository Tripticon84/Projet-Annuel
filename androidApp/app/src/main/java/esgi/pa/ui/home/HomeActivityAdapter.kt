// HomeActivityAdapter.kt
package esgi.pa.ui.home

import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.Button
import android.widget.TextView
import androidx.recyclerview.widget.RecyclerView
import esgi.pa.R
import esgi.pa.data.model.Activity

class HomeActivityAdapter : RecyclerView.Adapter<HomeActivityAdapter.ActivityViewHolder>() {
    private val activities = mutableListOf<Activity>()

    fun updateActivities(newActivities: List<Activity>) {
        activities.clear()
        activities.addAll(newActivities)
        notifyDataSetChanged()
    }

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): ActivityViewHolder {
        val view = LayoutInflater.from(parent.context)
            .inflate(R.layout.item_activity, parent, false)
        return ActivityViewHolder(view)
    }

    override fun onBindViewHolder(holder: ActivityViewHolder, position: Int) {
        holder.bind(activities[position])
    }

    override fun getItemCount() = activities.size

    inner class ActivityViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {
        private val tvActivityName: TextView = itemView.findViewById(R.id.activityName)
        private val tvActivityDate: TextView = itemView.findViewById(R.id.activityDate)
        private val tvActivityType: TextView = itemView.findViewById(R.id.activityType)
        private val btnRegister: Button = itemView.findViewById(R.id.btn_activity_register)

        fun bind(activity: Activity) {
            tvActivityName.text = activity.nom
            tvActivityDate.text = "Date: ${activity.date}"
            tvActivityType.text = "Type: ${activity.type}"

            // Hide registration button
            btnRegister.visibility = View.GONE
        }
    }
}