@extends('layouts.adminlte')

@section('title', 'Tambah Produk Baru')

@section('content_header')
    <h1>Tambah Produk Baru</h1>
@stop

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h3 class="card-title">Form Tambah Produk</h3>
            <div class="card-tools">
                <a href="{{ route('produk.index') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
            </div>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> Terjadi kesalahan input.<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('produk.store') }}" method="POST" enctype="multipart/form-data" id="produkForm">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama_produk">Nama Produk <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_produk') is-invalid @enderror" 
                                   id="nama_produk" name="nama_produk" value="{{ old('nama_produk') }}" 
                                   placeholder="Masukkan nama produk" required>
                            @error('nama_produk')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="harga_produk">Harga (Rp) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('harga_produk') is-invalid @enderror" 
                                   id="harga_produk" name="harga_produk" value="{{ old('harga_produk') }}" 
                                   min="0" step="100" placeholder="Masukkan harga" required>
                            @error('harga_produk')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="stok_produk">Stok <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('stok_produk') is-invalid @enderror" 
                                   id="stok_produk" name="stok_produk" value="{{ old('stok_produk') }}" 
                                   min="0" placeholder="Masukkan jumlah stok" required>
                            @error('stok_produk')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="kategori_id">Kategori <span class="text-danger">*</span></label>
                            <select class="form-control select2 @error('kategori_id') is-invalid @enderror" 
                                    id="kategori_id" name="kategori_id" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($kategori as $kat)
                                    <option value="{{ $kat->id }}" {{ old('kategori_id') == $kat->id ? 'selected' : '' }}>
                                        {{ $kat->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kategori_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="gambar_produk">Gambar Produk</label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input @error('gambar_produk') is-invalid @enderror" 
                               id="gambar_produk" name="gambar_produk" accept="image/*">
                        <label class="custom-file-label" for="gambar_produk">Pilih file gambar...</label>
                        @error('gambar_produk')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <small class="form-text text-muted">Format: jpeg, png, jpg, gif (max 2MB)</small>
                    <div class="mt-2" id="image-preview"></div>
                </div>

                <div class="form-group text-center">
                    <button type="submit" class="btn btn-primary btn-lg mr-2">
                        <i class="fas fa-save mr-1"></i> Simpan Produk
                    </button>
                    <button type="reset" class="btn btn-outline-secondary btn-lg">
                        <i class="fas fa-undo mr-1"></i> Reset
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--single {
        height: calc(2.25rem + 2px);
        padding: .375rem .75rem;
    }
    .card-header {
        border-bottom: none;
    }
    #image-preview {
        border: 1px dashed #ddd;
        padding: 10px;
        text-align: center;
        display: none;
    }
    #image-preview img {
        max-width: 100%;
        max-height: 200px;
    }
</style>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize Select2
        $('.select2').select2({
            placeholder: '-- Pilih Kategori --',
            width: '100%'
        });

        // Show filename when file selected
        $('.custom-file-input').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
            
            // Image preview
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#image-preview').html(
                        `<img src="${e.target.result}" class="img-thumbnail" alt="Preview">`
                    ).show();
                }
                reader.readAsDataURL(file);
            } else {
                $('#image-preview').hide().empty();
            }
        });

        // Form validation
        $('#produkForm').on('submit', function(e) {
            let isValid = true;
            $(this).find('[required]').each(function() {
                if (!$(this).val()) {
                    $(this).addClass('is-invalid');
                    isValid = false;
                } else {
                    $(this).removeClass('is-invalid');
                }
            });

            if (!isValid) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Harap isi semua field yang wajib diisi!',
                });
            }
        });

        // Reset form
        $('[type="reset"]').on('click', function() {
            $('#image-preview').hide().empty();
            $('.custom-file-label').html('Pilih file gambar...');
            $('.is-invalid').removeClass('is-invalid');
        });
    });
</script>
@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        timer: 3000,
        showConfirmButton: false
    });
</script>
@endif
@if(session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: '{{ session('error') }}'
    });
</script>
@endif
@endsection