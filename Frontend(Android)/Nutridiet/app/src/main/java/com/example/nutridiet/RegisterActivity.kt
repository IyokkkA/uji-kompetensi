package com.example.nutridiet

import android.content.Intent
import android.os.Handler
import android.os.Looper
import android.text.method.HideReturnsTransformationMethod
import android.text.method.PasswordTransformationMethod
import android.util.Patterns
import android.widget.Button
import android.widget.CheckBox
import android.widget.EditText
import android.widget.FrameLayout
import android.widget.LinearLayout
import android.widget.TextView
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle
import org.json.JSONObject
import java.io.OutputStreamWriter
import java.net.HttpURLConnection
import java.net.URL

class RegisterActivity : AppCompatActivity() {

    private lateinit var etFullName: EditText
    private lateinit var etEmail: EditText
    private lateinit var etPassword: EditText
    private lateinit var etConfirm: EditText
    private lateinit var btnTogglePassword: android.widget.ImageButton
    private lateinit var cbTerms: CheckBox
    private lateinit var btnRegister: Button
    private lateinit var btnGoogle: Button
    private lateinit var tvLogin: TextView

    private lateinit var goalCardLoss: LinearLayout
    private lateinit var goalCardMaintain: LinearLayout
    private lateinit var goalCardBulk: LinearLayout
    private lateinit var checkLoss: FrameLayout
    private lateinit var checkMaintain: FrameLayout
    private lateinit var checkBulk: FrameLayout

