// LoginViewModel.kt
package esgi.pa.ui.login

import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import esgi.pa.data.repository.AuthRepository
import esgi.pa.data.model.LoginResponse
import esgi.pa.util.Resource
import kotlinx.coroutines.flow.MutableStateFlow
import kotlinx.coroutines.flow.StateFlow
import kotlinx.coroutines.launch

class LoginViewModel(private val authRepository: AuthRepository) : ViewModel() {

    private val _loginState = MutableStateFlow<Resource<LoginResponse>>(Resource.Loading())
    val loginState: StateFlow<Resource<LoginResponse>> = _loginState

    fun login(username: String, password: String) {
        viewModelScope.launch {
            _loginState.value = Resource.Loading()
            val result = authRepository.login(username, password)
            _loginState.value = result
        }
    }
}