package com.example.nutridiet

import android.os.Bundle
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.Button
import android.widget.ImageButton
import android.widget.ProgressBar
import android.widget.TextView
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import androidx.fragment.app.Fragment
import com.google.android.material.progressindicator.CircularProgressIndicator

class HomeFragment : Fragment() {

    private var waterCurrent = 2100
    private val waterTarget = 2500

    private var consumed = 1240
    private val targetCal = 1850

    override fun onCreateView(
        inflater: LayoutInflater, container: ViewGroup?, savedInstanceState: Bundle?
    ): View = inflater.inflate(R.layout.fragment_home, container, false)

    override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        super.onViewCreated(view, savedInstanceState)

        val prefs = requireContext().getSharedPreferences("nutridiet_auth", AppCompatActivity.MODE_PRIVATE)
        val savedName = prefs.getString("name", null)
        if (!savedName.isNullOrBlank()) {
            val first = savedName.trim().split("\\s+".toRegex()).firstOrNull() ?: savedName
            view.findViewById<TextView>(R.id.tvGreeting).text = "Halo, $first!"
        }

        view.findViewById<ImageButton>(R.id.btnNotifHome)?.setOnClickListener {
            Toast.makeText(requireContext(), "Belum ada notifikasi baru", Toast.LENGTH_SHORT).show()
        }

        setupWater(view)
        setupDinner(view)
        view.findViewById<View>(R.id.cardTips)?.setOnClickListener {
            Toast.makeText(requireContext(), getString(R.string.toast_tips_soon), Toast.LENGTH_SHORT).show()
        }
    }

    private fun setupWater(view: View) {
        val tvWater = view.findViewById<TextView>(R.id.tvWaterCurrent)
        val bar = view.findViewById<ProgressBar>(R.id.waterBar)
        val tvCups = view.findViewById<TextView>(R.id.tvCups)
        val btn = view.findViewById<Button>(R.id.btnAddWater)

        fun render() {
            tvWater.text = String.format("%,d", waterCurrent).replace(',', '.')
            bar.progress = waterCurrent
            val filled = (waterCurrent / 500).coerceIn(0, 5)
            tvCups.text = "🥛".repeat(filled) + "○".repeat(5 - filled)
        }
        render()

        btn.setOnClickListener {
            if (waterCurrent < waterTarget) {
                waterCurrent = (waterCurrent + 250).coerceAtMost(waterTarget)
                render()
                if (waterCurrent >= waterTarget) {
                    Toast.makeText(requireContext(), getString(R.string.toast_water_full), Toast.LENGTH_SHORT).show()
                }
            } else {
                Toast.makeText(requireContext(), getString(R.string.toast_water_full), Toast.LENGTH_SHORT).show()
            }
        }
    }

    private fun setupDinner(view: View) {
        view.findViewById<Button>(R.id.btnAddDinner)?.setOnClickListener {
            consumed += 450
            val remaining = (targetCal - consumed).coerceAtLeast(0)
            view.findViewById<TextView>(R.id.tvRemaining).text = remaining.toString()
            view.findViewById<TextView>(R.id.tvConsumed).text =
                String.format("%,d", consumed).replace(',', '.')
            val pct = ((consumed.toFloat() / targetCal.toFloat()) * 100).toInt().coerceIn(0, 100)
            view.findViewById<CircularProgressIndicator>(R.id.ringCalories).progress = pct
            Toast.makeText(requireContext(), getString(R.string.toast_dinner_added), Toast.LENGTH_SHORT).show()
        }
    }
}
