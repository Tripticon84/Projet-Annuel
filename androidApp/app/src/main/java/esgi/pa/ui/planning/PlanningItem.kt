package esgi.pa.ui.planning

import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.ImageView
import android.widget.TextView
import androidx.recyclerview.widget.RecyclerView
import esgi.pa.R
import java.text.SimpleDateFormat
import java.util.Locale

data class PlanningItem(
    val id: Int,
    val name: String,
    val type: String,
    val date: String,
    val location: String? = null,
    val isEvent: Boolean = false
)

class PlanningItemAdapter : RecyclerView.Adapter<PlanningItemAdapter.PlanningViewHolder>() {
    private val items: MutableList<PlanningItem> = mutableListOf()

    fun updateItems(newItems: List<PlanningItem>) {
        items.clear()
        items.addAll(newItems)
        notifyDataSetChanged()
    }

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): PlanningViewHolder {
        val view = LayoutInflater.from(parent.context)
            .inflate(R.layout.item_planning, parent, false)
        return PlanningViewHolder(view)
    }

    override fun onBindViewHolder(holder: PlanningViewHolder, position: Int) {
        holder.bind(items[position])
    }

    override fun getItemCount() = items.size

    class PlanningViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {
        private val nameTextView: TextView = itemView.findViewById(R.id.tvEventName)
        private val typeTextView: TextView = itemView.findViewById(R.id.tvEventType)
        private val locationTextView: TextView = itemView.findViewById(R.id.tvEventLocation)
        private val iconView: ImageView = itemView.findViewById(R.id.ivEventIcon)

        fun bind(item: PlanningItem) {
            nameTextView.text = item.name
            typeTextView.text = item.type

            if (item.location.isNullOrEmpty()) {
                locationTextView.visibility = View.GONE
            } else {
                locationTextView.visibility = View.VISIBLE
                locationTextView.text = "Lieu: ${item.location}"
            }

            // Set icon based on whether it's an event or activity
            iconView.setImageResource(
                if (item.isEvent) R.drawable.ic_events else R.drawable.ic_activities
            )
        }
    }
}