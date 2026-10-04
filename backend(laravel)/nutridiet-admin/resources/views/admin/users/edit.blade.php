@extends('layouts.admin')
@section('title','Edit User')
@section('subtitle', $user->email)
@section('content') @include('admin.users.form') @endsection
