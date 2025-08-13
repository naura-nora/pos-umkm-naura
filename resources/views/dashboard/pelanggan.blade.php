@extends('layouts.adminlte')

@section('content')
<div class="container">
    <h1>Dashboard Pelanggan</h1>
    <p>Selamat datang, {{ auth()->user()->name }}!</p>
    
    <div class="row">
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