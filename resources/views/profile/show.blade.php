@extends('layouts.adminlte')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-user-circle mr-2"></i>Profil Saya</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- Profile Card -->
                <div class="col-md-4">
                    <div class="card profile-card">
                        <div class="card-header bg-gradient-primary">
                            <h3 class="card-title text-white">Foto Profil</h3>
                        </div>
                        <div class="card-body text-center">
                            <div class="profile-avatar">
                                <img src="{{ Auth::user()->photo ? asset('storage/'.Auth::user()->photo) : asset('adminlte/dist/img/user-default.png') }}" 
                                     alt="Foto profil" class="img-thumbnail">
                            </div>
                            <h3 class="profile-name mt-3">{{ Auth::user()->name }}</h3>
                            <p class="text-muted">{{ Auth::user()->email }}</p>
                            
                            <div class="profile-stats">
                                <div class="stat-item">
                                    <div class="stat-label">Bergabung</div>
                                    <div class="stat-value">{{ Auth::user()->created_at->format('d M Y') }}</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-label">Terakhir Diperbarui</div>
                                    <div class="stat-value">{{ Auth::user()->updated_at->format('d M Y') }}</div>
                                </div>
                            </div>
                            
                            <a href="{{ route('profile.edit') }}" class="btn btn-primary btn-edit mt-3">
                                <i class="fas fa-edit mr-2"></i> Edit Profil
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Profile Information -->
                <div class="col-md-8">
                    <div class="card profile-info-card">
                        <div class="card-header bg-gradient-info">
                            <h3 class="card-title text-white"><i class="fas fa-info-circle mr-2"></i>Informasi Profil</h3>
                        </div>
                        <div class="card-body">
                            <div class="profile-details">
                                <div class="detail-item">
                                    <div class="detail-label">Nama Lengkap</div>
                                    <div class="detail-value">{{ Auth::user()->name }}</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Email</div>
                                    <div class="detail-value">{{ Auth::user()->email }}</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Nomor Telepon</div>
                                    <div class="detail-value">{{ Auth::user()->phone ?? '-' }}</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Alamat</div>
                                    <div class="detail-value">{{ Auth::user()->address ?? '-' }}</div>
                                </div>
                                @if(Auth::user()->role)
                                <div class="detail-item">
                                    <div class="detail-label">Role</div>
                                    <div class="detail-value">{{ Auth::user()->role }}</div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('css')
<style>
    /* Profile Card Styles */
    .profile-card {
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        border: none;
    }
    
    .profile-avatar img {
        width: 150px;
        height: 150px;
        object-fit: cover;
        border-radius: 50%;
        border: 5px solid #fff;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    
    .profile-name {
        font-weight: 600;
        color: #343a40;
        margin-bottom: 5px;
    }
    
    .profile-stats {
        display: flex;
        justify-content: space-around;
        margin: 20px 0;
        padding: 15px 0;
        border-top: 1px solid #eee;
        border-bottom: 1px solid #eee;
    }
    
    .stat-item {
        text-align: center;
    }
    
    .stat-label {
        font-size: 14px;
        color: #6c757d;
    }
    
    .stat-value {
        font-weight: 600;
        color: #495057;
    }
    
    .btn-edit {
        border-radius: 50px;
        padding: 8px 25px;
        font-weight: 600;
        box-shadow: 0 4px 10px rgba(0,123,255,0.3);
    }
    
    /* Profile Info Card Styles */
    .profile-info-card {
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        border: none;
    }
    
    .profile-details {
        padding: 15px;
    }
    
    .detail-item {
        display: flex;
        padding: 12px 0;
        border-bottom: 1px solid #f1f1f1;
    }
    
    .detail-item:last-child {
        border-bottom: none;
    }
    
    .detail-label {
        width: 200px;
        font-weight: 600;
        color: #495057;
    }
    
    .detail-value {
        flex: 1;
        color: #6c757d;
    }
    
    /* Gradient Header */
    .bg-gradient-primary {
        background: linear-gradient(135deg, #6777ef 0%, #6777ef 100%);
    }
    
    .bg-gradient-info {
        background: linear-gradient(135deg, #3abaf4 0%, #6777ef 100%);
    }
    
    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .profile-stats {
            flex-direction: column;
        }
        
        .stat-item {
            margin-bottom: 10px;
        }
        
        .detail-item {
            flex-direction: column;
        }
        
        .detail-label {
            width: 100%;
            margin-bottom: 5px;
        }
    }
</style>
@endpush