@extends('layouts.adminlte')

@section('content')
<div class="container">
    <h1>Dashboard Admin</h1>
    <p>Selamat datang, {{ auth()->user()->name }}!</p>
    <ul>
        <li><a href="{{ route('produk.index') }}">Kelola Produk</a></li>
        <li><a href="{{ route('kategori.index') }}">Kelola Kategori</a></li>
        <li><a href="{{ route('user-management.index') }}">Manajemen User</a></li>
        <li><a href="{{ route('aktivitas.index') }}">Log Aktivitas</a></li>
    </ul>
</div>
@endsection
