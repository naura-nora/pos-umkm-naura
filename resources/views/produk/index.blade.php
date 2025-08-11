@extends('layouts.adminlte')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header bg-primary">
            <h1 class="card-title">Daftar Produk</h1>
            <div class="card-tools">
                @if(auth()->user()->hasAnyRole(['admin']))
                <a href="{{ route('produk.create') }}" class="btn btn-light">
                    <i class="fas fa-plus"></i> Tambah Produk
                </a>
                @endif
            </div>
        </div>

        <!-- serching -->
        <div class="card-tools mt-3 container-fluid">
                <form method="GET" action="{{ route('produk.index') }}" id="searchForm">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" 
                               placeholder="Cari disini" 
                               value="{{ request('search') }}"
                               id="searchInput">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-default">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
        <!-- end serching -->


        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Produk</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Kategori</th>
                        <th>Gambar</th>
                        @if(auth()->user()->hasAnyRole(['admin']))
                            <th>Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($produks as $produk)
                    <tr>
                        <td>{{ ($produks->currentPage() - 1) * $produks->perPage() + $loop->iteration }}</td>
                        <td>{{ $produk->nama_produk }}</td>
                        <td>{{ number_format($produk->harga_produk, 0, ',', '.') }}</td>
                        <td>{{ $produk->stok_produk }}</td>
                        <td>{{ $produk->kategori->nama_kategori ?? '-' }}</td>
                        <td>
                            @if($produk->gambar_produk)
                                <img src="{{ asset('adminlte/img/' . $produk->gambar_produk) }}" alt="gambar" width="80">
                            @else
                                <span class="text-muted">Tidak ada</span>
                            @endif
                        </td>
                        @if(auth()->user()->hasAnyRole(['admin']))
                        <td>
                            <a href="{{ route('produk.edit', $produk->id) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('produk.destroy', $produk->id) }}" method="POST" style="display:inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus produk ini?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="d-flex justify-content-end mt-3">
                @if ($produks->lastPage() > 1)
                    {{ $produks->links('pagination::bootstrap-4') }}
                @else
                    <ul class="pagination">
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    </ul>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection