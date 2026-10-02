package com.example.nutridiet

import android.os.Bundle
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.Button
import android.widget.ImageButton
import android.widget.LinearLayout
import android.widget.TextView
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import androidx.fragment.app.Fragment

class ProfileFragment : Fragment() {

    override fun onCreateView(
        inflater: LayoutInflater, container: ViewGroup?, savedInstanceState: Bundle?
    ): View = inflater.inflate(R.layout.fragment_profile, container, false)

    override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        super.onViewCreated(view, savedInstanceState)

        val prefs = requireContext().getSharedPreferences("nutridiet_auth", AppCompatActivity.MODE_PRIVATE)
        val savedName = prefs.getString("name", null)
        if (!savedName.isNullOrBlank()) {
            view.findViewById<TextView>(R.id.tvName).text = savedName
        }

        view.findViewById<Button>(R.id.btnEditProfile).setOnClickListener {
            Toast.makeText(requireContext(), "Edit profil segera hadir", Toast.LENGTH_SHORT).show()
        }
        view.findViewById<Button>(R.id.btnReport).setOnClickListener {
            Toast.makeText(requireContext(), "Laporan PDF sedang disiapkan…", Toast.LENGTH_SHORT).show()
        }
        view.findViewById<ImageButton>(R.id.btnNotif).setOnClickListener {
            Toast.makeText(requireContext(), "Belum ada notifikasi baru", Toast.LENGTH_SHORT).show()
        }
        // Navigasi antar tab diurus MainActivity (host) lewat navbar tetap,
        // bukan dari dalam konten fragment.
    }
}
