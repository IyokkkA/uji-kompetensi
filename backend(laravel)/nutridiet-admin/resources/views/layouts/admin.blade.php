<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'Dashboard') · NutriDiet Admin</title>
<script src="https://cdn.tailwindcss.com"></script>
<script>
tailwind.config = { theme: { extend: { colors: { primary: '#16a34a', dark: '#14532d' } } } }
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<style>body{font-family:'Inter',ui-sans-serif,system-ui,sans-serif}</style>
</head>
<body class="bg-slate-100 text-slate-800">
<div class="flex min-h-screen">
  <!-- Sidebar -->
  <aside class="w-64 bg-gradient-to-b from-green-700 to-green-900 text-white flex flex-col fixed inset-y-0">
    <div class="px-6 py-6 flex items-center gap-3">
      <div class="w-11 h-11 rounded-2xl bg-white/15 flex items-center justify-center text-2xl">🥗</div>
      <div>
        <p class="font-extrabold text-lg leading-none">NutriDiet</p>
        <p class="text-green-200 text-xs mt-1">Admin Panel</p>
      </div>
    </div>
    <nav class="px-3 space-y-1 text-sm font-medium flex-1">
      @php $seg = request()->path(); @endphp
      <a href="/admin" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ $seg==='admin' ? 'bg-white text-green-800' : 'hover:bg-white/10 text-green-50' }}">📊 Dashboard</a>
      <a href="/admin/users" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ str_starts_with($seg,'admin/users') ? 'bg-white text-green-800' : 'hover:bg-white/10 text-green-50' }}">👥 Data User</a>
      <a href="/admin/nutrition" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ $seg==='admin/nutrition' ? 'bg-white text-green-800' : 'hover:bg-white/10 text-green-50' }}">🍎 Nutrisi</a>
      <a href="/admin/activities" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ $seg==='admin/activities' ? 'bg-white text-green-800' : 'hover:bg-white/10 text-green-50' }}">🏃 Aktivitas</a>
      <div class="pt-4 px-4 text-[11px] uppercase tracking-wider text-green-300">Android API</div>
      <div class="mx-4 p-3 rounded-xl bg-black/20 text-xs text-green-100 font-mono break-all">POST /api/register<br>POST /api/login</div>
    </nav>
    <div class="p-4">
      <div class="bg-white/10 rounded-2xl p-4">
        <p class="font-semibold text-sm">{{ auth()->user()->name ?? 'Admin' }}</p>
        <p class="text-green-200 text-xs">{{ auth()->user()->email ?? '' }}</p>
        <form action="/admin/logout" method="POST" class="mt-3">@csrf
          <button class="w-full bg-white text-green-800 text-sm font-bold py-2 rounded-xl hover:bg-green-50">Keluar</button>
        </form>
      </div>
    </div>
  </aside>

  <!-- Main -->
  <div class="flex-1 ml-64">
    <header class="bg-white/80 backdrop-blur border-b sticky top-0 z-10">
      <div class="px-8 py-4 flex items-center justify-between">
        <div>
          <h1 class="text-xl font-extrabold">@yield('title', 'Dashboard')</h1>
          <p class="text-sm text-slate-500">@yield('subtitle', 'Kelola semua data user aplikasi NutriDiet')</p>
        </div>
        <div class="flex items-center gap-3">
          <span class="text-xs bg-green-100 text-green-700 font-bold px-3 py-1.5 rounded-full">● Server Online</span>
          <span class="text-xs bg-slate-100 px-3 py-1.5 rounded-full">{{ now()->format('d M Y') }}</span>
        </div>
      </div>
    </header>
    <main class="p-8">
      @if(session('success'))<div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl text-sm">✅ {{ session('success') }}</div>@endif
      @if(session('error'))<div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">⛔ {{ session('error') }}</div>@endif
      @yield('content')
    </main>
  </div>
</div>
@yield('scripts')
</body>
</html>
