package com.example.nutridiet

import android.content.Intent
import android.os.Bundle
import android.os.Handler
import android.os.Looper
import android.text.method.HideReturnsTransformationMethod
import android.text.method.PasswordTransformationMethod
import android.util.Patterns
import android.widget.CheckBox
import android.widget.EditText
import android.widget.FrameLayout
import android.widget.ImageButton
import android.widget.ImageView
import android.widget.TextView
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import org.json.JSONObject
import java.net.HttpURLConnection
import java.net.URL

class LoginActivity : AppCompatActivity() {

    private lateinit var etIdentifier: EditText
    private lateinit var etPassword: EditText
    private lateinit var cbRemember: CheckBox
    private lateinit var btnLogin: FrameLayout
    private lateinit var tvLoginLabel: TextView
    private lateinit var ivLoginIcon: ImageView

    private var isPasswordVisible = false
    private var isLoading = false

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_login)

        etIdentifier = findViewById(R.id.etIdentifier)
        etPassword = findViewById(R.id.etPassword)
        cbRemember = findViewById(R.id.cbRemember)
        btnLogin = findViewById(R.id.btnLogin)
        tvLoginLabel = findViewById(R.id.tvLoginLabel)
        ivLoginIcon = findViewById(R.id.ivLoginIcon)

        findViewById<ImageButton>(R.id.btnTogglePassword).setOnClickListener {
            isPasswordVisible = !isPasswordVisible
            val sel = etPassword.selectionEnd
            etPassword.transformationMethod = if (isPasswordVisible)
                HideReturnsTransformationMethod.getInstance()
            else PasswordTransformationMethod.getInstance()
            (it as ImageButton).setImageResource(
                if (isPasswordVisible) R.drawable.ic_eye_off else R.drawable.ic_eye
            )
            etPassword.setSelection(sel.coerceAtLeast(0))
        }

        btnLogin.setOnClickListener { onLoginClicked() }
        findViewById<TextView>(R.id.tvForgotPassword).setOnClickListener {
            Toast.makeText(this, getString(R.string.toast_forgot_soon), Toast.LENGTH_SHORT).show()
        }
        findViewById<FrameLayout>(R.id.btnGoogleLogin).setOnClickListener {
            Toast.makeText(this, getString(R.string.toast_google_soon), Toast.LENGTH_SHORT).show()
        }
        findViewById<FrameLayout>(R.id.btnAppleLogin).setOnClickListener {
            Toast.makeText(this, getString(R.string.toast_apple_soon), Toast.LENGTH_SHORT).show()
        }
        findViewById<TextView>(R.id.tvRegister).setOnClickListener {
            startActivity(Intent(this, RegisterActivity::class.java))
        }
    }

    private fun onLoginClicked() {
        if (isLoading) return
        val id = etIdentifier.text.toString().trim()
        val pass = etPassword.text.toString()

        if (id.isEmpty()) { etIdentifier.error = getString(R.string.err_identifier_empty); etIdentifier.requestFocus(); return }
        val isPhone = id.all { it.isDigit() || it == '+' || it == ' ' || it == '-' }
        if (!isPhone && !Patterns.EMAIL_ADDRESS.matcher(id).matches()) {
            etIdentifier.error = getString(R.string.err_identifier_invalid); etIdentifier.requestFocus(); return
        }
        if (pass.length < 8) { etPassword.error = getString(R.string.err_pass_short); etPassword.requestFocus(); return }

        setLoading(true, getString(R.string.btn_login_loading))
        doLoginApi(id, pass,
            onSuccess = { name, token ->
                if (cbRemember.isChecked) {
                    UserPrefs.save(this, name ?: id, id, "", token)
                }
                Handler(Looper.getMainLooper()).post {
                    setLoading(true, getString(R.string.btn_login_success))
                    Toast.makeText(this, getString(R.string.btn_login_success), Toast.LENGTH_SHORT).show()
                    Handler(Looper.getMainLooper()).postDelayed({
                        setLoading(false, getString(R.string.btn_login))
                        startActivity(Intent(this, MainActivity::class.java))
                    }, 1200)
                }
            },
            onError = { msg ->
                Handler(Looper.getMainLooper()).post {
                    setLoading(false, getString(R.string.btn_login))
                    Toast.makeText(this, msg, Toast.LENGTH_LONG).show()
                }
            })
    }

    private fun setLoading(loading: Boolean, text: String) {
        isLoading = loading
        btnLogin.isClickable = !loading
        btnLogin.isFocusable = !loading
        tvLoginLabel.text = text
    }

    private fun doLoginApi(
        identifier: String, password: String,
        onSuccess: (name: String?, token: String?) -> Unit,
        onError: (msg: String) -> Unit
    ) {
        Thread {
            var conn: HttpURLConnection? = null
            try {
                val url = URL("${ApiConfig.BASE_URL}/api/login")
                conn = (url.openConnection() as HttpURLConnection).apply {
                    requestMethod = "POST"
                    connectTimeout = 8000
                    readTimeout = 8000
                    doOutput = true
                    setRequestProperty("Content-Type", "application/json")
                    setRequestProperty("Accept", "application/json")
                }
                val isEmail = Patterns.EMAIL_ADDRESS.matcher(identifier).matches()
                val body = JSONObject()
                    .put(if (isEmail) "email" else "phone", identifier)
                    .put("password", password)
                    .toString()
                conn.outputStream.bufferedWriter().use { it.write(body); it.flush() }
                val code = conn.responseCode
                val resp = (if (code in 200..299) conn.inputStream else conn.errorStream)
                    .bufferedReader().use { it.readText() }
                if (code in 200..299) {
                    val j = try { JSONObject(resp) } catch (_: Exception) { JSONObject() }
                    val name = if (j.isNull("name")) null else j.optString("name")
                    val token = if (j.isNull("token")) null else j.optString("token")
                    onSuccess(name, token)
                } else {
                    val msg = try { JSONObject(resp).optString("message", resp.take(200)) }
                    catch (_: Exception) { resp.take(200) }
                    if (code == 404 && resp.contains("<", ignoreCase = true)) {
                        onSuccess(null, null) // backend belum ada -> demo lokal
                    } else {
                        onError(msg.ifEmpty { "Gagal masuk ($code)" })
                    }
                }
            } catch (_: java.net.ConnectException) {
                onSuccess(null, null) // demo lokal saat backend mati
            } catch (e: Exception) {
                onError("Tidak dapat terhubung: ${e.message}")
            } finally {
                conn?.disconnect()
            }
        }.start()
    }
}
