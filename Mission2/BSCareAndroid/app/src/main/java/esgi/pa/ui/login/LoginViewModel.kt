// LoginViewModel.kt
package esgi.pa.ui.login

import android.util.Patterns
import androidx.lifecycle.LiveData
import androidx.lifecycle.MutableLiveData
import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import esgi.pa.data.model.LoginResponse
import esgi.pa.data.repository.AuthRepository
import esgi.pa.util.Resource
import kotlinx.coroutines.launch

class LoginViewModel : ViewModel() {

    private val repository = AuthRepository()

    private val _loginResult = MutableLiveData<Resource<LoginResponse>>()
    val loginResult: LiveData<Resource<LoginResponse>> = _loginResult

    fun login(username: String, password: String) {
        // Update validation
        if (username.isEmpty()) {
            _loginResult.value = Resource.Error("Please enter your username")
            return
        }

        viewModelScope.launch {
            _loginResult.value = Resource.Loading()
            val result = repository.login(username, password)
            _loginResult.value = result
        }
    }
}