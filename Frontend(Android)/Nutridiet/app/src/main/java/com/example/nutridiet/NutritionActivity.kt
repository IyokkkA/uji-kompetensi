package com.example.nutridiet

import android.content.Intent
import android.os.Bundle
import androidx.appcompat.app.AppCompatActivity

// Pengalihan kompatibilitas: semua tab kini di-host MainActivity single-activity
// agar navbar tetap dan transisi smooth. Activity lama hanya meneruskan.
class NutritionActivity : AppCompatActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        startActivity(Intent(this, MainActivity::class.java).apply {
            putExtra(MainActivity.EXTRA_TAB, MainActivity.TAB_NUTRITION)
        })
        overridePendingTransition(0, 0)
        finish()
    }
}
