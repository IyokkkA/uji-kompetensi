<!DOCTYPE html>
<html lang="id">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login Admin · NutriDiet</title><script src="https://cdn.tailwindcss.com"></script></head>
<body class="min-h-screen bg-gradient-to-br from-green-700 via-green-800 to-emerald-950 flex items-center justify-center p-6">
<div class="bg-white rounded-3xl shadow-2xl w-full max-w-md p-8">
  <div class="w-14 h-14 rounded-2xl bg-green-100 text-3xl flex items-center justify-center mb-4">🥗</div>
  <h1 class="text-2xl font-extrabold">NutriDiet Admin</h1>
  <p class="text-sm text-slate-500 mb-6">Masuk untuk mengelola data user aplikasi.</p>
  @if(session('error'))<div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">{{ session('error') }}</div>@endif
  <form method="POST" action="/admin/login" class="space-y-4">@csrf
    <div><label class="text-sm font-semibold">Email</label>
      <input name="email" type="email" required value="{{ old('email','admin@nutridiet.id') }}" class="mt-1 w-full border rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-green-500 outline-none"></div>
    <div><label class="text-sm font-semibold">Password</label>
      <input name="password" type="password" required placeholder="••••••••" class="mt-1 w-full border rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-green-500 outline-none"></div>
    <label class="flex items-center gap-2 text-sm text-slate-600"><input type="checkbox" name="remember" value="1" class="accent-green-600"> Ingat saya</label>
    <button class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-xl">Masuk Dashboard →</button>
  </form>
  <div class="mt-6 text-xs bg-slate-50 border rounded-xl p-3 text-slate-600">Demo: <b>admin@nutridiet.id</b> / <b>admin123</b><br>User demo password: <b>password123</b></div>
</div>
</body>
</html>
