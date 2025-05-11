package esgi.pa.ui.profile

import android.os.Bundle
import android.util.Log
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.TextView
import android.widget.Toast
import androidx.fragment.app.Fragment
import androidx.lifecycle.ViewModelProvider
import esgi.pa.R
import esgi.pa.data.model.GetOneByCredentialsResponse

class ProfileFragment : Fragment() {

    private lateinit var profileViewModel: ProfileViewModel
    private lateinit var tvUsername: TextView
    private lateinit var tvUserId: TextView

    override fun onCreateView(
        inflater: LayoutInflater,
        container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View? {
        val root = inflater.inflate(R.layout.fragment_profile, container, false)

        // Initialize views that match the layout
        tvUsername = root.findViewById(R.id.tv_username)
        tvUserId = root.findViewById(R.id.tv_user_id)

        return root
    }

    override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        super.onViewCreated(view, savedInstanceState)

        profileViewModel = ViewModelProvider(this).get(ProfileViewModel::class.java)

        // Add log before loading data
        Log.d("ProfileFragment", "Starting to load user data")

        // Observe ViewModel data
        profileViewModel.userData.observe(viewLifecycleOwner) { userData ->
            Log.d("ProfileFragment", "Received user data: $userData")
            updateUI(userData)
        }

        profileViewModel.error.observe(viewLifecycleOwner) { errorMsg ->
            Log.e("ProfileFragment", "Error loading user data: $errorMsg")
            showError(errorMsg)
        }

        // Load user data
        profileViewModel.loadUserData()
    }

    private fun updateUI(userData: GetOneByCredentialsResponse) {
        Log.d("ProfileFragment", "Updating UI with user: ${userData.prenom} ${userData.nom}")
        tvUsername.text = "${userData.prenom} ${userData.nom}"
        tvUserId.text = "ID: ${userData.collaborateur_id} | ${userData.role}"
    }

    private fun showError(message: String) {
        Toast.makeText(context, message, Toast.LENGTH_LONG).show()
    }
}