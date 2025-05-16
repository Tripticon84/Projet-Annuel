// HomeEventAdapter.kt
package esgi.pa.ui.home

import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.Button
import android.widget.TextView
import androidx.recyclerview.widget.RecyclerView
import esgi.pa.R
import esgi.pa.data.model.Event

class HomeEventAdapter : RecyclerView.Adapter<HomeEventAdapter.EventViewHolder>() {
    private val events = mutableListOf<Event>()

    fun updateEvents(newEvents: List<Event>) {
        events.clear()
        events.addAll(newEvents)
        notifyDataSetChanged()
    }

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): EventViewHolder {
        val view = LayoutInflater.from(parent.context)
            .inflate(R.layout.item_event, parent, false)
        return EventViewHolder(view)
    }

    override fun onBindViewHolder(holder: EventViewHolder, position: Int) {
        holder.bind(events[position])
    }

    override fun getItemCount() = events.size

    inner class EventViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {
        private val tvEventName: TextView = itemView.findViewById(R.id.event_name)
        private val tvEventDate: TextView = itemView.findViewById(R.id.event_date)
        private val tvEventLocation: TextView = itemView.findViewById(R.id.event_location)
        private val tvEventType: TextView = itemView.findViewById(R.id.event_type)
        private val btnRegister: Button = itemView.findViewById(R.id.btn_event_register)

        fun bind(event: Event) {
            tvEventName.text = event.nom
            tvEventDate.text = "Date: ${event.date}"
            tvEventLocation.text = "Lieu: ${event.lieu}"
            tvEventType.text = "Type: ${event.type}"

            // Hide registration button
            btnRegister.visibility = View.GONE
        }
    }
}