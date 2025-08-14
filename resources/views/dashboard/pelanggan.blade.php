@extends('layouts.adminlte')

@section('content')
<div class="container-fluid">
    <!-- Welcome Banner -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="welcome-card p-4 rounded shadow" style="background: #001f3f; background: linear-gradient(to right, #001f3f, #003366);">
                <h2 class="text-white text-bold">Selamat datang, {{ auth()->user()->name }}!</h2>
                <p class="text-light mb-0">Hari ini: {{ now()->format('l, d F Y') }}</p>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-md-4 mb-4">
            <div class="card shadow h-100 py-2 product-card" style="border-left: 4px solid #f6c23e;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="h6 font-weight-bold text-warning mb-1">
                                Total Produk Tersedia</div>
                            <div class="h3 mb-0 font-weight-bold text-dark">{{ $totalProduk }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-box-open fa-3x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <a href="{{ route('profile.edit') }}" class="text-decoration-none">
                <div class="card shadow h-100 py-2 product-card" style="border-left: 4px solid #f6c23e;">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="h6 font-weight-bold text-warning mb-1">
                                    Profil Saya</div>
                                <div class="h5 mb-0 font-weight-bold text-dark">
                                    {{ auth()->user()->email }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user-circle fa-3x text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        
        <div class="col-md-4 mb-4">
            <a href="{{ route('produk.index') }}" class="text-decoration-none">
                <div class="card shadow h-100 py-2 product-card" style="border-left: 4px solid #f6c23e;">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="h6 font-weight-bold text-warning mb-1">
                                    Lihat Semua Produk</div>
                                <div class="h5 mb-0 font-weight-bold text-dark">
                                    {{ $totalProduk }} produk tersedia
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-store fa-3x text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Produk Terbaru Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between" style="background: #001f3f; background: linear-gradient(to right, #001f3f, #003366);">
                    <h6 class="m-0 font-weight-bold text-white">Produk Terbaru</h6>
                    <a href="{{ route('produk.index') }}" class="btn btn-sm btn-light ml-auto">Lihat Semua</a>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($produkTerbaru as $produk)
                        <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                            <a href="{{ route('produk.index') }}" class="card-link">
                                <div class="card h-100 product-card">
                                    <div class="card-body d-flex flex-column">
                                        <!-- Badge Kategori -->
                                        <div class="d-flex justify-content-end mb-2">
                                            <span class="badge bg-primary text-white text-truncate">
                                                {{ $produk->kategori->nama_kategori ?? 'Tanpa Kategori' }}
                                            </span>
                                        </div>
                                        
                                        <!-- Nama Produk -->
                                        <h5 class="card-title mb-2 text-dark">
                                            {{ $produk->nama_produk }}
                                        </h5>
                                        
                                        <!-- Harga dan Stok -->
                                        <div class="mt-auto pt-2">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="h5 mb-0 text-primary font-weight-bold">Rp {{ number_format($produk->harga_produk, 0, ',', '.') }}</span>
                                                <span class="badge bg-success">{{ $produk->stok_produk }} stok</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    
    .product-card {
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        border-radius: 8px;
        border: 1px solid rgba(0,0,0,0.1);
        overflow: hidden;
        transform-origin: center;
    }
    .product-card:hover {
        transform: scale(0.97);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    .card-body {
        padding: 1.25rem;
    }
    .badge {
        font-weight: 500;
        padding: 0.35em 0.65em;
        font-size: 0.75rem;
    }
    @media (max-width: 767.98px) {
        .col-md-6 {
            padding-left: 8px;
            padding-right: 8px;
        }
        .card-body {
            padding: 1rem;
        }
    }
    @media (min-width: 1200px) {
        .col-xl-3 {
            flex: 0 0 25%;
            max-width: 25%;
        }
    }
</style>
@endsection