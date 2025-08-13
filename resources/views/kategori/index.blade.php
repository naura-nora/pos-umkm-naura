@extends('layouts.adminlte')

@section('content')
<div class="container">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <!-- TEMPATKAN KODE CARD HEADER DI SINI -->
        <div class="card-header" style="background: #001f3f; background: linear-gradient(to right, #001f3f, #003366);">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title text-light text-bold">Daftar Kategori</h3>
                @if(auth()->user()->hasAnyRole(['admin']))
                <a href="{{ route('kategori.create') }}" class="btn btn-light">
                    <i class="fas fa-plus"></i> Tambah Kategori
                </a>
                @endif
            </div>
            <div class="mt-2 w-100">
                <form method="GET" action="{{ route('kategori.index') }}" id="searchForm" class="w-100">
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
                            <a href="{{ route('kategori.index') }}" class="btn btn-danger">
                                <i class="fas fa-times"></i> Reset
                            </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- END OF CARD HEADER -->

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped text-center w-100">
                    <thead class="bg-secondary text-white">
                        <tr>
                            <th style="width: 10%;">No</th>
                            <th>Nama Kategori</th>
                            @if(auth()->user()->hasAnyRole(['admin']))
                            <th style="width: 20%;">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kategori as $item)
                        <tr>
                            <td>{{ ($kategori->currentPage() - 1) * $kategori->perPage() + $loop->iteration }}</td>
                            <td>{{ $item->nama_kategori }}</td>
                            @if(auth()->user()->hasAnyRole(['admin']))
                            <td>
                                <a href="{{ route('kategori.edit', $item->id) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('kategori.destroy', $item->id) }}" method="POST" style="display:inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus kategori ini?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                            @endif
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ auth()->user()->hasAnyRole(['admin']) ? 3 : 2 }}" class="text-center">
                                Tidak ada data kategori
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($kategori->hasPages())
            <div class="d-flex justify-content-center mt-3">
                {{ $kategori->links('pagination::bootstrap-4') }}
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
        max-width: 500px;
    }
    .input-group input {
        border-right: none;
    }
    .input-group-append .btn {
        border-left: none;
        background-color: #fff;
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
        min-height: 300px;
    }
    .badge {
        font-size: 0.9em;
        padding: 0.4em 0.6em;
    }
    .card {
        margin-bottom: 20px;
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
        searchForm.submit();
    }
    
    // Event listener untuk input
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(submitSearch, 800);
    });
    
    // Event listener untuk tombol enter
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            clearTimeout(searchTimer);
            submitSearch();
        }
    });
});
</script>
@endsection