    // sama seperti selectGoal(this, 'weight_loss') di HTML Stitch
    private var selectedGoal = "weight_loss"
    private var isPasswordVisible = false
    private var isLoading = false

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_register)

        etFullName = findViewById(R.id.etFullName)
        etEmail = findViewById(R.id.etEmail)
        etPassword = findViewById(R.id.etPassword)
        etConfirm = findViewById(R.id.etConfirm)
        btnTogglePassword = findViewById(R.id.btnTogglePassword)
        cbTerms = findViewById(R.id.cbTerms)
        btnRegister = findViewById(R.id.btnRegister)
        btnGoogle = findViewById(R.id.btnGoogle)
        tvLogin = findViewById(R.id.tvLogin)
        goalCardLoss = findViewById(R.id.goalCardLoss)
        goalCardMaintain = findViewById(R.id.goalCardMaintain)
        goalCardBulk = findViewById(R.id.goalCardBulk)
        checkLoss = findViewById(R.id.checkLoss)
        checkMaintain = findViewById(R.id.checkMaintain)
        checkBulk = findViewById(R.id.checkBulk)

        // Toggle password visibility — sama seperti JS togglePassword di HTML
        btnTogglePassword.setOnClickListener {
            isPasswordVisible = !isPasswordVisible
            val sel = etPassword.selectionEnd
            if (isPasswordVisible) {
                etPassword.transformationMethod = HideReturnsTransformationMethod.getInstance()
                btnTogglePassword.setImageResource(android.R.drawable.ic_menu_view)
            } else {
                etPassword.transformationMethod = PasswordTransformationMethod.getInstance()
                btnTogglePassword.setImageResource(R.drawable.ic_eye)
            }
            etPassword.setSelection(sel.coerceAtLeast(0))
        }

        // Goal selector — sama seperti function selectGoal(element, goalKey)
        goalCardLoss.setOnClickListener { selectGoal("weight_loss") }
        goalCardMaintain.setOnClickListener { selectGoal("maintenance") }
        goalCardBulk.setOnClickListener { selectGoal("healthy_bulk") }
        selectGoal("weight_loss")

        btnRegister.setOnClickListener { onRegisterClicked() }
        btnGoogle.setOnClickListener {
            Toast.makeText(this, "Login Google segera hadir", Toast.LENGTH_SHORT).show()
        }
        tvLogin.setOnClickListener {
            startActivity(Intent(this, LoginActivity::class.java))
            finish()
        }
    }

    private fun selectGoal(goalKey: String) {
        selectedGoal = goalKey
        val cards = listOf(goalCardLoss, goalCardMaintain, goalCardBulk)
        val checks = listOf(checkLoss, checkMaintain, checkBulk)
        cards.forEach { it.setBackgroundResource(R.drawable.bg_goal) }
        checks.forEach { it.setBackgroundResource(R.drawable.bg_field) }
        when (goalKey) {
            "weight_loss" -> {
                goalCardLoss.setBackgroundResource(R.drawable.bg_goal_selected)
                checkLoss.setBackgroundResource(R.drawable.bg_btn_primary)
            }
            "maintenance" -> {
                goalCardMaintain.setBackgroundResource(R.drawable.bg_goal_selected)
                checkMaintain.setBackgroundResource(R.drawable.bg_btn_primary)
            }
            "healthy_bulk" -> {
                goalCardBulk.setBackgroundResource(R.drawable.bg_goal_selected)
                checkBulk.setBackgroundResource(R.drawable.bg_btn_primary)
            }
        }
    }

    private fun onRegisterClicked() {
        if (isLoading) return
        val name = etFullName.text.toString().trim()
        val email = etEmail.text.toString().trim()
        val pass = etPassword.text.toString()
        val confirm = etConfirm.text.toString()

        if (name.isEmpty()) { etFullName.error = getString(R.string.err_name_empty); etFullName.requestFocus(); return }
        if (email.isEmpty()) { etEmail.error = getString(R.string.err_email_empty); etEmail.requestFocus(); return }
        if (!Patterns.EMAIL_ADDRESS.matcher(email).matches()) { etEmail.error = getString(R.string.err_email_invalid); etEmail.requestFocus(); return }
        if (pass.length < 8) { etPassword.error = getString(R.string.err_pass_short); etPassword.requestFocus(); return }
        if (confirm != pass) { etConfirm.error = getString(R.string.err_confirm_mismatch); etConfirm.requestFocus(); return }
        if (!cbTerms.isChecked) { Toast.makeText(this, getString(R.string.err_terms), Toast.LENGTH_SHORT).show(); return }

        // Efek loading persis HTML: "Menyiapkan Pola Makanmu..." -> "Akun Berhasil Dibuat!"
        setLoading(true)
        doRegisterApi(name, email, pass, selectedGoal,
            onSuccess = { token ->
                UserPrefs.save(this, name, email, selectedGoal, token)
                Handler(Looper.getMainLooper()).post {
                    setSuccess()
                    Toast.makeText(this, getString(R.string.btn_success), Toast.LENGTH_SHORT).show()
                    Handler(Looper.getMainLooper()).postDelayed({ finish() }, 1200)
                }
            },
            onError = { msg ->
                Handler(Looper.getMainLooper()).post {
                    setLoading(false)
                    Toast.makeText(this, msg, Toast.LENGTH_LONG).show()
                }
            })
    }

    private fun setLoading(loading: Boolean) {
        isLoading = loading
        btnRegister.isEnabled = !loading
        btnRegister.text = if (loading) getString(R.string.btn_loading) else getString(R.string.btn_register)
    }

    private fun setSuccess() {
        isLoading = false
        btnRegister.isEnabled = true
        btnRegister.text = getString(R.string.btn_success)
    }

    /**
     * Fungsi register: POST ke Laravel /api/register.
     * Body: name, email, password, password_confirmation, goal
     * Jika server tidak reachable (dev tanpa backend), fallback simpan lokal agar UI tetap bisa didemo.
     */
    private fun doRegisterApi(
        name: String, email: String, password: String, goal: String,
        onSuccess: (token: String?) -> Unit,
        onError: (msg: String) -> Unit
    ) {
        Thread {
            var conn: HttpURLConnection? = null
            try {
                val url = URL(ApiConfig.REGISTER_ENDPOINT)
                conn = (url.openConnection() as HttpURLConnection).apply {
                    requestMethod = "POST"
                    connectTimeout = 8000
                    readTimeout = 8000
                    doOutput = true
                    setRequestProperty("Content-Type", "application/json")
                    setRequestProperty("Accept", "application/json")
                }
                val body = JSONObject()
                    .put("name", name)
                    .put("email", email)
                    .put("password", password)
                    .put("password_confirmation", etConfirm.text.toString())
                    .put("goal", goal)
                    .toString()
                OutputStreamWriter(conn.outputStream).use { it.write(body); it.flush() }
                val code = conn.responseCode
                val stream = if (code in 200..299) conn.inputStream else conn.errorStream
                val resp = stream.bufferedReader().use { it.readText() }
                if (code in 200..299) {
                    val token = try {
                        val j = JSONObject(resp)
                        if (j.isNull("token")) null else j.optString("token")
                    } catch (_: Exception) { null }
                    onSuccess(token)
                } else {
                    // Laravel validation error -> tampilkan pesan pertama
                    val msg = try {
                        val j = JSONObject(resp)
                        j.optString("message", resp.take(200))
                    } catch (_: Exception) { resp.take(200) }
                    // Fallback demo jika backend belum jalan: anggap 404/HTML = simpan lokal
                    if (code == 404 && resp.contains("<", ignoreCase = true)) {
                        onSuccess(null)
                    } else {
                        onError(msg.ifEmpty { "Gagal daftar ($code)" })
                    }
                }
            } catch (e: java.net.ConnectException) {
                // Backend belum jalan -> tetap sukses lokal supaya bisa demo UI "sama persis"
                onSuccess(null)
            } catch (e: Exception) {
                onError("Tidak dapat terhubung: ${e.message}")
            } finally {
                conn?.disconnect()
            }
        }.start()
    }
}
