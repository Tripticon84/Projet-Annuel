package esgi.pa.ui.home

import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.TextView
import androidx.recyclerview.widget.DiffUtil
import androidx.recyclerview.widget.ListAdapter
import androidx.recyclerview.widget.RecyclerView
import esgi.pa.R
import esgi.pa.data.model.Event
import java.text.SimpleDateFormat
import java.util.Locale

class EventAdapter : ListAdapter<Event, EventAdapter.EventViewHolder>(EventDiffCallback()) {

    class EventViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {
        val nameTv: TextView = itemView.findViewById(R.id.event_name)
        val dateTv: TextView = itemView.findViewById(R.id.event_date)
        val locationTv: TextView = itemView.findViewById(R.id.event_location)
        val typeTv: TextView = itemView.findViewById(R.id.event_type)
    }

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): EventViewHolder {
        val view = LayoutInflater.from(parent.context)
            .inflate(R.layout.item_event, parent, false)
        return EventViewHolder(view)
    }

    override fun onBindViewHolder(holder: EventViewHolder, position: Int) {
        val event = getItem(position)
        holder.nameTv.text = event.nom

        // Format date properly
        try {
            val dateFormat = SimpleDateFormat("yyyy-MM-dd", Locale.getDefault())
            val outputFormat = SimpleDateFormat("dd/MM/yyyy", Locale.getDefault())
            val date = event.date
            val formattedDate = outputFormat.format(dateFormat.parse(date))
            holder.dateTv.text = formattedDate
        } catch (e: Exception) {
            holder.dateTv.text = event.date
        }

        holder.locationTv.text = event.lieu
        holder.typeTv.text = event.type
    }

    class EventDiffCallback : DiffUtil.ItemCallback<Event>() {
        override fun areItemsTheSame(oldItem: Event, newItem: Event): Boolean {
            return oldItem.evenement_id == newItem.evenement_id
        }

        override fun areContentsTheSame(oldItem: Event, newItem: Event): Boolean {
            return oldItem == newItem
        }
    }
}