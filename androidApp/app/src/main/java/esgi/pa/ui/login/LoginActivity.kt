package esgi.pa.ui.login

import android.content.Intent
import android.os.Bundle
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import androidx.lifecycle.ViewModelProvider
import androidx.lifecycle.lifecycleScope
import esgi.pa.MainActivity
import esgi.pa.data.repository.AuthRepository
import esgi.pa.databinding.ActivityLoginBinding
import esgi.pa.util.Resource
import esgi.pa.util.SessionManager
import kotlinx.coroutines.launch

class LoginActivity : AppCompatActivity() {

    private lateinit var binding: ActivityLoginBinding
    private lateinit var viewModel: LoginViewModel
    private lateinit var sessionManager: SessionManager

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        binding = ActivityLoginBinding.inflate(layoutInflater)
        setContentView(binding.root)

        sessionManager = SessionManager(this)

        // Initialize ViewModel with factory
        val authRepository = AuthRepository()
        val viewModelFactory = LoginViewModelFactory(authRepository)
        viewModel = ViewModelProvider(this, viewModelFactory)[LoginViewModel::class.java]

        // Check if user is already logged in
        binding.loginButton.setOnClickListener {
            val username = binding.usernameEditText.text.toString().trim()
            val password = binding.passwordEditText.text.toString().trim()

            if (username.isNotEmpty() && password.isNotEmpty()) {
                // Store password for API calls
                sessionManager.savePassword(password)
                viewModel.login(username, password)
            } else {
                Toast.makeText(this, "Veuillez entrer votre nom d'utilisateur et mot de passe", Toast.LENGTH_SHORT).show()
            }
        }

        // Observe login state
        lifecycleScope.launch {
            viewModel.loginState.collect { state ->
                when (state) {
                    is Resource.Loading -> {
                        // Progress bar visibility code removed
                    }
                    is Resource.Success -> {
                        val response = state.data!!
                        sessionManager.saveSession(
                            token = response.token
                        )

                        // Navigate to main activity
                        startActivity(Intent(this@LoginActivity, MainActivity::class.java))
                        finish()
                    }
                    is Resource.Error -> {
                        Toast.makeText(this@LoginActivity, state.message, Toast.LENGTH_SHORT).show()
                    }
                }
            }
        }

        // Observe authentication state
        // Observe employee data state
        lifecycleScope.launch {
            viewModel.employeeState.collect { state ->
                when (state) {
                    is Resource.Success -> {
                        val employeeData = state.data!!
                        // Update session with user ID and username
                        sessionManager.saveSession(
                            token = sessionManager.getToken()!!,
                            username = employeeData.username,
                            userId = employeeData.collaborateur_id
                        )

                        // Show success message with user details
                        Toast.makeText(
                            this@LoginActivity,
                            "Connecté en tant que: ${employeeData.username} (ID: ${employeeData.collaborateur_id})",
                            Toast.LENGTH_LONG
                        ).show()
                    }
                    is Resource.Error -> {
                        Toast.makeText(
                            this@LoginActivity,
                            "Échec récupération données utilisateur: ${state.message}",
                            Toast.LENGTH_SHORT
                        ).show()
                    }
                    is Resource.Loading -> {
                        // Optional: Show loading state
                    }
                }
            }
        }
    }
}