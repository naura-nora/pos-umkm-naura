@extends('layouts.adminlte')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-white text-bold" style="background: #001f3f; background: linear-gradient(to right, #001f3f, #003366);">
                    <h3 class="card-title m-0">Ajukan Retur Barang</h3>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                    
                    <form action="{{ route('retur.store') }}" method="POST">
                        @csrf

                        <!-- Pilih Transaksi (Searchable Dropdown) -->
                        <div class="form-group row mb-4">
                            <label for="transaksi_input" class="col-md-4 col-form-label text-md-right">Transaksi</label>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" id="transaksi_input" 
                                        class="form-control @error('transaksi_id') is-invalid @enderror" 
                                        placeholder="Klik tombol cari untuk memilih transaksi"
                                        readonly required>
                                    
                                    <input type="hidden" id="transaksi_id" name="transaksi_id">
                                    
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button" 
                                                id="transaksiDropdownBtn" data-toggle="dropdown" 
                                                aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-search"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right" id="transaksiDropdown" style="min-width: 400px; padding: 10px;">
                                            <div class="form-group">
                                                <input type="text" class="form-control" 
                                                    placeholder="Cari kode transaksi atau nama pelanggan" id="searchTransaksi">
                                            </div>
                                            <div id="transaksiList" style="max-height: 250px; overflow-y: auto;">
                                                @foreach($transaksis as $trans)
                                                    <a class="dropdown-item transaksi-item" href="#" 
                                                        data-id="{{ $trans->id }}"
                                                        data-kode="{{ $trans->kode }}"
                                                        data-pelanggan="{{ $trans->nama_pelanggan }}"
                                                        data-tanggal="{{ $trans->tanggal }}">
                                                        <div>
                                                            <strong>{{ $trans->kode }}</strong>
                                                            <br>
                                                            <small class="text-muted">
                                                                Pelanggan: {{ $trans->nama_pelanggan }} | 
                                                                Tanggal: {{ date('d/m/Y', strtotime($trans->tanggal)) }}
                                                            </small>
                                                        </div>
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @error('transaksi_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Pilih Produk -->
                        <div class="form-group row mb-4">
                            <label for="produk_input" class="col-md-4 col-form-label text-md-right">Produk</label>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" id="produk_input" 
                                        class="form-control @error('produk_id') is-invalid @enderror" 
                                        placeholder="Pilih transaksi terlebih dahulu"
                                        readonly required>
                                    
                                    <input type="hidden" id="produk_id" name="produk_id">
                                    
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button" 
                                                id="produkDropdownBtn" data-toggle="dropdown" 
                                                aria-haspopup="true" aria-expanded="false" disabled>
                                            <i class="fas fa-search"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right" id="produkDropdown" style="min-width: 400px; padding: 10px;">
                                            <div class="form-group">
                                                <input type="text" class="form-control" 
                                                    placeholder="Cari nama produk" id="searchProduk">
                                            </div>
                                            <div id="produkList" style="max-height: 250px; overflow-y: auto;">
                                                <div class="text-center p-3 text-muted">
                                                    Pilih transaksi terlebih dahulu
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @error('produk_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Jumlah Retur -->
                        <div class="form-group row mb-4">
                            <label for="qty" class="col-md-4 col-form-label text-md-right">Jumlah Retur</label>
                            <div class="col-md-6">
                                <input id="qty" type="number" class="form-control @error('qty') is-invalid @enderror" 
                                    name="qty" min="1" value="1" required>
                                <small class="form-text text-muted">Jumlah retur tidak boleh melebihi jumlah pembelian.</small>
                                <div id="qtyError" class="text-danger mt-1" style="display: none;">
                                    Jumlah yang Anda masukkan melebihi jumlah pembelian!
                                </div>
                                @error('qty')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Alasan Retur -->
                        <div class="form-group row mb-4">
                            <label for="alasan" class="col-md-4 col-form-label text-md-right">Alasan Retur</label>
                            <div class="col-md-6">
                                <textarea id="alasan" class="form-control @error('alasan') is-invalid @enderror" 
                                    name="alasan" rows="3" required placeholder="Masukkan alasan retur">{{ old('alasan') }}</textarea>
                                @error('alasan')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>



                        <!-- Metode Pengembalian -->
                        <div class="form-group row mb-4">
                            <label class="col-md-4 col-form-label text-md-right">Metode Pengembalian</label>
                            <div class="col-md-6">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="metode_pengembalian" id="metode_barang" value="barang_baru" {{ old('metode_pengembalian') == 'barang_baru' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="metode_barang">Diganti dengan barang baru</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="metode_pengembalian" id="metode_uang" value="uang_kembali" {{ old('metode_pengembalian') == 'uang_kembali' || !old('metode_pengembalian') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="metode_uang">Uang dikembalikan</label>
                                </div>

                                @error('metode_pengembalian')
                                    <span class="invalid-feedback d-block mt-1" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Pesan Error Stok -->
                        <div id="stokError" class="col-md-6 offset-md-4 text-danger mt-2" style="display: none;">
                            Stok habis. Silakan isi stok terlebih dahulu untuk menggunakan opsi ini.
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                                    Ajukan Retur
                                </button>
                                <a href="{{ route('retur.index') }}" class="btn btn-secondary">
                                    Batal
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Variabel untuk menyimpan data produk
    var produkData = [];
    var maxQty = 0;
    var stokTersedia = 0; // ✅ Tambahkan variabel stok

    // Pencarian Transaksi
    $('#searchTransaksi').on('input', function() {
        const searchText = $(this).val().toLowerCase();
        $('#transaksiList .transaksi-item').each(function() {
            const kode = $(this).data('kode').toLowerCase();
            const pelanggan = $(this).data('pelanggan').toLowerCase();
            if (kode.includes(searchText) || pelanggan.includes(searchText)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    // Pilih Transaksi
    $(document).on('click', '#transaksiList .transaksi-item', function(e) {
        e.preventDefault();
        const transaksiId = $(this).data('id');
        const kode = $(this).data('kode');
        const pelanggan = $(this).data('pelanggan');
        
        $('#transaksi_id').val(transaksiId);
        $('#transaksi_input').val(kode + ' - ' + pelanggan);
        
        // Load produk dari transaksi yang dipilih
        loadProdukDariTransaksi(transaksiId);
        
        // Enable dropdown produk
        $('#produkDropdownBtn').prop('disabled', false);
        $('#produk_input').attr('placeholder', 'Klik tombol cari untuk memilih produk');
    });

    // Fungsi untuk memuat produk dari transaksi
    function loadProdukDariTransaksi(transaksiId) {
        $.ajax({
            url: '{{ route("retur.get-produk", ["transaksiId" => ":transaksiId"]) }}'.replace(':transaksiId', transaksiId),
            type: 'GET',
            success: function(response) {
                let produkHtml = '';
                if (response.success && response.data.length > 0) {
                    produkData = response.data;
                    response.data.forEach(function(produk) {
                        produkHtml += `
                            <a class="dropdown-item produk-item" href="#" 
                                data-id="${produk.id}"
                                data-nama="${produk.nama_produk}"
                                data-qty="${produk.qty}"
                                data-harga="${produk.harga}"
                                data-stok_tersedia="${produk.stok_tersedia}">
                                <div>
                                    <strong>${produk.nama_produk}</strong>
                                    <br>
                                    <small class="text-muted">
                                        Jumlah Beli: ${produk.qty} | 
                                        Harga: Rp ${parseInt(produk.harga).toLocaleString('id-ID')}
                                    </small>
                                </div>
                            </a>
                        `;
                    });
                } else {
                    produkHtml = '<div class="dropdown-item text-muted">Tidak ada produk ditemukan untuk transaksi ini</div>';
                }
                $('#produkList').html(produkHtml);
                
                // Reset produk input & stok
                $('#produk_id').val('');
                $('#produk_input').val('');
                stokTersedia = 0;
                $('#stokError').hide();
                checkFormValidity();
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                $('#produkList').html('<div class="dropdown-item text-danger">Error memuat produk: ' + error + '</div>');
            }
        });
    }

    // Pencarian Produk
    $(document).on('input', '#searchProduk', function() {
        const searchText = $(this).val().toLowerCase();
        $('#produkList .produk-item').each(function() {
            const nama = $(this).data('nama').toLowerCase();
            if (nama.includes(searchText)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    // Pilih Produk
    $(document).on('click', '#produkList .produk-item', function(e) {
        e.preventDefault();
        const produkId = $(this).data('id');
        const produkNama = $(this).data('nama');
        maxQty = $(this).data('qty');
        stokTersedia = $(this).data('stok_tersedia'); // ✅ Ambil stok dari data attribute
        
        $('#produk_id').val(produkId);
        $('#produk_input').val(produkNama);
        
        // Set max value untuk input quantity
        $('#qty').attr('max', maxQty);
        $('#qty').attr('data-max', maxQty);
        $('#qty').val(1);
        $('#qtyError').hide();
        $('#stokError').hide(); // Reset error stok
        
        checkFormValidity();
    });

    // Validasi Jumlah
    $('#qty').on('input', function() {
        const inputQty = parseInt($(this).val()) || 0;
        const maxQty = parseInt($(this).attr('data-max') || 0);
        
        if (inputQty > maxQty) {
            $('#qtyError').show();
            $('#submitBtn').prop('disabled', true);
        } else {
            $('#qtyError').hide();
            checkFormValidity();
        }
    });

    // Cek validitas form
    function checkFormValidity() {
        const transaksiSelected = $('#transaksi_id').val() !== '';
        const produkSelected = $('#produk_id').val() !== '';
        const qtyValid = parseInt($('#qty').val()) <= parseInt($('#qty').attr('data-max') || 0);
        const alasanFilled = $('#alasan').val().trim() !== '';
        const metodeSelected = $('input[name="metode_pengembalian"]:checked').length > 0;
        const metodeBarang = $('input[name="metode_pengembalian"]:checked').val() === 'barang_baru';

        // ✅ Validasi stok hanya jika pilih "barang_baru"
        let stokValid = true;
        if (metodeBarang) {
            if (stokTersedia <= 0) {
                stokValid = false;
                $('#stokError').show();
            } else {
                $('#stokError').hide();
            }
        } else {
            $('#stokError').hide();
        }

        if (transaksiSelected && produkSelected && qtyValid && alasanFilled && stokValid) {
            $('#submitBtn').prop('disabled', false);
        } else {
            $('#submitBtn').prop('disabled', true);
        }
    }

    // Validasi saat input berubah
    $('#alasan').on('input', checkFormValidity);
    $('input[name="metode_pengembalian"]').on('change', checkFormValidity);
});
</script>
@endsection