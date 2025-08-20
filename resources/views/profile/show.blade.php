@extends('layouts.adminlte')

@section('title', 'Profil Saya')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-dark">
            <i class="fas fa-user-circle mr-2 text-primary"></i>Profil Saya
        </h1>
    </div>

    <div class="row">
        <!-- Left Column -->
        <div class="col-lg-4">
            <div class="card profile-summary-card mb-4" style="background: #001f3f; background: linear-gradient(to right, #001f3f, #003366);">
                <div class="card-body text-center p-4">
                    <div class="profile-picture-container mx-auto mb-3 position-relative">
                        @if(Auth::user()->photo)
                            <img src="{{ asset('storage/'.Auth::user()->photo) }}" alt="Foto profil" class="img-thumbnail rounded-circle profile-picture">
                        @else
                            <div class="profile-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="fas fa-user text-white"></i>
                            </div>
                        @endif
                        <button class="btn btn-sm btn-primary rounded-circle change-photo-btn" data-toggle="modal" data-target="#changePhotoModal" title="Ubah Foto">
                            <i class="fas fa-camera"></i>
                        </button>
                    </div>
                    <h4 class="mb-1 text-white">{{ Auth::user()->name }}</h4>
                    <p class="text-white mb-3">{{ Auth::user()->email }}</p>
                    
                    <div class="d-flex justify-content-center mb-3">
                        <div class="px-3 text-center">
                            <div class="h5 mb-0 text-white">{{ Auth::user()->created_at->format('d M Y') }}</div>
                            <small class="text-white">Bergabung</small>
                        </div>
                        <div class="px-3 text-center border-left">
                            <div class="h5 mb-0 text-white">{{ Auth::user()->updated_at->format('d M Y') }}</div>
                            <small class="text-white">Diperbarui</small>
                        </div>
                    </div>
                    
                    <hr class="my-3">
                    
                    <div class="d-grid gap-2">
                        <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-warning rounded-3">
                            <i class="fas fa-pencil-alt mr-2"></i> Edit Profil
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Column -->
        <div class="col-lg-8">
            <div class="card main-profile-card mb-4" >
                <div class="card-header d-flex justify-content-between align-items-center" style="background: #001f3f; background: linear-gradient(to right, #001f3f, #003366);">
                    <h5 class="mb-0 text-white"><i class="fas fa-info-circle mr-2 text-warning"></i>Informasi Profil</h5>
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
                        </div>
                        <div class="col-md-6">
                            <div class="info-item mb-4">
                                <label class="text-muted small mb-1">Nomor Telepon</label>
                                <p class="mb-0 fw-bold">{{ Auth::user()->phone ?: '-' }}</p>
                            </div>
                            <div class="info-item mb-4">
                                <label class="text-muted small mb-1">Alamat</label>
                                <p class="mb-0 fw-bold">{{ Auth::user()->address ?: '-' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="info-item">
                                <label class="text-muted small mb-1">Bergabung Pada</label>
                                <p class="mb-0 fw-bold">{{ Auth::user()->created_at->format('d F Y') }}</p>
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
        border-radius: 10px;
        transition: all 0.3s ease;
    }
    
    .profile-summary-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }
    
    .profile-picture-container {
        width: 150px;
        height: 150px;
        position: relative;
    }
    
    .profile-picture {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .profile-icon {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        font-size: 3.5rem;
    }
    
    .change-photo-btn {
        position: absolute;
        bottom: 10px;
        right: 10px;
        width: 36px;
        height: 36px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .main-profile-card {
        border: none;
        box-shadow: var(--card-shadow);
        border-radius: 10px;
    }
    
    .info-item {
        padding: 1rem;
        background-color: rgba(58, 123, 213, 0.05);
        border-radius: 8px;
        transition: all 0.3s ease;
    }
    
    .info-item:hover {
        background-color: rgba(58, 123, 213, 0.1);
    }
    
    .btn-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }
    
    .btn-outline-secondary {
        border-color: #dee2e6;
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
    
    .custom-file-label {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .dropdown-menu {
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        border: none;
    }
    
    .dropdown-item {
        padding: 0.5rem 1.5rem;
    }
    
    .dropdown-item:hover {
        background-color: rgba(58, 123, 213, 0.1);
        color: var(--primary-color);
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
        
        // Update file name when file is selected
        $('#photoInput').on('change', function() {
            var fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').html(fileName);
        });
    });
</script>
@endpush