package com.example.nutridiet

import android.content.Context
import androidx.appcompat.app.AppCompatDelegate

object ThemeHelper {
    const val MODE_SYSTEM = "system"
    const val MODE_LIGHT = "light"
    const val MODE_DARK = "dark"

    private const val PREF = "nutridiet_settings"
    private const val KEY = "theme_mode"

    fun getMode(context: Context): String =
        context.getSharedPreferences(PREF, Context.MODE_PRIVATE)
            .getString(KEY, MODE_SYSTEM) ?: MODE_SYSTEM

    fun setMode(context: Context, mode: String) {
        context.getSharedPreferences(PREF, Context.MODE_PRIVATE)
            .edit().putString(KEY, mode).apply()
        apply(mode)
    }

    fun apply(mode: String) {
        AppCompatDelegate.setDefaultNightMode(
            when (mode) {
                MODE_LIGHT -> AppCompatDelegate.MODE_NIGHT_NO
                MODE_DARK -> AppCompatDelegate.MODE_NIGHT_YES
                else -> AppCompatDelegate.MODE_NIGHT_FOLLOW_SYSTEM
            }
        )
    }

    fun applySaved(context: Context) = apply(getMode(context))
}
