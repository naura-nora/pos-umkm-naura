@extends('layouts.adminlte')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-user-circle mr-2"></i>Profil Saya</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                        <i class="fas fa-edit mr-2"></i> Edit Profil
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- Left Column - Profile Card -->
                <div class="col-lg-4">
                    <div class="card profile-card shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="profile-avatar mb-3">
                                <img src="{{ Auth::user()->photo ? asset('storage/'.Auth::user()->photo) : asset('adminlte/dist/img/user-default.png') }}" 
                                     alt="Foto profil" class="img-thumbnail rounded-circle">
                            </div>
                            
                            <h3 class="profile-name mb-1">{{ Auth::user()->name }}</h3>
                            <p class="text-muted mb-4">{{ Auth::user()->email }}</p>
                            
                            <div class="profile-meta bg-light p-3 rounded mb-4">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Bergabung</span>
                                    <span class="font-weight-bold">{{ Auth::user()->created_at->format('d M Y') }}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">Terakhir Diperbarui</span>
                                    <span class="font-weight-bold">{{ Auth::user()->updated_at->format('d M Y') }}</span>
                                </div>
                            </div>
                            
                            <button class="btn btn-outline-primary btn-block" data-toggle="modal" data-target="#changePhotoModal">
                                <i class="fas fa-camera mr-2"></i> Ganti Foto
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Profile Details -->
                <div class="col-lg-8">
                    <div class="card profile-details-card shadow-sm">
                        <div class="card-body p-4">
                            <h4 class="card-title mb-4"><i class="fas fa-info-circle text-primary mr-2"></i>Informasi Profil</h4>
                            
                            <div class="profile-detail-list">
                                <div class="detail-item py-3 border-bottom">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <span class="detail-label font-weight-bold">Nama Lengkap</span>
                                        </div>
                                        <div class="col-md-9">
                                            <span class="detail-value">{{ Auth::user()->name }}</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="detail-item py-3 border-bottom">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <span class="detail-label font-weight-bold">Email</span>
                                        </div>
                                        <div class="col-md-9">
                                            <span class="detail-value">{{ Auth::user()->email }}</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="detail-item py-3 border-bottom">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <span class="detail-label font-weight-bold">Nomor Telepon</span>
                                        </div>
                                        <div class="col-md-9">
                                            <span class="detail-value">{{ Auth::user()->phone ?? '-' }}</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="detail-item py-3 border-bottom">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <span class="detail-label font-weight-bold">Alamat</span>
                                        </div>
                                        <div class="col-md-9">
                                            <span class="detail-value">{{ Auth::user()->address ?? '-' }}</span>
                                        </div>
                                    </div>
                                </div>
                                
                                @if(Auth::user()->role)
                                <div class="detail-item py-3">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <span class="detail-label font-weight-bold">Role</span>
                                        </div>
                                        <div class="col-md-9">
                                            <span class="detail-value">{{ Auth::user()->role }}</span>
                                        </div>
                                    </div>
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

<!-- Change Photo Modal -->
<div class="modal fade" id="changePhotoModal" tabindex="-1" role="dialog" aria-labelledby="changePhotoModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changePhotoModalLabel">Ganti Foto Profil</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('profile.update.photo') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="photo">Pilih Foto Baru</label>
                        <input type="file" class="form-control-file" id="photo" name="photo" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Foto</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('css')
<style>
    /* Profile Card */
    .profile-card {
        border-radius: 10px;
        border: none;
        height: 100%;
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }
    
    .profile-avatar img {
        width: 150px;
        height: 150px;
        object-fit: cover;
        border: 5px solid #fff;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .profile-name {
        font-weight: 700;
        color: #2c3e50;
        font-size: 1.5rem;
    }
    
    .profile-meta {
        background-color: #f8f9fa;
        border-radius: 8px;
    }
    
    /* Profile Details Card */
    .profile-details-card {
        border-radius: 10px;
        border: none;
        height: 100%;
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }
    
    .card-title {
        color: #2c3e50;
        font-weight: 600;
        font-size: 1.25rem;
    }
    
    .detail-label {
        color: #7f8c8d;
    }
    
    .detail-value {
        color: #2c3e50;
    }
    
    /* Hover Effects */
    .btn-outline-primary:hover {
        background-color: #3498db;
        color: white;
    }
    
    /* Responsive Adjustments */
    @media (max-width: 992px) {
        .profile-card, .profile-details-card {
            margin-bottom: 20px;
        }
        
        .detail-item .col-md-3 {
            margin-bottom: 5px;
        }
    }
    
    @media (max-width: 576px) {
        .profile-avatar img {
            width: 120px;
            height: 120px;
        }
        
        .profile-name {
            font-size: 1.3rem;
        }
    }
</style>
@endpush

@push('js')
<script>
    $(document).ready(function() {
        // Preview image before upload
        $('#photo').change(function(e) {
            if (e.target.files.length > 0) {
                var src = URL.createObjectURL(e.target.files[0]);
                $('.profile-avatar img').attr('src', src);
            }
        });
    });
</script>
@endpush