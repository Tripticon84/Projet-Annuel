package esgi.pa.data.api

import esgi.pa.data.model.GetEmployeeEventResponse
import esgi.pa.data.model.GetOneByCredentialsRequest
import esgi.pa.data.model.GetOneByCredentialsResponse
import esgi.pa.data.model.LoginRequest
import esgi.pa.data.model.LoginResponse
import retrofit2.Response
import retrofit2.http.Body
import retrofit2.http.GET
import retrofit2.http.POST
import retrofit2.http.Query

interface ApiService {

    @POST("employee/login.php")
    suspend fun login(@Body request: LoginRequest): Response<LoginResponse>

    @GET("employee/getOneByCredentials.php")
    suspend fun authentificate(
        @Query("username") username: String,
        @Query("password") password: String
    ): Response<GetOneByCredentialsResponse>

}