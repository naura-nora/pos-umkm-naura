@extends('layouts.adminlte')

@section('content')
<div class="container">
    <div class="card">
        <!-- Header -->
        <div class="card-header" style="background: #001f3f; background: linear-gradient(to right, #001f3f, #003366);">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title text-light text-bold">User & Role Management</h3>
                <a href="{{ route('user-management.create') }}" class="btn btn-light">
                    <i class="fas fa-plus"></i> Tambah Akun
                </a>
            </div>
            
            <!-- Search Form -->
            <div class="mt-2 w-100">
                <form method="GET" action="{{ route('user-management.index') }}" id="searchForm">
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
                            <a href="{{ route('user-management.index') }}" class="btn btn-danger ml-1">
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
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-striped text-center">
                    <thead class="bg-secondary text-white">
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Password</th>
                            <th>Role</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <div class="input-group input-group-sm">
                                    <input type="password" class="form-control password-field" 
                                           value="••••••••" 
                                           data-password="{{ $user->password_plain ?? '' }}"
                                           readonly
                                           id="password-{{ $user->id }}">
                                    <div class="input-group-append">
                                        <button class="btn toggle-password" 
                                                type="button"
                                                data-target="#password-{{ $user->id }}">
                                            <!-- <i class="fas fa-eye"></i> -->
                                        </button>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $user->getRoleNames()->first() ?? 'Pelanggan' }}</td>
                            <td>
                                <a href="{{ route('user-management.show', $user->id) }}" 
                                   class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('user-management.edit', $user->id) }}" 
                                   class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('user-management.destroy', $user->id) }}" 
                                      method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus akun ini?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5">Tidak ada data user</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($users->hasPages())
            <div class="d-flex justify-content-start mt-3">
                {{ $users->links('pagination::bootstrap-4') }}
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
    .password-field {
        font-family: monospace;
    }
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Password toggle functionality
    document.querySelectorAll('.toggle-password').forEach(function(button) {
        button.addEventListener('click', function() {
            const inputGroup = this.closest('.input-group');
            const input = inputGroup.querySelector('.password-field');
            const icon = this.querySelector('i');
            
            if (input.type === 'password') {
                // Show actual password
                input.type = 'text';
                input.value = input.dataset.password;
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                // Hide password
                input.type = 'password';
                input.value = '********';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });

    // Search functionality
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