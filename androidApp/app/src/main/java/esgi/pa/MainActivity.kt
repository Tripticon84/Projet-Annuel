package esgi.pa

import android.content.Intent
import android.nfc.NfcAdapter
import android.os.Bundle
import android.util.Log
import android.view.Menu
import android.widget.TextView
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import androidx.drawerlayout.widget.DrawerLayout
import androidx.navigation.findNavController
import androidx.navigation.fragment.NavHostFragment
import androidx.navigation.ui.AppBarConfiguration
import androidx.navigation.ui.navigateUp
import androidx.navigation.ui.setupActionBarWithNavController
import androidx.navigation.ui.setupWithNavController
import com.google.android.material.navigation.NavigationView
import com.google.android.material.snackbar.Snackbar
import esgi.pa.data.repository.AuthRepository
import esgi.pa.databinding.ActivityMainBinding
import esgi.pa.ui.login.LoginActivity
import esgi.pa.ui.nfc.NfcWriterFragment
import esgi.pa.util.SessionManager

class MainActivity : AppCompatActivity() {
    private val TAG = "MainActivity"
    private lateinit var appBarConfiguration: AppBarConfiguration
    private lateinit var binding: ActivityMainBinding
    private lateinit var sessionManager: SessionManager
    private val authRepository = AuthRepository()
    private var nfcAdapter: NfcAdapter? = null

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)

        sessionManager = SessionManager(this)
        val token = sessionManager.getToken()
        val userId = sessionManager.getUserId()

        Log.d(TAG, "Token value: $token")
        Log.d(TAG, "User ID value: $userId")

        // Check if user is logged in
        if (token == null) {
            Log.d(TAG, "No token found, redirecting to LoginActivity")
            val intent = Intent(this, LoginActivity::class.java)
            startActivity(intent)
            finish()
            return
        }

        Log.d(TAG, "Token found, continuing to MainActivity")

        binding = ActivityMainBinding.inflate(layoutInflater)
        setContentView(binding.root)

        // Initialize NFC adapter
        nfcAdapter = NfcAdapter.getDefaultAdapter(this)

        // Continue with the rest of MainActivity setup...
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
                R.id.nav_home, R.id.nav_profile, R.id.navigation_planning,
                R.id.nav_activities, R.id.nav_events, R.id.navigation_nfc_writer
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
            val currentUserId = sessionManager.getUserId()

            if (usernameTextView != null && userIdTextView != null) {
                if (username != null && currentUserId > 0) {
                    usernameTextView.text = username
                    userIdTextView.text = "ID: $currentUserId"

                    // Optional Toast message
                    Toast.makeText(
                        this,
                        "Connecté en tant que: $username (ID: $currentUserId)",
                        Toast.LENGTH_SHORT
                    ).show()
                } else {
                    usernameTextView.text = "Utilisateur"
                    userIdTextView.text = "Données incomplètes"
                }
            } else {
                Log.e(TAG, "TextView elements not found in navigation header")
            }
        } catch (e: Exception) {
            Log.e(TAG, "Error setting up navigation header", e)
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

    override fun onNewIntent(intent: Intent) {
        super.onNewIntent(intent)

        // Vérifier si c'est un intent NFC
        if (NfcAdapter.ACTION_TAG_DISCOVERED == intent.action ||
            NfcAdapter.ACTION_TECH_DISCOVERED == intent.action ||
            NfcAdapter.ACTION_NDEF_DISCOVERED == intent.action) {

            // Trouver le fragment NFC s'il est actif
            val navHostFragment = supportFragmentManager.findFragmentById(R.id.nav_host_fragment_content_main) as NavHostFragment
            val currentFragment = navHostFragment.childFragmentManager.primaryNavigationFragment

            if (currentFragment is NfcWriterFragment) {
                // Transmettre l'intent au fragment
                currentFragment.writeToTag(intent)
            }
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