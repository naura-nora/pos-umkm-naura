@extends('layouts.adminlte')

@section('content')
<div class="container">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header" style="background: #001f3f; background: linear-gradient(to right, #001f3f, #003366);">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title text-light text-bold">Manajemen Retur Barang</h3>
                <div>
                    @if(auth()->user()->hasRole(['admin', 'kasir']))
                        <a href="{{ route('retur.create') }}" class="btn btn-light">
                            <i class="fas fa-plus"></i> Tambah Retur
                        </a>
                    @endif
                </div>
            </div>
            <div class="mt-2 w-100">
                <form method="GET" action="{{ route('retur.index') }}" id="searchForm">
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
                            <a href="{{ route('retur.index') }}" class="btn btn-danger ml-1">
                                <i class="fas fa-times"></i> Reset
                            </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped text-center">
                    <thead class="bg-secondary">
                        <tr>
                            <th>No</th>
                            <th>Produk</th>
                            <th>Jumlah</th>
                            <!-- <th>Alasan</th> -->
                            <th>Status</th>
                            <th>Dibuat Oleh</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($returs as $retur)
                        <tr>
                            <td>{{ ($returs->currentPage() - 1) * $returs->perPage() + $loop->iteration }}</td>
                            <td>{{ $retur->produk->nama_produk ?? '-' }}</td>
                            <td>{{ $retur->qty }}</td>
                            <!-- <td>{{ Str::limit($retur->alasan, 30) }}</td> -->
                            <td>
                                @if($retur->status == 'pending')
                                    <span class="badge bg-warning text-dark">Menunggu</span>
                                @elseif($retur->status == 'approved')
                                    <span class="badge bg-success text-white">Disetujui</span>
                                @else
                                    <span class="badge bg-danger text-white">Ditolak</span>
                                @endif
                            </td>
                            <td>{{ $retur->user->name ?? '-' }}</td>
                            <td>{{ $retur->created_at->format('d/m/Y') }}</td>
                            
                            <td>
                                <!-- Lihat Detail -->
                                <a href="{{ route('retur.show', $retur->id) }}" class="btn btn-sm btn-info" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>

                                <!-- Edit (hanya admin, selalu tampil) -->
                                @if(auth()->user()->hasRole('admin'))
                                    <a href="{{ route('retur.edit', $retur->id) }}" class="btn btn-sm btn-warning" title="Edit Retur">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @endif

                                <!-- Hapus -->
                                <form action="{{ route('retur.destroy', $retur->id) }}" method="post" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus retur ini?')" title="Hapus Retur">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>

                                <!-- Approve & Reject (hanya jika status = pending DAN user adalah admin) -->
                                @if(auth()->user()->hasRole('admin') && $retur->status === 'pending')
                                    <form action="{{ route('retur.approve', $retur->id) }}" method="post" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Setujui retur ini?')">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>

                                    <form action="{{ route('retur.reject', $retur->id) }}" method="post" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Tolak retur ini?')">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">Tidak ada data retur</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($returs->hasPages())
            <div class="d-flex justify-content-start mt-3">
                {{ $returs->onEachSide(1)->links('pagination::bootstrap-4') }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>

    



    /* Badge status */
    .badge {
        font-size: 0.75rem;
        padding: 0.3em 0.6em;
        border-radius: 4px;
    }

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
    .badge {
        font-size: 0.9em;
        padding: 0.4em 0.6em;
    }
    .card {
        margin-bottom: 20px;
    }
    
    /* Custom Pagination Kecil */
    .pagination {
        font-size: 12px;
        margin: 0;
    }
    
    .pagination .page-item .page-link {
        padding: 0.25rem 0.5rem;
        min-width: 28px;
        text-align: center;
        margin: 0 2px;
        border-radius: 3px;
    }
    
    .pagination .page-item.active .page-link {
        background-color: #007bff;
        border-color: #007bff;
    }
    
    .pagination .page-link {
        color: #007bff;
    }
    
    /* Untuk tombol Previous/Next */
    .pagination .page-item:first-child .page-link,
    .pagination .page-item:last-child .page-link {
        padding: 0.25rem 0.5rem;
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
        // Reset ke halaman pertama saat melakukan pencarian baru
        const formData = new FormData(searchForm);
        formData.set('page', '1');
        
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