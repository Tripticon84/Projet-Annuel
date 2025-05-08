package esgi.pa.data

import android.content.Context
import androidx.datastore.core.DataStore
import androidx.datastore.preferences.core.Preferences
import androidx.datastore.preferences.core.edit
import androidx.datastore.preferences.core.stringPreferencesKey
import androidx.datastore.preferences.preferencesDataStore
import kotlinx.coroutines.flow.Flow
import kotlinx.coroutines.flow.map

// Extension property to create the DataStore
private val Context.dataStore: DataStore<Preferences> by preferencesDataStore(name = "session_prefs")

class SessionManager(private val context: Context) {

    companion object {
        private val TOKEN_KEY = stringPreferencesKey("COLLABORATEUR_TOKEN_KEY")
        private val USER_ID_KEY = stringPreferencesKey("COLLABORATEUR_ID_KEY")
        private val USER_NAME_KEY = stringPreferencesKey("COLLABORATEUR_NAME_KEY")
        private val USER_EMAIL_KEY = stringPreferencesKey("COLLABORATEUR_EMAIL_KEY")
        private val USER_ROLE_KEY = stringPreferencesKey("COLLABORATEUR_ROLE_KEY")
        private val USER_NOM_KEY = stringPreferencesKey("COLLABORATEUR_NOM_KEY")
        private val USER_PRENOM_KEY = stringPreferencesKey("COLLABORATEUR_PRENOM_KEY")
        private val USER_SOCIETE_ID_KEY = stringPreferencesKey("COLLABORATEUR_SOCIETE_ID_KEY")
    }

    // Store auth token
    suspend fun saveAuthToken(token: String) {
        context.dataStore.edit { preferences ->
            preferences[TOKEN_KEY] = token
        }
    }

    // Get the auth token
    val tokenFlow: Flow<String?> = context.dataStore.data
        .map { preferences ->
            preferences[TOKEN_KEY]
        }

    // Check if user is logged in
    val isLoggedIn: Flow<Boolean> = tokenFlow.map { it != null }

    // Get user ID
    val userIdFlow: Flow<String?> = context.dataStore.data
        .map { preferences ->
            preferences[USER_ID_KEY]
        }

    // Get user name
    val userNameFlow: Flow<String?> = context.dataStore.data
        .map { preferences ->
            preferences[USER_NAME_KEY]
        }

    // Save collaborateur info
    suspend fun saveCollaborateurInfo(
        userId: String,
        userName: String,
        email: String,
        role: String,
        nom: String,
        prenom: String,
        societeId: String
    ) {
        context.dataStore.edit { preferences ->
            preferences[USER_ID_KEY] = userId
            preferences[USER_NAME_KEY] = userName
            preferences[USER_EMAIL_KEY] = email
            preferences[USER_ROLE_KEY] = role
            preferences[USER_NOM_KEY] = nom
            preferences[USER_PRENOM_KEY] = prenom
            preferences[USER_SOCIETE_ID_KEY] = societeId
        }
    }

    // Clear all data on logout
    suspend fun clearSession() {
        context.dataStore.edit { it.clear() }
    }
}