package esgi.pa.data.model

import java.time.ZonedDateTime

data class LoginResponse(
    val token: String,
    val date: DateInfo
)

data class DateInfo(
    val date: String,
    val timezone_type: Int,
    val timezone: String
)