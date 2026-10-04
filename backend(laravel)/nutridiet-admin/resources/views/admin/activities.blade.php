@extends('layouts.admin')
@section('title','Data Aktivitas')
@section('subtitle','Log olahraga semua user')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
<table class="w-full text-sm">
<thead><tr class="bg-slate-50 text-left text-xs uppercase text-slate-400"><th class="px-5 py-3">Tanggal</th><th class="px-5 py-3">User</th><th class="px-5 py-3">Aktivitas</th><th class="px-5 py-3">Durasi</th><th class="px-5 py-3">Terbakar</th><th class="px-5 py-3">Langkah</th></tr></thead>
<tbody>@forelse($logs as $l)<tr class="border-t hover:bg-slate-50">
<td class="px-5 py-3 whitespace-nowrap">{{ $l->log_date->format('d M Y') }}</td>
<td class="px-5 py-3 font-bold">{{ $l->user->name ?? '-' }}</td>
<td class="px-5 py-3">🏃 {{ $l->activity_type }}</td>
<td class="px-5 py-3">{{ $l->duration_min }} mnt</td>
<td class="px-5 py-3 font-extrabold text-violet-500">{{ $l->calories_burned }} kkal</td>
<td class="px-5 py-3">{{ number_format($l->steps) }}</td></tr>
@empty<tr><td colspan="6" class="p-10 text-center text-slate-400">Belum ada data.</td></tr>@endforelse</tbody>
</table><div class="p-4">{{ $logs->links() }}</div></div>
@endsection
