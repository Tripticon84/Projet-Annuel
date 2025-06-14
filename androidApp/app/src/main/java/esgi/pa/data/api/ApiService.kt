package esgi.pa.data.api

import esgi.pa.data.model.Activity
import esgi.pa.data.model.Event
import esgi.pa.data.model.GetEmployeeEventResponse
import esgi.pa.data.model.GetOneByCredentialsRequest
import esgi.pa.data.model.GetOneByCredentialsResponse
import esgi.pa.data.model.LoginRequest
import esgi.pa.data.model.LoginResponse
import esgi.pa.data.model.RegisterToActivityRequest
import esgi.pa.data.model.RegisterToAnythingResponse
import esgi.pa.data.model.RegisterToEventRequest
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

    @GET("employee/getEvent.php")
    suspend fun getEmployeeEvent(
        @Query("collaborateur_id") collaborateur_id: Int
    ): Response<List<Event>>

    @GET("employee/getActivity.php")
    suspend fun getEmployeeActivity(
        @Query("collaborateur_id") collaborateur_id: Int
    ): Response<List<Activity>>

    @GET("activity/getAll.php")
    suspend fun getAllActivity(
    ): Response<List<Activity>>

    @GET("event/getAll.php")
    suspend fun getAllEvent(
    ): Response<List<Event>>

    @POST("employee/register.php")
    suspend fun registerToActivity(
        @Body request: RegisterToActivityRequest
    ): Response<RegisterToAnythingResponse>

    @POST("employee/register.php")
    suspend fun registerToEvent(
        @Body request: RegisterToEventRequest
    ): Response<RegisterToAnythingResponse>

    @POST("employee/unregister.php")
    suspend fun unregisterToActivity(
        @Body request: RegisterToActivityRequest
    ): Response<RegisterToAnythingResponse>

    @POST("employee/unregister.php")
    suspend fun unregisterToEvent(
        @Body request: RegisterToEventRequest
    ): Response<RegisterToAnythingResponse>
}