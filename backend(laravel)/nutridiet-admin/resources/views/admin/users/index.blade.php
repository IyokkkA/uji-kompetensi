@extends('layouts.admin')
@section('title','Data User')
@section('subtitle','Semua user yang terdaftar dari aplikasi Android')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border p-5 mb-4">
  <form class="flex flex-wrap gap-2" method="GET">
    <input name="q" value="{{ $q }}" placeholder="🔍 Cari nama / email / HP..." class="border rounded-xl px-4 py-2 text-sm flex-1 min-w-[220px] outline-none focus:ring-2 focus:ring-green-500">
    <select name="goal" class="border rounded-xl px-3 py-2 text-sm">
      <option value="">Semua Goal</option>
      <option value="weight_loss" @selected($goal==='weight_loss')>Weight Loss</option>
      <option value="maintenance" @selected($goal==='maintenance')>Maintenance</option>
      <option value="healthy_bulk" @selected($goal==='healthy_bulk')>Healthy Bulk</option>
    </select>
    <select name="status" class="border rounded-xl px-3 py-2 text-sm">
      <option value="">Semua Status</option>
      <option value="active" @selected($status==='active')>Active</option>
      <option value="inactive" @selected($status==='inactive')>Inactive</option>
    </select>
    <button class="bg-green-600 text-white text-sm font-bold px-5 py-2 rounded-xl">Filter</button>
    <a href="/admin/users/create" class="bg-slate-900 text-white text-sm font-bold px-5 py-2 rounded-xl">+ Tambah User</a>
  </form>
</div>

<div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
<table class="w-full text-sm">
  <thead><tr class="bg-slate-50 text-left text-xs uppercase text-slate-400">
    <th class="px-5 py-3">User</th><th class="px-5 py-3">Goal</th><th class="px-5 py-3">Target</th><th class="px-5 py-3">Log</th><th class="px-5 py-3">Status</th><th class="px-5 py-3 text-right">Aksi</th>
  </tr></thead>
  <tbody>
  @forelse($users as $u)
    <tr class="border-t hover:bg-slate-50">
      <td class="px-5 py-3"><div class="flex items-center gap-3">
        <div class="w-9 h-9 rounded-full bg-green-100 text-green-700 font-extrabold flex items-center justify-center">{{ strtoupper(substr($u->name,0,1)) }}</div>
        <div><p class="font-bold">{{ $u->name }}</p><p class="text-xs text-slate-500">{{ $u->email }} · {{ $u->phone ?? '-' }}</p></div>
      </div></td>
      <td class="px-5 py-3"><span class="text-xs font-bold px-2.5 py-1 rounded-full {{ $u->goal==='weight_loss'?'bg-red-100 text-red-600':($u->goal==='healthy_bulk'?'bg-green-100 text-green-700':'bg-blue-100 text-blue-700') }}">{{ $u->goalLabel() }}</span></td>
      <td class="px-5 py-3 text-xs">{{ $u->target_calories }} kkal<br>{{ $u->water_target }} ml</td>
      <td class="px-5 py-3 text-xs">🍎 {{ $u->nutrition_logs_count }} · 🏃 {{ $u->activity_logs_count }}</td>
      <td class="px-5 py-3"><span class="text-xs font-bold px-2.5 py-1 rounded-full {{ $u->status==='active'?'bg-green-100 text-green-700':'bg-red-100 text-red-600' }}">{{ $u->status }}</span></td>
      <td class="px-5 py-3 text-right whitespace-nowrap">
        <a href="/admin/users/{{ $u->id }}" class="text-green-600 font-bold text-xs">Detail</a> ·
        <a href="/admin/users/{{ $u->id }}/edit" class="text-blue-600 font-bold text-xs">Edit</a>
      </td>
    </tr>
  @empty
    <tr><td colspan="6" class="px-5 py-10 text-center text-slate-400">Tidak ada user ditemukan.</td></tr>
  @endforelse
  </tbody>
</table>
<div class="p-4">{{ $users->links() }}</div>
</div>
@endsection
