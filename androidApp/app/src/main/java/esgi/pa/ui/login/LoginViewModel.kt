package esgi.pa.ui.login

import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import esgi.pa.data.repository.AuthRepository
import esgi.pa.data.model.GetOneByCredentialsResponse
import esgi.pa.data.model.LoginResponse
import esgi.pa.util.Resource
import kotlinx.coroutines.flow.MutableStateFlow
import kotlinx.coroutines.flow.StateFlow
import kotlinx.coroutines.launch

class LoginViewModel(private val authRepository: AuthRepository) : ViewModel() {

    private val _loginState = MutableStateFlow<Resource<LoginResponse>>(Resource.Loading())
    val loginState: StateFlow<Resource<LoginResponse>> = _loginState

    private val _employeeState = MutableStateFlow<Resource<GetOneByCredentialsResponse>>(Resource.Loading())
    val employeeState: StateFlow<Resource<GetOneByCredentialsResponse>> = _employeeState

    fun login(username: String, password: String) {
        viewModelScope.launch {
            _loginState.value = Resource.Loading()
            val result = authRepository.login(username, password)
            _loginState.value = result

            // If login successful, get employee data with credentials
            if (result is Resource.Success) {
                getEmployeeData(username, password)
            }
        }
    }

    private fun getEmployeeData(username: String, password: String) {
        viewModelScope.launch {
            _employeeState.value = Resource.Loading()
            val result = authRepository.getEmployeeByCredentials(username, password)
            _employeeState.value = result
        }
    }
}