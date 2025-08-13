@extends('layouts.adminlte')

@section('content')
<div class="container">
    <h1>Dashboard Kasir</h1>
    <p>Selamat datang, {{ auth()->user()->name }}!</p>
    
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Transaksi Baru</h5>
                    <a href="{{ route('transaksi.create') }}" class="btn btn-primary">Buat Transaksi</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Riwayat Transaksi</h5>
                    <a href="{{ route('transaksi.index') }}" class="btn btn-primary">Lihat Riwayat</a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Daftar Produk</h5>
                    <a href="{{ route('produk.index') }}" class="btn btn-primary">Lihat Produk</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection