@extends('layouts.admin')
@section('title', isset($user) ? 'Edit User' : 'Tambah User')
@section('subtitle', 'Kelola data user NutriDiet')

@section('content')
<div class="bg-white rounded-2xl border shadow-sm p-6 max-w-2xl">
<form method="POST" action="{{ isset($user) ? "/admin/users/{$user->id}" : '/admin/users' }}" class="grid grid-cols-2 gap-4">@csrf @if(isset($user)) @method('PUT') @endif
  <div class="col-span-2 sm:col-span-1"><label class="text-sm font-bold">Nama</label><input name="name" required value="{{ old('name', $user->name ?? '') }}" class="mt-1 w-full border rounded-xl px-4 py-2.5 text-sm"></div>
  <div class="col-span-2 sm:col-span-1"><label class="text-sm font-bold">Email</label><input name="email" type="email" required value="{{ old('email', $user->email ?? '') }}" class="mt-1 w-full border rounded-xl px-4 py-2.5 text-sm"></div>
  <div><label class="text-sm font-bold">No. HP</label><input name="phone" value="{{ old('phone', $user->phone ?? '') }}" class="mt-1 w-full border rounded-xl px-4 py-2.5 text-sm"></div>
  <div><label class="text-sm font-bold">Password {{ isset($user) ? '(kosongkan jika tidak diubah)' : '' }}</label><input name="password" type="password" {{ isset($user) ? '' : 'required' }} class="mt-1 w-full border rounded-xl px-4 py-2.5 text-sm"></div>
  <div><label class="text-sm font-bold">Goal</label><select name="goal" class="mt-1 w-full border rounded-xl px-4 py-2.5 text-sm">
    <option value="weight_loss" @selected(old('goal', $user->goal ?? '')==='weight_loss')>Weight Loss</option>
    <option value="maintenance" @selected(old('goal', $user->goal ?? '')==='maintenance')>Maintenance</option>
    <option value="healthy_bulk" @selected(old('goal', $user->goal ?? '')==='healthy_bulk')>Healthy Bulk</option></select></div>
  <div><label class="text-sm font-bold">Status</label><select name="status" class="mt-1 w-full border rounded-xl px-4 py-2.5 text-sm">
    <option value="active" @selected(old('status', $user->status ?? 'active')==='active')>Active</option>
    <option value="inactive" @selected(old('status', $user->status ?? '')==='inactive')>Inactive</option></select></div>
  <div><label class="text-sm font-bold">Tinggi (cm)</label><input name="height_cm" type="number" value="{{ old('height_cm', $user->height_cm ?? 165) }}" class="mt-1 w-full border rounded-xl px-4 py-2.5 text-sm"></div>
  <div><label class="text-sm font-bold">Berat (kg)</label><input name="weight_kg" type="number" step="0.1" value="{{ old('weight_kg', $user->weight_kg ?? 60) }}" class="mt-1 w-full border rounded-xl px-4 py-2.5 text-sm"></div>
  <div><label class="text-sm font-bold">Target Kalori</label><input name="target_calories" type="number" value="{{ old('target_calories', $user->target_calories ?? 1850) }}" class="mt-1 w-full border rounded-xl px-4 py-2.5 text-sm"></div>
  <div><label class="text-sm font-bold">Target Air (ml)</label><input name="water_target" type="number" value="{{ old('water_target', $user->water_target ?? 2500) }}" class="mt-1 w-full border rounded-xl px-4 py-2.5 text-sm"></div>
  @if($errors->any())<div class="col-span-2 bg-red-50 border border-red-200 text-red-600 text-sm rounded-xl p-3">@foreach($errors->all() as $e)<p>• {{ $e }}</p>@endforeach</div>@endif
  <div class="col-span-2 flex gap-2"><button class="bg-green-600 text-white font-bold px-6 py-2.5 rounded-xl text-sm">Simpan</button>
  <a href="/admin/users" class="bg-slate-100 font-bold px-6 py-2.5 rounded-xl text-sm">Batal</a></div>
</form>
</div>
@endsection
