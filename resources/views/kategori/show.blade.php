@extends('layouts.adminlte')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header" style="background: linear-gradient(to right, #001f3f, #003366);">
            <h3 class="card-title text-light">
                Produk dalam Kategori: <strong>{{ $kategori->nama_kategori }}</strong>
            </h3>
            <div class="card-tools">
                <a href="{{ route('kategori.index') }}" class="btn btn-light">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        <div class="card-body">
            @if($kategori->produk->isEmpty())
                <div class="text-center text-muted">
                    <i class="fas fa-box-open fa-3x mb-3"></i>
                    <p>Tidak ada produk dalam kategori ini.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-striped text-center">
                        <thead class="bg-secondary text-white">
                            <tr>
                                <th>No</th>
                                <th>Nama Produk</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Gambar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kategori->produk as $produk)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $produk->nama_produk }}</td>
                                <td>{{ $produk->harga_produk }}</td>
                                <td>
                                    <span class="">{{ $produk->stok_produk }}</span>
                                </td>
                                <td>
                                    @if($produk->gambar_url)
                                        <img src="{{ $produk->gambar_url }}" alt="{{ $produk->nama_produk }}" width="50" class="img-thumbnail">
                                    @else
                                        <span class="text-muted">Tidak ada gambar</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .card-title strong {
        color: #fff;
    }
    .badge {
        font-size: 0.9em;
    }
    .img-thumbnail {
        border-radius: 4px;
    }
</style>
@endsection