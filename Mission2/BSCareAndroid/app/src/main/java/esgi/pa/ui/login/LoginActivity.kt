package esgi.pa.ui.login

import android.content.Intent
import android.os.Bundle
import android.util.Log
import android.view.View
import android.widget.Toast
import androidx.activity.viewModels
import androidx.appcompat.app.AppCompatActivity
import androidx.lifecycle.lifecycleScope
import esgi.pa.MainActivity
import esgi.pa.data.SessionManager
import esgi.pa.databinding.ActivityLoginBinding
import esgi.pa.util.Resource
import kotlinx.coroutines.flow.first
import kotlinx.coroutines.launch

class LoginActivity : AppCompatActivity() {
    private val TAG = "LoginActivity"
    private lateinit var binding: ActivityLoginBinding
    private val viewModel: LoginViewModel by viewModels()
    private lateinit var sessionManager: SessionManager

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        Log.d(TAG, "onCreate started")

        binding = ActivityLoginBinding.inflate(layoutInflater)
        setContentView(binding.root)

        sessionManager = SessionManager(applicationContext)

        // Check if already logged in
        lifecycleScope.launch {
            try {
                if (sessionManager.isLoggedIn.first()) {
                    Log.d(TAG, "User already logged in, navigating to MainActivity")
                    navigateToMainActivity()
                    return@launch
                }
            } catch (e: Exception) {
                Log.e(TAG, "Error checking login status: ${e.message}", e)
            }
        }

        setupViews()
        observeViewModel()
    }

    private fun setupViews() {
        binding.btnLogin.setOnClickListener {
            val username = binding.etUsername.text.toString().trim()
            val password = binding.etPassword.text.toString().trim()

            if (username.isEmpty()) {
                binding.etUsername.error = "Nom d'utilisateur requis"
                return@setOnClickListener
            }

            if (password.isEmpty()) {
                binding.etPassword.error = "Mot de passe requis"
                return@setOnClickListener
            }

            viewModel.login(username, password)
        }
    }

    private fun observeViewModel() {
        viewModel.loginResult.observe(this) { result ->
            when (result) {
                is Resource.Loading -> {
                    showLoading(true)
                }

                is Resource.Success -> {
                    showLoading(false)
                    result.data?.let { loginResponse ->
                        lifecycleScope.launch {
                            try {
                                // Save auth token and user info
                                Log.d(TAG, "Login successful, saving session data")
                                sessionManager.saveAuthToken(loginResponse.token)
                                sessionManager.saveCollaborateurInfo(
                                    userId = loginResponse.userId.toString(),
                                    userName = loginResponse.userName,
                                    email = loginResponse.email,
                                    role = loginResponse.role,
                                    nom = loginResponse.nom,
                                    prenom = loginResponse.prenom,
                                    societeId = loginResponse.idSociete.toString()
                                )

                                navigateToMainActivity()
                            } catch (e: Exception) {
                                Log.e(TAG, "Error saving session: ${e.message}", e)
                                Toast.makeText(
                                    this@LoginActivity,
                                    "Erreur lors de la sauvegarde des données: ${e.message}",
                                    Toast.LENGTH_LONG
                                ).show()
                            }
                        }
                    }
                }

                is Resource.Error -> {
                    Log.e(TAG, "Login error: ${result.message}")
                    showLoading(false)
                    Toast.makeText(
                        this,
                        result.message ?: "Erreur inconnue",
                        Toast.LENGTH_LONG
                    ).show()
                }
            }
        }
    }

    private fun navigateToMainActivity() {
        try {
            Log.d(TAG, "Attempting to navigate to MainActivity")

            val intent = Intent(this@LoginActivity, MainActivity::class.java)
            intent.flags = Intent.FLAG_ACTIVITY_NEW_TASK or Intent.FLAG_ACTIVITY_CLEAR_TASK

            startActivity(intent)
            finish()
        } catch (e: Exception) {
            Log.e(TAG, "ERROR navigating to MainActivity: ${e.message}", e)
            e.printStackTrace()

            Toast.makeText(
                this,
                "Impossible d'ouvrir l'écran principal: ${e.message}",
                Toast.LENGTH_LONG
            ).show()

            // Clear session on failure
            lifecycleScope.launch {
                sessionManager.clearSession()
            }
        }
    }

    private fun showLoading(isLoading: Boolean) {
        binding.progressBar.visibility = if (isLoading) View.VISIBLE else View.GONE
        binding.btnLogin.isEnabled = !isLoading
        binding.etUsername.isEnabled = !isLoading
        binding.etPassword.isEnabled = !isLoading
    }
}