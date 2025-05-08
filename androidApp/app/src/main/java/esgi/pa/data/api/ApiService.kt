package esgi.pa.data.api

import esgi.pa.data.model.GetEmployeeEventResponse
import esgi.pa.data.model.LoginRequest
import esgi.pa.data.model.LoginResponse
import retrofit2.Response
import retrofit2.http.Body
import retrofit2.http.GET
import retrofit2.http.POST

interface ApiService {

    @POST("employee/login.php")
    suspend fun login(@Body request: LoginRequest): Response<LoginResponse>

    @GET("employee/getEvent.php")
    suspend fun getEmployeeEvents(): Response<GetEmployeeEventResponse>
}