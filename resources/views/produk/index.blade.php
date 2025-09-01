@extends('layouts.adminlte')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header" style="background: #001f3f; background: linear-gradient(to right, #001f3f, #003366);">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title text-light text-bold">Daftar Produk</h3>
                @if(auth()->user()->hasAnyRole(['admin']))
                    <a href="{{ route('produk.create') }}" class="btn btn-light">
                        <i class="fas fa-plus"></i> Tambah Produk
                    </a>
                @endif
            </div>
            <div class="mt-2 w-100">
                <form method="GET" action="{{ route('produk.index') }}" id="searchForm">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" 
                            placeholder="Cari disini" 
                            value="{{ request('search') }}"
                            id="searchInput">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-secondary">
                                <i class="fas fa-search"></i>
                            </button>
                            @if(request('search'))
                            <a href="{{ route('produk.index') }}" class="btn btn-danger ml-1">
                                <i class="fas fa-times"></i> Reset
                            </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-striped text-center">
                    <thead class="bg-secondary text-white">
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
                        @forelse($produks as $produk)
                        <tr>
                            <td>{{ ($produks->currentPage() - 1) * $produks->perPage() + $loop->iteration }}</td>
                            <td>{{ $produk->nama_produk }}</td>
                            <td>Rp {{ number_format($produk->harga_produk, 0, ',', '.') }}</td>
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
                                <a href="{{ route('produk.show', $produk->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
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
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data produk</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($produks->hasPages())
            <div class="d-flex justify-content-start mt-3">
                {{ $produks->links('pagination::bootstrap-4') }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .card-header {
        padding: 1rem 1.25rem;
    }
    .input-group {
        width: 100%;
        max-width: 600px;
    }
    .input-group input {
        border-right: none;
    }
    .input-group-append .btn {
        border-left: none;
    }
    .input-group-append .btn-primary {
        background-color: #007bff;
        color: white;
    }
    .input-group-append .btn-danger {
        background-color: #dc3545;
        color: white;
    }
    .pagination {
        margin: 0;
    }
    .page-item.active .page-link {
        background-color: #007bff;
        border-color: #007bff;
    }
    .page-link {
        color: #007bff;
    }
    .table-responsive {
        min-height: 400px;
    }
    .table img {
        max-height: 80px;
        object-fit: cover;
    }
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const searchForm = document.getElementById('searchForm');
    let searchTimer;
    
    // Fungsi untuk submit form
    function submitSearch() {
        // Tambahkan parameter page=1 untuk reset ke halaman pertama saat search
        const formData = new FormData(searchForm);
        formData.set('page', '1');
        
        // Buat URL dengan parameter
        const params = new URLSearchParams(formData);
        window.location.href = `${searchForm.action}?${params.toString()}`;
    }
    
    // Pencarian otomatis saat mengetik
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(submitSearch, 800);
    });
    
    // Submit form saat tekan Enter
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            clearTimeout(searchTimer);
            submitSearch();
        }
    });
    
    // Focus ke input jika ada pencarian sebelumnya
    if(searchInput.value) {
        searchInput.focus();
    }
});
</script>
@endsection