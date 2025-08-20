@extends('layouts.adminlte')

@section('content')
<div class="container">
    <div class="card">
        <!-- Header dengan Pencarian di Bawah Judul -->
        <div class="card-header" style="background: #001f3f; background: linear-gradient(to right, #001f3f, #003366);">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title text-light text-bold">Aktivitas Terakhir</h3>
            </div>
            
            <!-- Form Pencarian Full Width -->
            <div class="mt-3 w-100">
                <form method="GET" action="{{ route('aktivitas.index') }}" id="searchForm">
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
                            <a href="{{ route('aktivitas.index') }}" class="btn btn-danger ml-1">
                                <i class="fas fa-times"></i> Reset
                            </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Body -->
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="bg-secondary text-white">
                        <tr>
                            <th>No</th>
                            <th>User</th>
                            <th>Event</th>
                            <th>Deskripsi</th>
                            <th>Detail</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($activities as $activity)
                        <tr>
                            <td>{{ ($activities->currentPage() - 1) * $activities->perPage() + $loop->iteration }}</td>
                            <td>
                                @if($activity->causer)
                                    {{ $activity->causer->name }}
                                    <br>
                                    <small class="text-muted">{{ $activity->causer->email }}</small>
                                @else
                                    System
                                @endif
                            </td>
                            <td>
                                <span class="badge 
                                    @if($activity->event == 'created') badge-success
                                    @elseif($activity->event == 'updated') badge-warning
                                    @elseif($activity->event == 'deleted') badge-danger
                                    @elseif($activity->event == 'login') badge-info
                                    @elseif($activity->event == 'logout') badge-secondary
                                    @else badge-primary @endif">
                                    {{ ucfirst($activity->event) }}
                                </span>
                            </td>
                            <td>{{ $activity->description }}</td>
                            <td>
                                @if($activity->properties && count($activity->properties) > 0)
                                    <button class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#detailModal{{ $activity->id }}">
                                        <i class="fas fa-eye"></i> Lihat
                                    </button>
                                    
                                    <!-- Modal -->
                                    <div class="modal fade" id="detailModal{{ $activity->id }}" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="detailModalLabel">Detail Aktivitas</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <pre>{{ json_encode($activity->properties, JSON_PRETTY_PRINT) }}</pre>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                {{ $activity->created_at->format('d M Y H:i') }}
                                <br>
                                <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada aktivitas yang tercatat</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-3">
                {{ $activities->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div>
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
    .badge {
        font-size: 0.9em;
        padding: 0.4em 0.6em;
    }
    pre {
        white-space: pre-wrap;
        word-wrap: break-word;
        background: #f8f9fa;
        padding: 1rem;
        border-radius: 4px;
    }
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const searchForm = document.getElementById('searchForm');
    let searchTimer;
    
    function submitSearch() {
        const formData = new FormData(searchForm);
        formData.set('page', '1');
        const params = new URLSearchParams(formData);
        window.location.href = `${searchForm.action}?${params.toString()}`;
    }
    
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(submitSearch, 800);
    });
    
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            clearTimeout(searchTimer);
            submitSearch();
        }
    });
    
    if(searchInput.value) {
        searchInput.focus();
    }
});
</script>
@endsection