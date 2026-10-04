@extends('layouts.admin')
@section('title','Data Nutrisi')
@section('subtitle','Log makanan & minuman semua user')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
<table class="w-full text-sm">
<thead><tr class="bg-slate-50 text-left text-xs uppercase text-slate-400"><th class="px-5 py-3">Tanggal</th><th class="px-5 py-3">User</th><th class="px-5 py-3">Makanan</th><th class="px-5 py-3">Kalori</th><th class="px-5 py-3">Makro (P/K/L)</th><th class="px-5 py-3">Air</th></tr></thead>
<tbody>@forelse($logs as $l)<tr class="border-t hover:bg-slate-50">
<td class="px-5 py-3 whitespace-nowrap">{{ $l->log_date->format('d M Y') }}<br><span class="text-xs text-slate-400">{{ $l->meal_type }}</span></td>
<td class="px-5 py-3 font-bold">{{ $l->user->name ?? '-' }}</td>
<td class="px-5 py-3">{{ $l->food_name }}</td>
<td class="px-5 py-3 font-extrabold text-orange-500">{{ $l->calories }} kkal</td>
<td class="px-5 py-3 text-xs">{{ $l->protein_g }}g / {{ $l->carbs_g }}g / {{ $l->fat_g }}g</td>
<td class="px-5 py-3 text-sky-600 font-bold">💧 {{ $l->water_ml }} ml</td></tr>
@empty<tr><td colspan="6" class="p-10 text-center text-slate-400">Belum ada data.</td></tr>@endforelse</tbody>
</table><div class="p-4">{{ $logs->links() }}</div></div>
@endsection
