@extends('layouts.adminlte')

@section('content')
<div class="container">
    <h1>Dashboard Pelanggan</h1>
    <p>Selamat datang, {{ auth()->user()->name }}!</p>
    <ul>
        <li><a href="{{ route('produk.index') }}">Lihat Produk</a></li>
        <li><a href="{{ route('transaksi.index') }}">Riwayat Pembelian</a></li>
    </ul>
</div>
@endsection
