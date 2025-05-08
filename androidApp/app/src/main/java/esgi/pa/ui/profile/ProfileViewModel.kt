package esgi.pa.ui.profile

import android.app.Application
import android.content.Context
import androidx.lifecycle.AndroidViewModel
import androidx.lifecycle.LiveData
import androidx.lifecycle.MutableLiveData
import androidx.lifecycle.viewModelScope
import esgi.pa.data.model.GetOneByCredentialsResponse
import esgi.pa.data.repository.AuthRepository
import esgi.pa.util.Resource
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.launch

class ProfileViewModel(application: Application) : AndroidViewModel(application) {

    private val repository = AuthRepository()

    private val _userData = MutableLiveData<GetOneByCredentialsResponse>()
    val userData: LiveData<GetOneByCredentialsResponse> = _userData

    private val _error = MutableLiveData<String>()
    val error: LiveData<String> = _error

    private val _isLoading = MutableLiveData<Boolean>()
    val isLoading: LiveData<Boolean> = _isLoading

    fun loadUserData() {
        _isLoading.value = true

        // Get stored credentials from SharedPreferences
        val sharedPref = getApplication<Application>().getSharedPreferences("USER_CREDENTIALS", Context.MODE_PRIVATE)
        val username = sharedPref.getString("USERNAME", "") ?: ""
        val password = sharedPref.getString("PASSWORD", "") ?: ""

        if (username.isEmpty() || password.isEmpty()) {
            _error.value = "Informations d'identification manquantes"
            _isLoading.value = false
            return
        }

        viewModelScope.launch(Dispatchers.IO) {
            when (val response = repository.getEmployeeByCredentials(username, password)) {
                is Resource.Success -> {
                    response.data?.let {
                        _userData.postValue(it)
                    } ?: run {
                        _error.postValue("User data is null")
                    }
                    _isLoading.postValue(false)
                }
                is Resource.Error -> {
                    _error.postValue(response.message ?: "Impossible de récupérer les données utilisateur")
                    _isLoading.postValue(false)
                }
                else -> {
                    _error.postValue("Unexpected response type")
                    _isLoading.postValue(false)
                }
            }
        }
    }
}