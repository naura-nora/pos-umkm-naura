@extends('layouts.adminlte')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Detail Produk {{ $produk->id }}</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header" style="background: #001f3f; background: linear-gradient(to right, #001f3f, #003366);">
                            <h3 class="card-title text-white">Informasi Produk</h3>
                            <div class="card-tools">
                                <a href="{{ route('produk.index') }}" class="btn btn-sm btn-default">
                                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- Card Data Produk -->
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <div class="card h-100">
                                        <div class="card-header bg-warning">
                                            <h3 class="card-title">Data Produk</h3>
                                        </div>
                                        <div class="card-body p-0">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th width="30%">ID Produk</th>
                                                    <td>{{ $produk->id }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Nama Produk</th>
                                                    <td>{{ $produk->nama_produk }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Harga</th>
                                                    <td>Rp {{ number_format($produk->harga_produk, 0, ',', '.') }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Stok</th>
                                                    <td>{{ $produk->stok_produk }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Kategori</th>
                                                    <td>{{ $produk->kategori->nama_kategori ?? 'Tidak ada kategori' }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Card Gambar Produk -->
                                <div class="col-md-6">
                                    <div class="card h-100">
                                        <div class="card-header bg-warning">
                                            <h3 class="card-title">Gambar Produk</h3>
                                        </div>
                                        <div class="card-body text-center d-flex align-items-center justify-content-center">
                                            @if($produk->gambar_produk)
                                                <img src="{{ asset('adminlte/img/' . $produk->gambar_produk) }}" 
                                                     alt="{{ $produk->nama_produk }}" 
                                                     class="img-fluid rounded" 
                                                     style="max-height: 300px;">
                                            @else
                                                <div class="text-center py-3">
                                                    <i class="fas fa-image fa-5x text-muted"></i>
                                                    <p class="mt-3 text-muted">Tidak ada gambar produk</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <small class="text-muted">
                                Dibuat pada: {{ $produk->created_at->format('d M Y') }} | 
                                Terakhir diupdate: {{ $produk->updated_at->format('d M Y') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('css')
<style>
    .card-footer {
        background-color: #f8f9fa;
    }
    .content-wrapper {
        min-height: calc(100vh - 100px) !important;
    }
</style>
@endsection