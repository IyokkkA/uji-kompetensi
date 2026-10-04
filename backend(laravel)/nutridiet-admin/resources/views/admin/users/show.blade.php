@extends('layouts.admin')
@section('title', $user->name)
@section('subtitle', $user->email . ' · ' . $user->goalLabel())

@section('content')
<div class="grid grid-cols-1 xl:grid-cols-3 gap-4 mb-4">
  <div class="bg-white rounded-2xl border p-6 shadow-sm">
    <div class="flex items-center gap-4 mb-4">
      <div class="w-16 h-16 rounded-2xl bg-green-600 text-white text-2xl font-extrabold flex items-center justify-center">{{ strtoupper(substr($user->name,0,1)) }}</div>
      <div><p class="font-extrabold text-lg">{{ $user->name }}</p>
        <span class="text-xs font-bold px-2.5 py-1 rounded-full {{ $user->status==='active'?'bg-green-100 text-green-700':'bg-red-100 text-red-600' }}">{{ $user->status }}</span>
        <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-slate-100">{{ $user->goalLabel() }}</span></div>
    </div>
    <div class="text-sm space-y-2">
      <p>📧 {{ $user->email }}</p><p>📱 {{ $user->phone ?? '-' }}</p>
      <p>📏 {{ $user->height_cm ?? '-' }} cm · ⚖️ {{ $user->weight_kg ?? '-' }} kg</p>
      <p>🎯 Target: <b>{{ $user->target_calories }} kkal</b> · 💧 <b>{{ $user->water_target }} ml</b></p>
      <p class="text-xs text-slate-400">Bergabung {{ $user->created_at->format('d M Y') }}</p>
    </div>
    <div class="flex gap-2 mt-4">
      <a href="/admin/users/{{ $user->id }}/edit" class="flex-1 text-center bg-blue-600 text-white text-sm font-bold py-2 rounded-xl">Edit</a>
      <form action="/admin/users/{{ $user->id }}/toggle-status" method="POST" class="flex-1">@csrf
        <button class="w-full text-sm font-bold py-2 rounded-xl {{ $user->status==='active'?'bg-amber-100 text-amber-700':'bg-green-600 text-white' }}">{{ $user->status==='active'?'Nonaktifkan':'Aktifkan' }}</button>
      </form>
      <form action="/admin/users/{{ $user->id }}" method="POST" onsubmit="return confirm('Hapus user ini?')">@csrf @method('DELETE')
        <button class="bg-red-100 text-red-600 text-sm font-bold px-4 py-2 rounded-xl">Hapus</button>
      </form>
    </div>
  </div>
  <div class="xl:col-span-2 grid grid-cols-3 gap-4">
    <div class="bg-orange-500 text-white rounded-2xl p-5"><p class="text-xs font-bold opacity-80">TOTAL KALORI</p><p class="text-2xl font-extrabold">{{ number_format($totalCalories) }}</p><p class="text-xs opacity-80">kkal · {{ $user->nutrition_logs_count }} log</p></div>
    <div class="bg-sky-500 text-white rounded-2xl p-5"><p class="text-xs font-bold opacity-80">TOTAL AIR</p><p class="text-2xl font-extrabold">{{ number_format($totalWater) }}</p><p class="text-xs opacity-80">ml</p></div>
    <div class="bg-violet-500 text-white rounded-2xl p-5"><p class="text-xs font-bold opacity-80">KALORI TERBAKAR</p><p class="text-2xl font-extrabold">{{ number_format($totalBurned) }}</p><p class="text-xs opacity-80">kkal · {{ $user->activity_logs_count }} sesi</p></div>
    <div class="col-span-3 bg-white rounded-2xl border p-5 shadow-sm">
      <h3 class="font-extrabold mb-3">🍎 Riwayat Nutrisi</h3>
      <div class="space-y-2 text-sm max-h-64 overflow-auto">
        @forelse($nutrition as $n)<div class="flex justify-between border-b border-dashed pb-2"><span>{{ $n->log_date->format('d M') }} · {{ $n->meal_type }} — <b>{{ $n->food_name }}</b> (💧{{ $n->water_ml }}ml)</span><b class="text-orange-500">{{ $n->calories }} kkal</b></div>
        @empty<p class="text-slate-400">Belum ada data.</p>@endforelse
      </div>
    </div>
    <div class="col-span-3 bg-white rounded-2xl border p-5 shadow-sm">
      <h3 class="font-extrabold mb-3">🏃 Riwayat Aktivitas</h3>
      <div class="space-y-2 text-sm max-h-64 overflow-auto">
        @forelse($activities as $a)<div class="flex justify-between border-b border-dashed pb-2"><span>{{ $a->log_date->format('d M') }} — <b>{{ $a->activity_type }}</b> ({{ $a->duration_min }} mnt · {{ $a->steps }} langkah)</span><b class="text-violet-500">{{ $a->calories_burned }} kkal</b></div>
        @empty<p class="text-slate-400">Belum ada data.</p>@endforelse
      </div>
    </div>
  </div>
</div>
<a href="/admin/users" class="text-sm font-bold text-green-600">← Kembali ke Data User</a>
@endsection
