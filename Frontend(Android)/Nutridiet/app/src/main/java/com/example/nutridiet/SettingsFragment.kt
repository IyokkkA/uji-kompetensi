package com.example.nutridiet

import android.content.Intent
import android.os.Bundle
import android.text.Editable
import android.text.TextWatcher
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.Button
import android.widget.EditText
import android.widget.LinearLayout
import android.widget.TextView
import android.widget.Toast
import androidx.appcompat.app.AlertDialog
import androidx.appcompat.app.AppCompatActivity
import androidx.fragment.app.Fragment
import com.google.android.material.switchmaterial.SwitchMaterial

class SettingsFragment : Fragment() {

    override fun onCreateView(
        inflater: LayoutInflater, container: ViewGroup?, savedInstanceState: Bundle?
    ): View = inflater.inflate(R.layout.fragment_settings, container, false)

    override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        super.onViewCreated(view, savedInstanceState)
        setupSwitches(view)
        setupSearch(view)
        setupRows(view)
    }

    override fun onResume() {
        super.onResume()
        // Segarkan label tema setiap kembali ke tab ini
        view?.findViewById<TextView>(R.id.tvThemeValue)?.text = themeLabel(ThemeHelper.getMode(requireContext()))
    }

    private fun prefs() = requireContext()
        .getSharedPreferences("nutridiet_settings", AppCompatActivity.MODE_PRIVATE)

    private fun setupSwitches(view: View) {
        bindSwitch(view, R.id.swWater, "reminder_water", true)
        bindSwitch(view, R.id.swFood, "reminder_food", true)
        bindSwitch(view, R.id.swReport, "report_weekly", true)
    }

    private fun bindSwitch(view: View, viewId: Int, prefKey: String, default: Boolean) {
        val sw = view.findViewById<SwitchMaterial>(viewId)
        sw.isChecked = prefs().getBoolean(prefKey, default)
        sw.setOnCheckedChangeListener { _, isChecked ->
            prefs().edit().putBoolean(prefKey, isChecked).apply()
            Toast.makeText(
                requireContext(),
                if (isChecked) getString(R.string.toast_notif_on)
                else getString(R.string.toast_notif_off),
                Toast.LENGTH_SHORT
            ).show()
        }
    }

    private fun setupSearch(view: View) {
        val etSearch = view.findViewById<EditText>(R.id.etSearch)
        val sections = listOf(
            R.id.sectionDiet, R.id.sectionNotif, R.id.sectionDevice,
            R.id.sectionAccount, R.id.sectionHelp
        ).mapNotNull { view.findViewById<LinearLayout>(it) }

        etSearch.addTextChangedListener(object : TextWatcher {
            override fun beforeTextChanged(s: CharSequence?, a: Int, b: Int, c: Int) {}
            override fun onTextChanged(s: CharSequence?, a: Int, b: Int, c: Int) {}
            override fun afterTextChanged(s: Editable?) {
                val q = (s?.toString() ?: "").trim().lowercase()
                if (q.isEmpty()) {
                    sections.forEach { it.visibility = View.VISIBLE }
                    return
                }
                sections.forEach { sec ->
                    val text = collectText(sec).lowercase()
                    sec.visibility = if (text.contains(q)) View.VISIBLE else View.GONE
                }
            }
        })
    }

    private fun collectText(v: View): String {
        val sb = StringBuilder()
        if (v is android.view.ViewGroup) {
            for (i in 0 until v.childCount) {
                sb.append(collectText(v.getChildAt(i))).append(' ')
            }
        } else if (v is TextView) {
            sb.append(v.text?.toString() ?: "")
        }
        return sb.toString()
    }

    private fun setupRows(view: View) {
        view.findViewById<View>(R.id.btnNotifSettings)?.setOnClickListener {
            Toast.makeText(requireContext(), "Belum ada notifikasi baru", Toast.LENGTH_SHORT).show()
        }
        rowToast(view, R.id.rowDietType, "Tipe diet: Rendah Kalori Seimbang")
        rowToast(view, R.id.rowAllergy, "Alergi: Kacang, Laktosa, Gluten")
        rowToast(view, R.id.rowSchedule, "Jadwal makan: 07:30 • 12:30 • 19:00 WIB")
        rowToast(view, R.id.rowFit, "Google Fit sudah terhubung")
        rowToast(view, R.id.rowScale, "Pencarian timbangan Bluetooth dimulai…")
        rowToast(view, R.id.rowPassword, "Keamanan biometrik aktif")
        rowToast(view, R.id.rowPrivacy, "Pengaturan privasi data diet")
        rowToast(view, R.id.rowHelp, "Pusat bantuan & tanya ahli gizi segera hadir")

        view.findViewById<TextView>(R.id.tvThemeValue)?.text =
            themeLabel(ThemeHelper.getMode(requireContext()))
        view.findViewById<View>(R.id.rowTheme)?.setOnClickListener { showThemeDialog(view) }

        view.findViewById<Button>(R.id.btnLogout).setOnClickListener { confirmLogout() }
    }

    private fun themeLabel(mode: String): String = when (mode) {
        ThemeHelper.MODE_LIGHT -> getString(R.string.settings_theme_light)
        ThemeHelper.MODE_DARK -> getString(R.string.settings_theme_dark)
        else -> getString(R.string.settings_theme_system)
    }

    private fun showThemeDialog(view: View) {
        val options = arrayOf(
            getString(R.string.settings_theme_light),
            getString(R.string.settings_theme_dark),
            getString(R.string.settings_theme_system)
        )
        val modes = arrayOf(
            ThemeHelper.MODE_LIGHT, ThemeHelper.MODE_DARK, ThemeHelper.MODE_SYSTEM
        )
        val current = modes.indexOf(ThemeHelper.getMode(requireContext())).coerceAtLeast(2)
        AlertDialog.Builder(requireContext())
            .setTitle(getString(R.string.settings_theme_title))
            .setSingleChoiceItems(options, current) { dialog, which ->
                ThemeHelper.setMode(requireContext(), modes[which])
                view.findViewById<TextView>(R.id.tvThemeValue)?.text = options[which]
                dialog.dismiss()
            }
            .setNegativeButton(getString(R.string.toast_logout_cancel), null)
            .show()
    }

    private fun rowToast(view: View, id: Int, msg: String) {
        view.findViewById<View>(id)?.setOnClickListener {
            Toast.makeText(requireContext(), msg, Toast.LENGTH_SHORT).show()
        }
    }

    private fun confirmLogout() {
        AlertDialog.Builder(requireContext())
            .setMessage(getString(R.string.toast_logout_confirm))
            .setPositiveButton(getString(R.string.toast_logout_yes)) { _, _ ->
                requireContext().getSharedPreferences("nutridiet_auth", AppCompatActivity.MODE_PRIVATE)
                    .edit().clear().apply()
                Toast.makeText(requireContext(), getString(R.string.toast_logged_out), Toast.LENGTH_SHORT).show()
                startActivity(Intent(requireContext(), LoginActivity::class.java))
                requireActivity().finishAffinity()
            }
            .setNegativeButton(getString(R.string.toast_logout_cancel), null)
            .show()
    }
}
