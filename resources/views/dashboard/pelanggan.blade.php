@extends('layouts.adminlte')

@section('content')
<div class="container-fluid">
    <!-- Welcome Banner -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="p-4 rounded shadow" style="background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);">
                <h2 class="text-white text-bold">Selamat datang, {{ auth()->user()->name }}!</h2>
                <p class="text-light mb-0">Temukan produk terbaik untuk kebutuhan Anda</p>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-md-4 mb-4">
            <div class="card shadow h-100 py-2 border-left-primary">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="h6 font-weight-bold text-primary mb-1">
                                Total Produk Tersedia</div>
                            <div class="h3 mb-0 font-weight-bold text-dark">{{ $totalProduk }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-box-open fa-3x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <a href="{{ route('profil.edit') }}" class="text-decoration-none">
                <div class="card shadow h-100 py-2 border-left-success">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="h6 font-weight-bold text-success mb-1">
                                    Profil Saya</div>
                                <div class="h5 mb-0 font-weight-bold text-dark">
                                    {{ auth()->user()->email }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user-circle fa-3x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        
        <div class="col-md-4 mb-4">
            <a href="{{ route('produk.index') }}" class="text-decoration-none">
                <div class="card shadow h-100 py-2 border-left-info">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="h6 font-weight-bold text-info mb-1">
                                    Lihat Semua Produk</div>
                                <div class="h5 mb-0 font-weight-bold text-dark">
                                    {{ $totalProduk }} produk tersedia
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-store fa-3x text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Produk Terbaru -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between" style="background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);">
                    <h6 class="m-0 font-weight-bold text-white">Produk Terbaru</h6>
                    <a href="{{ route('produk.index') }}" class="btn btn-sm btn-light">Lihat Semua</a>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($produkTerbaru as $produk)
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="card h-100 product-card">
                                <div class="position-relative">
                                    <img class="card-img-top" src="{{ $produk->gambar ? asset('storage/'.$produk->gambar) : asset('img/default-product.png') }}" alt="{{ $produk->nama_produk }}">
                                    <div class="position-absolute top-0 end-0 bg-primary text-white px-2 py-1 small">
                                        {{ $produk->kategori->nama_kategori }}
                                    </div>
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">{{ $produk->nama_produk }}</h5>
                                    <p class="card-text text-muted">{{ Str::limit($produk->deskripsi, 50) }}</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="h5 mb-0 text-primary">Rp {{ number_format($produk->harga_produk,0,',','.') }}</span>
                                        <span class="badge bg-success">Stok: {{ $produk->stok_produk }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Produk Populer -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between" style="background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);">
                    <h6 class="m-0 font-weight-bold text-white">Produk Populer</h6>
                    <a href="{{ route('produk.index') }}" class="btn btn-sm btn-light">Lihat Semua</a>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($produkPopuler as $produk)
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="card h-100 product-card">
                                <div class="position-relative">
                                    <img class="card-img-top" src="{{ $produk->gambar ? asset('storage/'.$produk->gambar) : asset('img/default-product.png') }}" alt="{{ $produk->nama_produk }}">
                                    <div class="position-absolute top-0 end-0 bg-danger text-white px-2 py-1 small">
                                        Terjual: {{ $produk->terjual }}
                                    </div>
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">{{ $produk->nama_produk }}</h5>
                                    <p class="card-text text-muted">{{ Str::limit($produk->deskripsi, 50) }}</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="h5 mb-0 text-primary">Rp {{ number_format($produk->harga_produk,0,',','.') }}</span>
                                        <span class="badge bg-success">Stok: {{ $produk->stok_produk }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Kategori Produk -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3" style="background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);">
                    <h6 class="m-0 font-weight-bold text-white">Kategori Produk</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($kategoriList as $kategori)
                        <div class="col-md-3 col-6 mb-3">
                            <a href="{{ route('produk.index', ['kategori' => $kategori->id]) }}" class="text-decoration-none">
                                <div class="card bg-light h-100">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">{{ $kategori->nama_kategori }}</h5>
                                        <p class="card-text">{{ $kategori->produk_count }} produk</p>
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
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 10px;
        overflow: hidden;
    }
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    .card-img-top {
        height: 180px;
        object-fit: cover;
    }
    .gradient-bg {
        background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
    }
</style>
@endsection