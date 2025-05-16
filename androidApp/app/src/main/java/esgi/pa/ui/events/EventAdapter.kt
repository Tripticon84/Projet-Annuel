// EventAdapter.kt in esgi.pa.ui.events package
package esgi.pa.ui.events

import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.Button
import android.widget.TextView
import androidx.core.content.ContextCompat
import androidx.recyclerview.widget.RecyclerView
import esgi.pa.R
import esgi.pa.data.model.Event

class EventAdapter(
    private val events: MutableList<Event> = mutableListOf(),
    private val registeredEventIds: MutableSet<Int> = mutableSetOf(),
    private val onRegisterClick: (Event, Boolean) -> Unit,
    private val showRegisterButton: Boolean = true // New parameter with default value
) : RecyclerView.Adapter<EventAdapter.EventViewHolder>() {

    // Existing functions remain unchanged
    fun updateEvents(newEvents: List<Event>) {
        events.clear()
        events.addAll(newEvents)
        notifyDataSetChanged()
    }

    fun updateRegisteredEvents(registeredEvents: List<Event>) {
        registeredEventIds.clear()
        registeredEventIds.addAll(registeredEvents.map { it.evenement_id })
        notifyDataSetChanged()
    }

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): EventViewHolder {
        val view = LayoutInflater.from(parent.context)
            .inflate(R.layout.item_event, parent, false)
        return EventViewHolder(view)
    }

    override fun onBindViewHolder(holder: EventViewHolder, position: Int) {
        val event = events[position]
        holder.bind(event, registeredEventIds.contains(event.evenement_id))
    }

    override fun getItemCount() = events.size

    inner class EventViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {
        private val tvEventName: TextView = itemView.findViewById(R.id.event_name)
        private val tvEventDate: TextView = itemView.findViewById(R.id.event_date)
        private val tvEventLocation: TextView = itemView.findViewById(R.id.event_location)
        private val tvEventType: TextView = itemView.findViewById(R.id.event_type)
        private val btnRegister: Button = itemView.findViewById(R.id.btn_event_register)

        fun bind(event: Event, isRegistered: Boolean) {
            tvEventName.text = event.nom
            tvEventDate.text = "Date: ${event.date}"
            tvEventLocation.text = "Lieu: ${event.lieu}"
            tvEventType.text = "Type: ${event.type}"

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
                    onRegisterClick(event, isRegistered)
                }
            }
        }
    }
}