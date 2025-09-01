@extends('layouts.adminlte')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Detail User {{ $user->id }}</h1>
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
                            <h3 class="card-title text-white">Informasi User</h3>
                            <div class="card-tools">
                                <a href="{{ route('user-management.index') }}" class="btn btn-default">
                                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <div class="card h-100">
                                        <div class="card-header bg-warning">
                                            <h3 class="card-title">Data User</h3>
                                        </div>
                                        <div class="card-body p-0">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th width="30%">ID</th>
                                                    <td>{{ $user->id }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Nama User</th>
                                                    <td>{{ $user->name }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Email</th>
                                                    <td>{{ $user->email }}</td>
                                                </tr>
                                                <tr>
                                                    <th>No HP</th>
                                                    <td>{{ $user->phone ? $user->phone : '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Alamat</th>
                                                    <td>{{ $user->address ? $user->address : '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Password</th>
                                                    <td>••••••••</td>
                                                </tr>
                                                <tr>
                                                    <th>Bergabung</th>
                                                    <td>{{ $user->created_at ? \Carbon\Carbon::parse($user->created_at)->format('d/m/Y') : '-' }}</td>
                                                </tr>
                                                
                                                <tr>
                                                    <th>Update Terakhir</th>
                                                    <td>{{ $user->updated_at ? \Carbon\Carbon::parse($user->updated_at)->format('d/m/Y') : '-' }}</td>
                                                </tr>
                                                
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Card Gambar Produk -->
                                <div class="col-md-6">
                                    <div class="card h-100">
                                        <div class="card-header bg-warning">
                                            <h3 class="card-title">Foto Profil</h3>
                                        </div>
                                        <div class="card-body text-center d-flex align-items-center justify-content-center">
                                            @if($user->photo)
                                                <!-- ✅ Ambil foto dari $user (user yang dilihat), bukan Auth::user() -->
                                                <img src="{{ asset('adminlte/img/profile-photos/' . $user->photo) }}" 
                                                    alt="Foto Profil {{ $user->name }}" 
                                                    class="img-fluid rounded shadow-sm" 
                                                    style="max-height: 300px; object-fit: cover;">
                                            @else
                                                <div class="text-center py-3">
                                                    <!-- Ganti ikon jadi lebih cocok untuk profil -->
                                                    <i class="fas fa-user-circle fa-5x text-muted"></i>
                                                    <p class="mt-3 text-muted">Tidak ada foto profil</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
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