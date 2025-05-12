package esgi.pa

import android.content.Intent
import android.os.Bundle
import android.util.Log
import android.view.Menu
import android.widget.TextView
import android.widget.Toast
import com.google.android.material.snackbar.Snackbar
import com.google.android.material.navigation.NavigationView
import androidx.navigation.findNavController
import androidx.navigation.ui.AppBarConfiguration
import androidx.navigation.ui.navigateUp
import androidx.navigation.ui.setupActionBarWithNavController
import androidx.navigation.ui.setupWithNavController
import androidx.drawerlayout.widget.DrawerLayout
import androidx.appcompat.app.AppCompatActivity
import esgi.pa.databinding.ActivityMainBinding
import esgi.pa.ui.login.LoginActivity
import esgi.pa.util.SessionManager

class MainActivity : AppCompatActivity() {

    private lateinit var appBarConfiguration: AppBarConfiguration
    private lateinit var binding: ActivityMainBinding
    private lateinit var sessionManager: SessionManager

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)

        sessionManager = SessionManager(this)
        val token = sessionManager.getToken()

        Log.d("MainActivity", "Token value: $token")

        // Check if user is logged in
        if (token == null) {
            Log.d("MainActivity", "No token found, redirecting to LoginActivity")
            val intent = Intent(this, LoginActivity::class.java)
            startActivity(intent)
            finish()
            return
        }

        Log.d("MainActivity", "Token found, continuing to MainActivity")

        binding = ActivityMainBinding.inflate(layoutInflater)
        setContentView(binding.root)

        setSupportActionBar(binding.appBarMain.toolbar)

        binding.appBarMain.fab.setOnClickListener { view ->
            Snackbar.make(view, "Replace with your own action", Snackbar.LENGTH_LONG)
                .setAction("Action", null)
                .setAnchorView(R.id.fab).show()
        }

        val drawerLayout: DrawerLayout = binding.drawerLayout
        val navView: NavigationView = binding.navView
        val navController = findNavController(R.id.nav_host_fragment_content_main)

        // Updated AppBarConfiguration to include the activities fragment
        appBarConfiguration = AppBarConfiguration(
            setOf(
                R.id.nav_home, R.id.nav_profile, R.id.navigation_planning, R.id.nav_activities, R.id.nav_events
            ), drawerLayout
        )

        setupActionBarWithNavController(navController, appBarConfiguration)
        navView.setupWithNavController(navController)

        try {
            // Display user information in navigation header
            val headerView = navView.getHeaderView(0)
            val usernameTextView = headerView.findViewById<TextView>(R.id.text_username)
            val userIdTextView = headerView.findViewById<TextView>(R.id.text_user_id)

            // Set user information from session
            val username = sessionManager.getUsername()
            val userId = sessionManager.getUserId()

            if (usernameTextView != null && userIdTextView != null) {
                if (username != null && userId != -1) {
                    usernameTextView.text = username
                    userIdTextView.text = "ID: $userId"

                    // Optional Toast message
                    Toast.makeText(
                        this,
                        "Connecté en tant que: $username (ID: $userId)",
                        Toast.LENGTH_SHORT
                    ).show()
                } else {
                    usernameTextView.text = "Utilisateur"
                    userIdTextView.text = "Données incomplètes"
                }
            } else {
                Log.e("MainActivity", "TextView elements not found in navigation header")
            }
        } catch (e: Exception) {
            Log.e("MainActivity", "Error setting up navigation header", e)
        }

        // Set up logout functionality
        navView.menu.findItem(R.id.nav_logout)?.setOnMenuItemClickListener {
            // Clear user session
            sessionManager.clearSession()

            // Navigate back to login screen
            val intent = Intent(this@MainActivity, LoginActivity::class.java)
            startActivity(intent)
            finish()

            true
        }
    }

    override fun onCreateOptionsMenu(menu: Menu): Boolean {
        menuInflater.inflate(R.menu.main, menu)
        return true
    }

    override fun onSupportNavigateUp(): Boolean {
        val navController = findNavController(R.id.nav_host_fragment_content_main)
        return navController.navigateUp(appBarConfiguration) || super.onSupportNavigateUp()
    }
}