@extends('layouts.adminlte')

@section('title', 'Profil Saya')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-dark">
            <i class="fas fa-user-circle mr-2 text-primary"></i>Profil Saya
        </h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent p-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Profil</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <!-- Left Column -->
        <div class="col-lg-3">
            <div class="card profile-summary-card mb-4">
                <div class="card-body text-center p-4">
                    <div class="profile-icon mx-auto mb-3">
                        @if(Auth::user()->photo)
                            <img src="{{ asset('storage/'.Auth::user()->photo) }}" alt="Foto profil" class="img-thumbnail rounded-circle">
                        @else
                            <i class="fas fa-user-circle"></i>
                        @endif
                    </div>
                    <h4 class="mb-1">{{ Auth::user()->name }}</h4>
                    <p class="text-muted mb-3">{{ Auth::user()->email }}</p>
                    
                    <div class="d-flex justify-content-center mb-3">
                        <div class="px-3 text-center">
                            <div class="h5 mb-0">{{ Auth::user()->created_at->format('d M Y') }}</div>
                            <small class="text-muted">Bergabung</small>
                        </div>
                        <div class="px-3 text-center border-start">
                            <div class="h5 mb-0">{{ Auth::user()->updated_at->format('d M Y') }}</div>
                            <small class="text-muted">Diperbarui</small>
                        </div>
                    </div>
                    
                    <hr class="my-3">
                    
                    <div class="d-grid gap-2">
                        <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-primary rounded">
                            <i class="fas fa-pencil-alt mr-2"></i> Edit Profil
                        </a>
                        <a href="{{ route('profile.change-password.show') }}" class="btn btn-sm btn-outline-secondary rounded">
                            <i class="fas fa-lock mr-2"></i> Keamanan
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="card quick-links-card mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0"><i class="fas fa-link mr-2"></i>Quick Links</h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="fas fa-cog mr-2 text-primary"></i> Pengaturan Akun
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="fas fa-bell mr-2 text-primary"></i> Notifikasi
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="fas fa-shield-alt mr-2 text-primary"></i> Privasi
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Column -->
        <div class="col-lg-9">
            <div class="card main-profile-card mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-info-circle mr-2 text-primary"></i>Informasi Profil</h5>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-print mr-2"></i> Cetak</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-download mr-2"></i> Unduh</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-item mb-4">
                                <label class="text-muted small mb-1">Nama Lengkap</label>
                                <p class="mb-0 fw-bold">{{ Auth::user()->name }}</p>
                            </div>
                            <div class="info-item mb-4">
                                <label class="text-muted small mb-1">Email</label>
                                <p class="mb-0 fw-bold">{{ Auth::user()->email }}</p>
                            </div>
                            <div class="info-item mb-4">
                                <label class="text-muted small mb-1">Nomor Telepon</label>
                                <p class="mb-0 fw-bold">{{ Auth::user()->phone ?: '-' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item mb-4">
                                <label class="text-muted small mb-1">Peran</label>
                                <p class="mb-0">
                                    <span class="badge bg-primary">{{ Auth::user()->role }}</span>
                                </p>
                            </div>
                            <div class="info-item mb-4">
                                <label class="text-muted small mb-1">Bergabung Pada</label>
                                <p class="mb-0 fw-bold">{{ Auth::user()->created_at->format('d F Y') }}</p>
                            </div>
                            <div class="info-item mb-4">
                                <label class="text-muted small mb-1">Alamat</label>
                                <p class="mb-0 fw-bold">{{ Auth::user()->address ?: '-' }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <h5 class="mb-3"><i class="fas fa-shield-alt mr-2 text-primary"></i>Keamanan Akun</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="security-status p-3 mb-3 rounded">
                                <div class="d-flex align-items-center">
                                    <div class="status-icon bg-success text-white rounded-circle me-3">
                                        <i class="fas fa-check"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">Email Terverifikasi</h6>
                                        <small class="text-muted">Status email sudah terverifikasi</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="security-status p-3 mb-3 rounded">
                                <div class="d-flex align-items-center">
                                    <div class="status-icon bg-warning text-white rounded-circle me-3">
                                        <i class="fas fa-exclamation"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">Kata Sandi</h6>
                                        <small class="text-muted">Diubah 3 bulan lalu</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <h5 class="mb-3"><i class="fas fa-history mr-2 text-primary"></i>Aktivitas Terakhir</h5>
                    <div class="activity-feed">
                        <div class="activity-item d-flex mb-3">
                            <div class="activity-icon bg-primary text-white rounded-circle me-3">
                                <i class="fas fa-sign-in-alt"></i>
                            </div>
                            <div class="activity-details flex-grow-1">
                                <h6 class="mb-1">Login ke sistem</h6>
                                <p class="text-muted small mb-0">Hari ini, 10:45 AM</p>
                                <p class="text-muted small">
                                    <i class="fas fa-globe mr-1"></i> IP: 192.168.1.1 • Browser: Chrome
                                </p>
                            </div>
                            <div class="activity-time text-muted small">
                                10:45 AM
                            </div>
                        </div>
                        
                        <div class="activity-item d-flex mb-3">
                            <div class="activity-icon bg-info text-white rounded-circle me-3">
                                <i class="fas fa-edit"></i>
                            </div>
                            <div class="activity-details flex-grow-1">
                                <h6 class="mb-1">Mengupdate profil</h6>
                                <p class="text-muted small mb-0">Kemarin, 2:30 PM</p>
                                <p class="text-muted small">
                                    <i class="fas fa-user-edit mr-1"></i> Informasi pribadi
                                </p>
                            </div>
                            <div class="activity-time text-muted small">
                                2:30 PM
                            </div>
                        </div>
                        
                        <div class="activity-item d-flex">
                            <div class="activity-icon bg-success text-white rounded-circle me-3">
                                <i class="fas fa-key"></i>
                            </div>
                            <div class="activity-details flex-grow-1">
                                <h6 class="mb-1">Mengubah kata sandi</h6>
                                <p class="text-muted small mb-0">2 hari lalu, 9:15 AM</p>
                                <p class="text-muted small">
                                    <i class="fas fa-shield-alt mr-1"></i> Keamanan akun
                                </p>
                            </div>
                            <div class="activity-time text-muted small">
                                9:15 AM
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Photo Upload Modal -->
<div class="modal fade" id="changePhotoModal" tabindex="-1" role="dialog" aria-labelledby="changePhotoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changePhotoModalLabel">
                    <i class="fas fa-camera mr-2 text-primary"></i> Ganti Foto Profil
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="photoUploadForm" action="{{ route('profile.update.photo') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="upload-area text-center p-4 mb-3">
                        <div class="upload-icon text-primary mb-3">
                            <i class="fas fa-cloud-upload-alt fa-3x"></i>
                        </div>
                        <h5 class="mb-2">Seret & Lepas Foto Disini</h5>
                        <p class="text-muted small mb-3">Atau</p>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="photoInput" name="photo" required accept="image/*">
                            <label class="custom-file-label btn btn-outline-primary" for="photoInput">Pilih File</label>
                        </div>
                        <small class="form-text text-muted">Format: JPEG, PNG (Maksimal 2MB)</small>
                    </div>
                    <div class="preview-area text-center">
                        <img id="imagePreview" src="#" alt="Preview" class="img-thumbnail rounded-circle d-none" style="width: 200px; height: 200px; object-fit: cover;">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="uploadButton">
                        <span class="button-text">Simpan Perubahan</span>
                        <span class="button-loading d-none">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Mengupload...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    :root {
        --primary-color: #3a7bd5;
        --secondary-color: #6a11cb;
        --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }
    
    .profile-summary-card {
        border: none;
        box-shadow: var(--card-shadow);
        transition: all 0.3s ease;
    }
    
    .profile-summary-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }
    
    .profile-icon {
        width: 120px;
        height: 120px;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        margin: 0 auto 1rem;
        overflow: hidden;
    }
    
    .profile-icon img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .quick-links-card {
        border: none;
        box-shadow: var(--card-shadow);
    }
    
    .main-profile-card {
        border: none;
        box-shadow: var(--card-shadow);
    }
    
    .info-item {
        padding: 1rem;
        background-color: rgba(58, 123, 213, 0.05);
        border-radius: 8px;
    }
    
    .security-status {
        background-color: rgba(58, 123, 213, 0.05);
        transition: all 0.3s ease;
    }
    
    .security-status:hover {
        background-color: rgba(58, 123, 213, 0.1);
    }
    
    .status-icon {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .activity-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .activity-item {
        padding: 1rem;
        border-radius: 8px;
        transition: all 0.3s ease;
    }
    
    .activity-item:hover {
        background-color: rgba(58, 123, 213, 0.05);
    }
    
    .btn-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }
    
    .btn-outline-secondary {
        border-color: #dee2e6;
    }
    
    .badge {
        padding: 0.35em 0.65em;
        font-weight: 500;
    }
    
    hr {
        opacity: 0.1;
    }
    
    .upload-area {
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        transition: all 0.3s;
    }
    
    .upload-area:hover {
        border-color: var(--primary-color);
        background-color: rgba(58, 123, 213, 0.05);
    }
</style>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        // Preview image before upload
        $('#photoInput').change(function(e) {
            if (this.files && this.files[0]) {
                var reader = new FileReader();
                
                reader.onload = function(e) {
                    $('#imagePreview')
                        .attr('src', e.target.result)
                        .removeClass('d-none');
                    $('.upload-area').addClass('d-none');
                }
                
                reader.readAsDataURL(this.files[0]);
            }
        });

        // AJAX Form Submission with Loading State
        $('#photoUploadForm').on('submit', function(e) {
            e.preventDefault();
            
            var formData = new FormData(this);
            var button = $('#uploadButton');
            
            // Show loading state
            button.find('.button-text').addClass('d-none');
            button.find('.button-loading').removeClass('d-none');
            button.prop('disabled', true);
            
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $('#changePhotoModal').modal('hide');
                    location.reload(); // Reload to show new photo
                    
                    // Show success notification
                    toastr.success('Foto profil berhasil diperbarui');
                },
                error: function(xhr) {
                    // Show error notification
                    toastr.error(xhr.responseJSON.message || 'Terjadi kesalahan saat mengupload foto');
                },
                complete: function() {
                    // Reset button state
                    button.find('.button-text').removeClass('d-none');
                    button.find('.button-loading').addClass('d-none');
                    button.prop('disabled', false);
                    $('.upload-area').removeClass('d-none');
                    $('#imagePreview').addClass('d-none');
                    $('#photoInput').val('');
                }
            });
        });
        
        // Drag and drop functionality
        $('.upload-area').on('dragover', function(e) {
            e.preventDefault();
            $(this).css('border-color', 'var(--primary-color)');
            $(this).css('background', 'rgba(58, 123, 213, 0.1)');
        });
        
        $('.upload-area').on('dragleave', function(e) {
            e.preventDefault();
            $(this).css('border-color', '#dee2e6');
            $(this).css('background', 'transparent');
        });
        
        $('.upload-area').on('drop', function(e) {
            e.preventDefault();
            $(this).css('border-color', '#dee2e6');
            $(this).css('background', 'transparent');
            
            var file = e.originalEvent.dataTransfer.files[0];
            if (file) {
                $('#photoInput')[0].files = e.originalEvent.dataTransfer.files;
                $('#photoInput').trigger('change');
            }
        });
    });
</script>
@endpush