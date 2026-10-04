@extends('layouts.admin')
@section('title','Dashboard')
@section('subtitle','Ringkasan semua data user NutriDiet (Android)')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-4 mb-6">
  <div class="bg-white rounded-2xl p-5 shadow-sm border"><p class="text-xs font-bold text-slate-400 uppercase">Total User</p><p class="text-3xl font-extrabold mt-1">{{ $totalUsers }}</p><p class="text-xs text-slate-500 mt-1">{{ $activeUsers }} aktif</p></div>
  <div class="bg-white rounded-2xl p-5 shadow-sm border"><p class="text-xs font-bold text-slate-400 uppercase">Kalori Hari Ini</p><p class="text-3xl font-extrabold mt-1 text-orange-500">{{ number_format($todayCalories) }}</p><p class="text-xs text-slate-500 mt-1">kkal tercatat</p></div>
  <div class="bg-white rounded-2xl p-5 shadow-sm border"><p class="text-xs font-bold text-slate-400 uppercase">Air Hari Ini</p><p class="text-3xl font-extrabold mt-1 text-sky-500">{{ number_format($todayWater) }}<span class="text-sm font-bold"> ml</span></p><p class="text-xs text-slate-500 mt-1">konsumsi air</p></div>
  <div class="bg-white rounded-2xl p-5 shadow-sm border"><p class="text-xs font-bold text-slate-400 uppercase">Aktivitas Hari Ini</p><p class="text-3xl font-extrabold mt-1 text-violet-500">{{ $todayActivities }}</p><p class="text-xs text-slate-500 mt-1">sesi olahraga</p></div>
  <div class="bg-gradient-to-br from-green-600 to-emerald-700 rounded-2xl p-5 shadow-sm text-white"><p class="text-xs font-bold text-green-200 uppercase">Distribusi Goal</p><p class="text-sm mt-2">🔻 Loss: <b>{{ $goalStats['weight_loss'] ?? 0 }}</b></p><p class="text-sm">⚖️ Maint: <b>{{ $goalStats['maintenance'] ?? 0 }}</b></p><p class="text-sm">💪 Bulk: <b>{{ $goalStats['healthy_bulk'] ?? 0 }}</b></p></div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-4 mb-6">
  <div class="xl:col-span-2 bg-white rounded-2xl p-6 shadow-sm border">
    <div class="flex items-center justify-between mb-4"><h2 class="font-extrabold">📈 Kalori Masuk — 7 Hari Terakhir</h2><a href="/admin/nutrition" class="text-xs font-bold text-green-600">Lihat detail →</a></div>
    <canvas id="calChart" height="110"></canvas>
  </div>
  <div class="bg-white rounded-2xl p-6 shadow-sm border">
    <h2 class="font-extrabold mb-4">🎯 Goal User</h2><canvas id="goalChart" height="200"></canvas>
  </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-4">
  <div class="bg-white rounded-2xl p-6 shadow-sm border">
    <div class="flex items-center justify-between mb-4"><h2 class="font-extrabold">🆕 User Terbaru</h2><a href="/admin/users" class="text-xs font-bold text-green-600">Semua →</a></div>
    <div class="space-y-3">
      @foreach($recentUsers as $u)
      <a href="/admin/users/{{ $u->id }}" class="flex items-center gap-3 hover:bg-slate-50 p-2 rounded-xl">
        <div class="w-10 h-10 rounded-full bg-green-100 text-green-700 font-extrabold flex items-center justify-center">{{ strtoupper(substr($u->name,0,1)) }}</div>
        <div class="flex-1 min-w-0"><p class="font-bold text-sm truncate">{{ $u->name }}</p><p class="text-xs text-slate-500 truncate">{{ $u->email }} · {{ $u->goalLabel() }}</p></div>
        <span class="text-[11px] px-2 py-1 rounded-full font-bold {{ $u->status==='active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600' }}">{{ $u->status }}</span>
      </a>
      @endforeach
    </div>
  </div>
  <div class="bg-white rounded-2xl p-6 shadow-sm border">
    <h2 class="font-extrabold mb-4">🍎 Nutrisi Terakhir</h2>
    <div class="space-y-2 text-sm">
      @foreach($recentNutrition as $n)
      <div class="flex justify-between border-b border-dashed pb-2"><span class="truncate mr-2">🍽️ <b>{{ $n->user->name ?? '-' }}</b> — {{ $n->food_name }}</span><b class="text-orange-500 whitespace-nowrap">{{ $n->calories }} kkal</b></div>
      @endforeach
    </div>
  </div>
  <div class="bg-white rounded-2xl p-6 shadow-sm border">
    <h2 class="font-extrabold mb-4">🏃 Aktivitas Terakhir</h2>
    <div class="space-y-2 text-sm">
      @foreach($recentActivities as $a)
      <div class="flex justify-between border-b border-dashed pb-2"><span class="truncate mr-2">🔥 <b>{{ $a->user->name ?? '-' }}</b> — {{ $a->activity_type }}</span><b class="text-violet-500 whitespace-nowrap">{{ $a->calories_burned }} kkal</b></div>
      @endforeach
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
new Chart(document.getElementById('calChart'), { type:'bar',
  data:{ labels: @json($labels), datasets:[{ data: @json($values), backgroundColor:'#22c55e', borderRadius:8 }]},
  options:{ plugins:{legend:{display:false}}, scales:{y:{beginAtZero:true}} } });
new Chart(document.getElementById('goalChart'), { type:'doughnut',
  data:{ labels:['Weight Loss','Maintenance','Healthy Bulk'],
    datasets:[{ data:[{{ $goalStats['weight_loss'] ?? 0 }},{{ $goalStats['maintenance'] ?? 0 }},{{ $goalStats['healthy_bulk'] ?? 0 }}], backgroundColor:['#ef4444','#3b82f6','#22c55e'] }]},
  options:{ cutout:'65%' } });
</script>
@endsection
