package com.example.nutridiet

import android.os.Bundle
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.Button
import android.widget.ImageButton
import android.widget.Toast
import androidx.fragment.app.Fragment

class ActivityFragment : Fragment() {

    private val dayIds = listOf(
        R.id.act21, R.id.act22, R.id.act23, R.id.act24,
        R.id.act25, R.id.act26, R.id.act27
    )

    override fun onCreateView(
        inflater: LayoutInflater, container: ViewGroup?, savedInstanceState: Bundle?
    ): View = inflater.inflate(R.layout.fragment_activity, container, false)

    override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        super.onViewCreated(view, savedInstanceState)

        view.findViewById<ImageButton>(R.id.btnNotifAct)?.setOnClickListener {
            Toast.makeText(requireContext(), "Belum ada notifikasi baru", Toast.LENGTH_SHORT).show()
        }

        dayIds.forEach { id ->
            view.findViewById<Button>(id)?.setOnClickListener { selectDay(view, id) }
        }

        view.findViewById<Button>(R.id.btnActHistory)?.setOnClickListener {
            Toast.makeText(requireContext(), getString(R.string.toast_history_soon), Toast.LENGTH_SHORT).show()
        }
        view.findViewById<Button>(R.id.btnExplore)?.setOnClickListener {
            Toast.makeText(requireContext(), getString(R.string.toast_recipe_soon), Toast.LENGTH_SHORT).show()
        }
        view.findViewById<Button>(R.id.btnStartNow)?.setOnClickListener {
            Toast.makeText(requireContext(), getString(R.string.toast_act_started), Toast.LENGTH_SHORT).show()
        }
        listOf(R.id.btnSesi1, R.id.btnSesi2, R.id.btnSesi3).forEach { id ->
            view.findViewById<Button>(id)?.setOnClickListener {
                Toast.makeText(requireContext(), getString(R.string.toast_act_started), Toast.LENGTH_SHORT).show()
            }
        }
        view.findViewById<Button>(R.id.btnLogManual)?.setOnClickListener {
            Toast.makeText(requireContext(), getString(R.string.toast_act_logged), Toast.LENGTH_SHORT).show()
        }
        view.findViewById<Button>(R.id.btnTimer)?.setOnClickListener {
            Toast.makeText(requireContext(), getString(R.string.toast_timer_soon), Toast.LENGTH_SHORT).show()
        }
    }

    private fun selectDay(view: View, id: Int) {
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
}
