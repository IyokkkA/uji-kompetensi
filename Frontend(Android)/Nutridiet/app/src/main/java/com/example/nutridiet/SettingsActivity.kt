package com.example.nutridiet

import android.content.Intent
import android.os.Bundle
import androidx.appcompat.app.AppCompatActivity

// Pengalihan kompatibilitas ke MainActivity (tab Pengaturan).
class SettingsActivity : AppCompatActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        startActivity(Intent(this, MainActivity::class.java).apply {
            putExtra(MainActivity.EXTRA_TAB, MainActivity.TAB_SETTINGS)
        })
        overridePendingTransition(0, 0)
        finish()
    }
}
