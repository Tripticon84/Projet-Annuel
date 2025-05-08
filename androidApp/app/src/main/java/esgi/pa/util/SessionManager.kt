package esgi.pa.util

import android.content.Context
import android.content.SharedPreferences

class SessionManager(context: Context) {

    private val prefs: SharedPreferences = context.getSharedPreferences(PREFS_NAME, Context.MODE_PRIVATE)

    companion object {
        private const val PREFS_NAME = "user_session"
        private const val KEY_TOKEN = "auth_token"
        private const val KEY_USERNAME = "username"
        private const val KEY_USER_ID = "user_id"
    }

    // In SessionManager.kt
    fun saveSession(token: String, username: String?, userId: Int) {
        prefs.edit().apply {
            putString(KEY_TOKEN, token)
            putString(KEY_USERNAME, username)
            putInt(KEY_USER_ID, userId)
            apply()
        }
    }

    fun getToken(): String? = prefs.getString(KEY_TOKEN, null)

    fun getUsername(): String? = prefs.getString(KEY_USERNAME, null)

    fun getUserId(): Int = prefs.getInt(KEY_USER_ID, -1)

    fun clearSession() {
        prefs.edit().clear().apply()
    }
}