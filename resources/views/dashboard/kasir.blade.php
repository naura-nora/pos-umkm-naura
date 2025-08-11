@extends('layouts.adminlte')

@section('content')
<div class="container">
    <h1>Dashboard Kasir</h1>
    <p>Selamat datang, {{ auth()->user()->name }}!</p>
    <ul>
        <li><a href="{{ route('transaksi.create') }}">Buat Transaksi Baru</a></li>
        <li><a href="{{ route('transaksi.index') }}">Riwayat Transaksi</a></li>
        <li><a href="{{ route('produk.index') }}">Lihat Produk</a></li>
    </ul>
</div>
@endsection
