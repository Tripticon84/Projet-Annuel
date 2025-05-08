package esgi.pa

import android.os.Bundle
import android.util.Log
import android.view.Menu
import android.view.MenuItem
import android.widget.TextView
import androidx.appcompat.app.AppCompatActivity
import androidx.core.view.GravityCompat
import androidx.drawerlayout.widget.DrawerLayout
import androidx.lifecycle.lifecycleScope
import androidx.navigation.NavController
import androidx.navigation.findNavController
import androidx.navigation.ui.AppBarConfiguration
import androidx.navigation.ui.navigateUp
import androidx.navigation.ui.setupActionBarWithNavController
import androidx.navigation.ui.setupWithNavController
import com.google.android.material.navigation.NavigationView
import esgi.pa.data.SessionManager
import esgi.pa.databinding.ActivityMainBinding
import kotlinx.coroutines.flow.first
import kotlinx.coroutines.launch

class MainActivity : AppCompatActivity() {
    private val TAG = "MainActivity"
    private lateinit var binding: ActivityMainBinding
    private lateinit var appBarConfiguration: AppBarConfiguration
    private lateinit var navController: NavController
    private lateinit var sessionManager: SessionManager
    private lateinit var drawerLayout: DrawerLayout

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        Log.d(TAG, "onCreate started")

        binding = ActivityMainBinding.inflate(layoutInflater)
        setContentView(binding.root)

        // Initialize the session manager
        sessionManager = SessionManager(applicationContext)

        // Set up the toolbar
        setSupportActionBar(binding.appBarMain.toolbar)

        // Set up the drawer layout
        drawerLayout = binding.drawerLayout
        val navView: NavigationView = binding.navView

        // Get the navigation controller
        navController = findNavController(R.id.nav_host_fragment_content_main)

        // Configure which top level destinations should show the drawer menu icon
        appBarConfiguration = AppBarConfiguration(
            setOf(
                R.id.nav_home, R.id.nav_gallery, R.id.nav_slideshow
            ), drawerLayout
        )

        // Connect the nav controller with ActionBar
        setupActionBarWithNavController(navController, appBarConfiguration)

        // Connect the navigation view with the nav controller
        navView.setupWithNavController(navController)

        // Load user data into navigation header
        loadUserDataIntoNavHeader()
    }

    private fun loadUserDataIntoNavHeader() {
        val headerView = binding.navView.getHeaderView(0)
        val tvName = headerView.findViewById<TextView>(R.id.nav_header_name)
        val tvEmail = headerView.findViewById<TextView>(R.id.nav_header_email)

        lifecycleScope.launch {
            try {
                // Get user data from SessionManager
                val userName = sessionManager.userNameFlow.first() ?: "Unknown"
                val userIdFlow = sessionManager.userIdFlow.first()

                // Update the navigation header
                tvName.text = userName
                tvEmail.text = userIdFlow

                Log.d(TAG, "Navigation header updated with user data")
            } catch (e: Exception) {
                Log.e(TAG, "Error loading user data: ${e.message}")
            }
        }
    }

    override fun onCreateOptionsMenu(menu: Menu): Boolean {
        menuInflater.inflate(R.menu.main, menu)
        return true
    }

    override fun onOptionsItemSelected(item: MenuItem): Boolean {
        return when (item.itemId) {
            R.id.action_logout -> {
                performLogout()
                true
            }
            else -> super.onOptionsItemSelected(item)
        }
    }

    private fun performLogout() {
        lifecycleScope.launch {
            try {
                // Clear the session
                sessionManager.clearSession()

                // Navigate back to login screen
                finish()
            } catch (e: Exception) {
                Log.e(TAG, "Error during logout: ${e.message}")
            }
        }
    }

    override fun onSupportNavigateUp(): Boolean {
        return navController.navigateUp(appBarConfiguration) || super.onSupportNavigateUp()
    }

    override fun onBackPressed() {
        if (drawerLayout.isDrawerOpen(GravityCompat.START)) {
            drawerLayout.closeDrawer(GravityCompat.START)
        } else {
            super.onBackPressed()
        }
    }
}