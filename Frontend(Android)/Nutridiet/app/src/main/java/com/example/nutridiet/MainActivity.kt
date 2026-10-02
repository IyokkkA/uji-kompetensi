package com.example.nutridiet

import android.os.Bundle
import android.widget.ImageView
import android.widget.LinearLayout
import android.widget.TextView
import androidx.appcompat.app.AppCompatActivity
import androidx.fragment.app.Fragment

class MainActivity : AppCompatActivity() {

    companion object {
        const val EXTRA_TAB = "extra_tab"
        const val TAB_HOME = "home"
        const val TAB_NUTRITION = "nutrition"
        const val TAB_ACTIVITY = "activity"
        const val TAB_PROFILE = "profile"
        const val TAB_SETTINGS = "settings"
    }

    private var currentTab = TAB_HOME

    override fun onCreate(savedInstanceState: Bundle?) {
        ThemeHelper.applySaved(this)
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_main)

        findViewById<LinearLayout>(R.id.navHome).setOnClickListener { switchTab(TAB_HOME) }
        findViewById<LinearLayout>(R.id.navNutrition).setOnClickListener { switchTab(TAB_NUTRITION) }
        findViewById<LinearLayout>(R.id.navActivity).setOnClickListener { switchTab(TAB_ACTIVITY) }
        findViewById<LinearLayout>(R.id.navProfile).setOnClickListener { switchTab(TAB_PROFILE) }
        findViewById<LinearLayout>(R.id.navSettings).setOnClickListener { switchTab(TAB_SETTINGS) }

        val start = intent.getStringExtra(EXTRA_TAB) ?: TAB_HOME
        if (savedInstanceState == null) {
            showTab(start, animate = false)
        } else {
            currentTab = savedInstanceState.getString("current_tab", TAB_HOME)
            updateNavState()
        }
    }

    override fun onSaveInstanceState(outState: Bundle) {
        super.onSaveInstanceState(outState)
        outState.putString("current_tab", currentTab)
    }

    fun switchTab(tab: String) {
        if (tab == currentTab) return
        showTab(tab, animate = true)
    }

    private fun showTab(tab: String, animate: Boolean) {
        currentTab = tab
        val fragment: Fragment = when (tab) {
            TAB_NUTRITION -> NutritionFragment()
            TAB_ACTIVITY -> ActivityFragment()
            TAB_PROFILE -> ProfileFragment()
            TAB_SETTINGS -> SettingsFragment()
            else -> HomeFragment()
        }
        val tx = supportFragmentManager.beginTransaction()
        if (animate) {
            tx.setCustomAnimations(R.anim.tab_enter, R.anim.tab_exit)
        }
        tx.replace(R.id.nav_host, fragment, tab)
        tx.commit()
        updateNavState()
    }

    private fun updateNavState() {
        setItem(currentTab == TAB_HOME, R.id.pillHome, R.id.iconHome, R.id.labelHome)
        setItem(currentTab == TAB_NUTRITION, R.id.pillNutrition, R.id.iconNutrition, R.id.labelNutrition)
        setItem(currentTab == TAB_ACTIVITY, R.id.pillActivity, R.id.iconActivity, R.id.labelActivity)
        setItem(currentTab == TAB_PROFILE, R.id.pillProfile, R.id.iconProfile, R.id.labelProfile)
        setItem(currentTab == TAB_SETTINGS, R.id.pillSettings, R.id.iconSettings, R.id.labelSettings)
    }

    private fun setItem(active: Boolean, pillId: Int, iconId: Int, labelId: Int) {
        val pill = findViewById<LinearLayout>(pillId)
        val icon = findViewById<ImageView>(iconId)
        val label = findViewById<TextView>(labelId)
        if (active) {
            pill.setBackgroundResource(R.drawable.bg_field)
            icon.alpha = 1f
            label.setTextColor(getColor(R.color.primary))
            label.setTypeface(label.typeface, android.graphics.Typeface.BOLD)
        } else {
            pill.background = null
            icon.alpha = 0.6f
            label.setTextColor(getColor(R.color.on_surface_variant))
            label.setTypeface(label.typeface, android.graphics.Typeface.NORMAL)
        }
    }
}
