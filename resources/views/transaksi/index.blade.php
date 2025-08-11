@extends('layouts.adminlte')

@section('content')
<div class="container">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header bg-primary">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title">Daftar Transaksi</h3>
                <div>
                    @if(auth()->user()->hasRole(['admin', 'kasir']))
                        <a href="{{ route('transaksi.create') }}" class="btn btn-light">
                            <i class="fas fa-plus"></i> Transaksi Baru
                        </a>
                    @endif
                </div>
            </div>
            <div class="card-tools mt-3 container-fluid">
                <form method="GET" action="{{ route('transaksi.index') }}" id="searchForm">
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
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped text-center">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Pelanggan</th>
                            <th>Total</th>
                            <th>Kasir</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transaksi as $trx)
                        <tr>
                            <td>{{ ($transaksi->currentPage() - 1) * $transaksi->perPage() + $loop->iteration }}</td>
                            <td>{{ $trx->nama_pelanggan }}</td>
                            <td>Rp {{ number_format($trx->total, 0, ',', '.') }}</td>
                            <td>{{ $trx->user->name }}</td>
                            <td>{{ $trx->tanggal->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge {{ $trx->status === 'Lunas' ? 'bg-success' : 'bg-warning' }}">
                                    {{ $trx->status }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('transaksi.show', $trx->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if(auth()->user()->hasRole('admin') || (auth()->user()->hasRole('kasir') && $trx->user_id == auth()->id()))
                                <a href="{{ route('transaksi.edit', $trx->id) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endif

                                @if(auth()->user()->hasRole('admin'))
                                <form action="{{ route('transaksi.destroy', $trx->id) }}" method="POST" style="display:inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus transaksi ini?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data transaksi</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($transaksi->hasPages())
            <div class="d-flex justify-content-center mt-3">
                {{ $transaksi->links() }}
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
        min-height: 400px;
    }
    .dataTables_info {
        padding-top: 0.85em;
    }
    .badge {
        font-size: 0.9em;
        padding: 0.4em 0.6em;
    }
    /* Pastikan ada jarak antara tabel dan footer */
    .card {
        margin-bottom: 20px;
    }
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    let searchTimer;
    
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(function() {
            document.getElementById('searchForm').submit();
        }, 500);
    });
    
    if(searchInput.value) {
        searchInput.focus();
    }
});
</script>
@endsection