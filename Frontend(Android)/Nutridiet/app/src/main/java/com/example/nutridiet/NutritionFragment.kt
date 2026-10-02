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
import androidx.fragment.app.Fragment
import com.google.android.material.progressindicator.CircularProgressIndicator

class NutritionFragment : Fragment() {

    private var consumed = 1240
    private val target = 1850
    private var water = 1750
    private val waterTarget = 2200

    private val dayIds = listOf(
        R.id.day22, R.id.day23, R.id.day24, R.id.day25,
        R.id.day26, R.id.day27, R.id.day28
    )

    override fun onCreateView(
        inflater: LayoutInflater, container: ViewGroup?, savedInstanceState: Bundle?
    ): View = inflater.inflate(R.layout.fragment_nutrition, container, false)

    override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        super.onViewCreated(view, savedInstanceState)

        view.findViewById<ImageButton>(R.id.btnNotifNutri)?.setOnClickListener {
            Toast.makeText(requireContext(), "Belum ada notifikasi baru", Toast.LENGTH_SHORT).show()
        }

        setupCalendar(view)
        setupDinner(view)
        setupWater(view)
        setupActions(view)
    }

    private fun setupCalendar(view: View) {
        fun select(id: Int) {
            dayIds.forEach {
                val b = view.findViewById<Button>(it)
                if (it == id) {
                    b.setBackgroundResource(R.drawable.bg_btn_primary)
                    b.setTextColor(requireContext().getColor(R.color.on_primary))
                } else {
                    b.setBackgroundResource(R.drawable.bg_field)
                    b.setTextColor(requireContext().getColor(R.color.on_surface_variant))
                }
            }
        }
        dayIds.forEach { id ->
            view.findViewById<Button>(id)?.setOnClickListener { select(id) }
        }
        view.findViewById<Button>(R.id.btnToday)?.setOnClickListener { select(R.id.day24) }
    }

    private fun setupDinner(view: View) {
        view.findViewById<Button>(R.id.btnDinnerSave)?.setOnClickListener {
            consumed = (consumed + 410).coerceAtMost(target)
            val left = (target - consumed).coerceAtLeast(0)
            view.findViewById<TextView>(R.id.tvNutriTotal).text =
                String.format("%,d", consumed).replace(',', '.')
            view.findViewById<TextView>(R.id.tvNutriLeft).text =
                "Sisa $left kkal untuk makan malam"
            view.findViewById<ProgressBar>(R.id.barNutri).progress = consumed
            val pct = ((consumed.toFloat() / target) * 100).toInt().coerceIn(0, 100)
            view.findViewById<CircularProgressIndicator>(R.id.ringNutri).progress = pct
            Toast.makeText(requireContext(), getString(R.string.toast_dinner_added), Toast.LENGTH_SHORT).show()
        }
    }

    private fun setupWater(view: View) {
        val tv = view.findViewById<TextView>(R.id.tvWaterMini)
        val bar = view.findViewById<ProgressBar>(R.id.barWaterMini)
        fun render() {
            tv.text = String.format("%,d", water).replace(',', '.') +
                " ml dari target ${String.format("%,d", waterTarget).replace(',', '.')} ml"
            bar.progress = water
        }
        render()
        view.findViewById<Button>(R.id.btnWaterMini)?.setOnClickListener {
            water = (water + 250).coerceAtMost(waterTarget)
            render()
            if (water >= waterTarget) {
                Toast.makeText(requireContext(), getString(R.string.toast_water_full), Toast.LENGTH_SHORT).show()
            }
        }
    }

    private fun setupActions(view: View) {
        view.findViewById<Button>(R.id.btnScan)?.setOnClickListener {
            Toast.makeText(requireContext(), getString(R.string.toast_scan_soon), Toast.LENGTH_SHORT).show()
        }
        view.findViewById<Button>(R.id.btnHistory)?.setOnClickListener {
            Toast.makeText(requireContext(), getString(R.string.toast_history_soon), Toast.LENGTH_SHORT).show()
        }
        view.findViewById<Button>(R.id.btnSeeAll)?.setOnClickListener {
            Toast.makeText(requireContext(), getString(R.string.toast_recipe_soon), Toast.LENGTH_SHORT).show()
        }
        listOf(R.id.btnRecipe1, R.id.btnRecipe2, R.id.btnRecipe3).forEach { id ->
            view.findViewById<Button>(id)?.setOnClickListener {
                Toast.makeText(requireContext(), getString(R.string.toast_recipe_soon), Toast.LENGTH_SHORT).show()
            }
        }
    }
}
