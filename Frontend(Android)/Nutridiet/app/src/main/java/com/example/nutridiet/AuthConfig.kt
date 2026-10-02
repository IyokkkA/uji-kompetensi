package com.example.nutridiet

import android.content.Context

object ApiConfig {
    // Ganti dengan URL Laravel kamu. Emulator -> 10.0.2.2, HP fisik -> IP LAN misal http://192.168.1.5:8000
    const val BASE_URL = "http://10.0.2.2:8000"
    const val REGISTER_ENDPOINT = "$BASE_URL/api/register"
    const val LOGIN_ENDPOINT = "$BASE_URL/api/login"
}

object UserPrefs {
    private const val PREF = "nutridiet_auth"
    fun save(context: Context, name: String, email: String, goal: String, token: String?) {
        context.getSharedPreferences(PREF, Context.MODE_PRIVATE).edit()
            .putString("name", name)
            .putString("email", email)
            .putString("goal", goal)
            .putString("token", token)
            .putBoolean("is_logged_in", true)
            .apply()
    }
    fun isLoggedIn(context: Context): Boolean =
        context.getSharedPreferences(PREF, Context.MODE_PRIVATE).getBoolean("is_logged_in", false)

    fun saveLogin(context: Context, name: String, identifier: String, token: String?, remember: Boolean) {
        val prefs = context.getSharedPreferences(PREF, Context.MODE_PRIVATE)
        val savedName = prefs.getString("name", null)
        val savedGoal = prefs.getString("goal", "weight_loss") ?: "weight_loss"
        prefs.edit()
            .putString("name", name.ifBlank { savedName ?: identifier })
            .putString("email", identifier)
            .putString("goal", savedGoal)
            .putString("token", token)
            .putBoolean("remember_me", remember)
            .putBoolean("is_logged_in", remember)
            .apply()
    }
}
