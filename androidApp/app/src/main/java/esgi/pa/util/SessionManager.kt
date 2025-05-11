package esgi.pa.util

import android.content.Context
import android.content.SharedPreferences
import android.util.Log

class SessionManager(context: Context) {

    private val prefs: SharedPreferences = context.getSharedPreferences(PREFS_NAME, Context.MODE_PRIVATE)

    companion object {
        private const val PREFS_NAME = "user_session"
        private const val KEY_TOKEN = "auth_token"
        private const val KEY_USERNAME = "username"
        private const val KEY_COLLABORATEUR_ID = "collaborateur_id"
        private const val KEY_PASSWORD = "password" // New key for password
    }

    fun saveSession(token: String, username: String? = null, userId: Int = -1, password: String? = null) {
        prefs.edit().apply {
            putString(KEY_TOKEN, token)
            username?.let { putString(KEY_USERNAME, it) }
            if (userId != -1) putInt(KEY_COLLABORATEUR_ID, userId)
            password?.let { putString(KEY_PASSWORD, it) }
            apply()
        }
    }

    // Save or update password separately
    fun savePassword(password: String) {
        prefs.edit().putString(KEY_PASSWORD, password).apply()
    }

    fun getToken(): String? = prefs.getString(KEY_TOKEN, null)

    fun getUsername(): String? = prefs.getString(KEY_USERNAME, null)

    fun getUserId(): Int = prefs.getInt(KEY_COLLABORATEUR_ID, -1)

    fun getCollaborateurId(): Int {
        val id = prefs.getInt(KEY_COLLABORATEUR_ID, -1)
        Log.d("SessionManager", "Retrieved collaborateur_id: $id")
        return id
    }

    fun saveCollaborateurId(id: Int) {
        Log.d("SessionManager", "Saving collaborateur_id: $id")
        prefs.edit().putInt(KEY_COLLABORATEUR_ID, id).apply()
    }

    fun getPassword(): String? = prefs.getString(KEY_PASSWORD, null)

    fun clearSession() {
        prefs.edit().clear().apply()
    }
}