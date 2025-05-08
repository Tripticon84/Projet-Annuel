package esgi.pa.util

import android.content.Context
import android.content.SharedPreferences

class SessionManager(context: Context) {

    private val prefs: SharedPreferences = context.getSharedPreferences(PREFS_NAME, Context.MODE_PRIVATE)

    companion object {
        private const val PREFS_NAME = "user_session"
        private const val KEY_TOKEN = "auth_token"
        private const val KEY_USERNAME = "username"
        private const val KEY_USER_ID = "collaborateur_id"
    }


    fun saveSession(token: String, username: String? = null, userId: Int = -1) {
        prefs.edit().apply {
            putString(KEY_TOKEN, token)
            username?.let { putString(KEY_USERNAME, it) }
            if (userId != -1) putInt(KEY_USER_ID, userId)
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