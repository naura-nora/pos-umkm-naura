@extends('layouts.adminlte')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <h3 class="card-header text-white text-bold" style="background: #001f3f; background: linear-gradient(to right, #001f3f, #003366);">Transaksi Baru</h3>

                <div class="card-body">
                    <form method="POST" action="{{ route('transaksi.store') }}">
                        @csrf

                        <!-- Informasi Pelanggan -->
                            <div class="form-group row">
                                <label for="nama_pelanggan" class="col-md-4 col-form-label text-md-right">Nama Pelanggan</label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <!-- INPUT BIASA (BUKAN SELECT) -->
                                        <input type="text" id="nama_pelanggan" 
                                            class="form-control @error('nama_pelanggan') is-invalid @enderror" 
                                            name="nama_pelanggan" 
                                            placeholder="Klik tombol cari untuk memilih pelanggan"
                                            value="{{ old('nama_pelanggan') }}"
                                            required autofocus readonly>
                                        
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="button" 
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-search"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right" style="min-width: 350px; padding: 10px;">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" 
                                                        placeholder="Cari nama pelanggan" id="searchPelanggan">
                                                </div>
                                                <div id="pelangganList" style="max-height: 250px; overflow-y: auto;">
                                                    @foreach($pelanggans as $pelanggan)
                                                        <a class="dropdown-item" href="#" 
                                                        data-value="{{ $pelanggan->name }}"
                                                        style="padding: 8px 12px; border-bottom: 1px solid #eee;">
                                                            <div>
                                                                <strong>{{ $pelanggan->name }}</strong>
                                                                <br>
                                                                <!-- <small class="text-muted">ID: {{ $pelanggan->id }}</small> -->
                                                                @if($pelanggan->kode_pelanggan)
                                                                    <small class="text-muted">Kode: {{ $pelanggan->kode_pelanggan }}</small>
                                                                @else
                                                                    <small class="text-muted text-danger">Kode belum tersedia</small>
                                                                @endif
                                                            </div>
                                                        </a>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
        
                                    
                                    @error('nama_pelanggan')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                        <!-- Tanggal Transaksi -->
                        <div class="form-group row">
                            <label for="tanggal" class="col-md-4 col-form-label text-md-right">Tanggal Transaksi</label>
                            <div class="col-md-6">
                                <input id="tanggal" type="date" class="form-control @error('tanggal') is-invalid @enderror" 
                                       name="tanggal" value="{{ old('tanggal', now()->toDateString()) }}" required>
                                @error('tanggal')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Form untuk menambahkan produk -->
                        <div class="card mb-4">
                            <div class="card-header">Tambah Produk</div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label for="produk_input" class="col-md-4 col-form-label text-md-right">Produk</label>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <!-- INPUT BIASA UNTUK PRODUK -->
                                            <input type="text" id="produk_input" 
                                                class="form-control" 
                                                placeholder="Klik tombol cari untuk memilih produk"
                                                readonly>
                                            
                                            <!-- Hidden input untuk menyimpan ID produk -->
                                            <input type="hidden" id="produk_id" name="produk_id">
                                            
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary" type="button" 
                                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right" style="min-width: 400px; padding: 10px;">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" 
                                                            placeholder="Cari nama produk..." id="searchProduk">
                                                    </div>
                                                    <div id="produkDropdownList" style="max-height: 250px; overflow-y: auto;">
                                                        @foreach($produks as $produk)
                                                            <a class="dropdown-item" href="#" 
                                                            data-id="{{ $produk->id }}"
                                                            data-nama="{{ $produk->nama_produk }}"
                                                            data-kategori="{{ $produk->kategori->nama_kategori ?? 'Tidak ada kategori' }}"
                                                            data-harga="{{ $produk->harga_produk }}"
                                                            data-stok="{{ $produk->stok_produk }}"
                                                            style="padding: 8px 12px; border-bottom: 1px solid #eee;">
                                                                <div>
                                                                    <strong>{{ $produk->nama_produk }}</strong>
                                                                    <br>
                                                                    <small class="text-muted">
                                                                        Kategori: {{ $produk->kategori->nama_kategori ?? 'Tidak ada kategori' }} | 
                                                                        Harga: Rp {{ number_format($produk->harga_produk, 0, ',', '.') }} | 
                                                                        Stok: {{ $produk->stok_produk }}
                                                                    </small>
                                                                </div>
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="kategori" class="col-md-4 col-form-label text-md-right">Kategori</label>
                                    <div class="col-md-6">
                                        <input id="kategori" type="text" class="form-control" readonly>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="harga_satuan" class="col-md-4 col-form-label text-md-right">Harga Satuan</label>
                                    <div class="col-md-6">
                                        <input id="harga_satuan" type="number" class="form-control" readonly>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="stok" class="col-md-4 col-form-label text-md-right">Stok Tersedia</label>
                                    <div class="col-md-6">
                                        <input id="stok" type="number" class="form-control" readonly>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="qty" class="col-md-4 col-form-label text-md-right">Jumlah</label>
                                    <div class="col-md-6">
                                        <input id="qty" type="number" class="form-control" min="1" value="1">
                                        <small class="text-muted">Maksimal: <span id="max-qty">0</span></small>
                                    </div>
                                </div>

                                <div class="form-group row mb-0">
                                    <div class="col-md-6 offset-md-4">
                                        <button type="button" id="tambahProdukBtn" class="btn btn-primary">
                                            Tambah Produk
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tabel produk yang dipilih -->
                        <div class="table-responsive">
                            <table class="table table-bordered" id="produkTable">
                                <thead>
                                    <tr>
                                        <th>Nama Produk</th>
                                        <th>Kategori</th>
                                        <th>Harga Satuan</th>
                                        <th>Jumlah</th>
                                        <th>Subtotal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Produk akan ditambahkan di sini melalui JavaScript -->
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="4" class="text-right">Total</th>
                                        <th id="totalHarga">0</th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <!-- Informasi Pembayaran -->
                        <!-- Metode Pembayaran -->
                        <div class="form-group row">
                            <label for="metode_pembayaran" class="col-md-4 col-form-label text-md-right">Metode Pembayaran</label>
                            <div class="col-md-6">
                                <select id="metode_pembayaran" class="form-control @error('metode_pembayaran') is-invalid @enderror" name="metode_pembayaran" required>
                                    <option value="">Pilih Metode Pembayaran</option>
                                    <option value="cash" {{ old('metode_pembayaran') == 'cash' ? 'selected' : '' }}>Cash</option>
                                    <option value="qris" {{ old('metode_pembayaran') == 'qris' ? 'selected' : '' }}>QRIS</option>
                                    <option value="debit" {{ old('metode_pembayaran') == 'debit' ? 'selected' : '' }}>Debit</option>
                                </select>
                                @error('metode_pembayaran')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        
                        <!-- Jumlah Bayar -->
                        <div class="form-group row mt-4">
                            <label for="bayar" class="col-md-4 col-form-label text-md-right">Jumlah Bayar</label>
                            <div class="col-md-6">
                                <input id="bayar" type="number" class="form-control @error('bayar') is-invalid @enderror" name="bayar" min="0" required>
                                @error('bayar')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Nomor Telepon -->
                        <div class="form-group row" id="nomor_telepon_container" style="display: none;">
                            <label for="nomor_telepon" class="col-md-4 col-form-label text-md-right">Nomor Telepon</label>
                            <div class="col-md-6">
                                <input id="nomor_telepon" type="text" class="form-control @error('nomor_telepon') is-invalid @enderror" 
                                    name="nomor_telepon" value="{{ old('nomor_telepon') }}" 
                                    placeholder="Masukkan nomor telepon">
                                @error('nomor_telepon')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <small class="form-text text-muted">*Wajib diisi untuk pembayaran QRIS dan Debit</small>
                            </div>
                        </div>

                        <!-- Kembalian (Auto Fill) -->
                        <div class="form-group row">
                            <label for="kembalian" class="col-md-4 col-form-label text-md-right">Kembalian</label>
                            <div class="col-md-6">
                                <input id="kembalian" type="number" class="form-control" readonly>
                            </div>
                        </div>

                        <!-- Hidden input untuk menyimpan data produk -->
                        <div id="produkInputsContainer">
                            <!-- Input tersembunyi untuk produk akan ditambahkan di sini -->
                        </div>

                        <div class="form-group row mb-0 mt-4">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Simpan Transaksi
                                </button>
                                <a href="{{ route('transaksi.index') }}" class="btn btn-secondary">Kembali</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // ====== VARIABEL GLOBAL ======
    const namaPelangganInput = document.getElementById('nama_pelanggan');
    const searchPelangganInput = document.getElementById('searchPelanggan');
    const pelangganItems = document.querySelectorAll('#pelangganList .dropdown-item');

    const produkInput = document.getElementById('produk_input');
    const produkIdInput = document.getElementById('produk_id');
    const searchProdukInput = document.getElementById('searchProduk');
    const produkItems = document.querySelectorAll('#produkDropdownList .dropdown-item');
    const kategoriInput = document.getElementById('kategori');
    const hargaSatuanInput = document.getElementById('harga_satuan');
    const stokInput = document.getElementById('stok');
    const maxQtySpan = document.getElementById('max-qty');
    const qtyInput = document.getElementById('qty');
    const tambahProdukBtn = document.getElementById('tambahProdukBtn');
    const produkTable = document.getElementById('produkTable').getElementsByTagName('tbody')[0];
    const totalHargaEl = document.getElementById('totalHarga');
    const bayarInput = document.getElementById('bayar');
    const kembalianInput = document.getElementById('kembalian');
    const produkInputsContainer = document.getElementById('produkInputsContainer');
    const metodePembayaran = document.getElementById('metode_pembayaran');
    const nomorTeleponContainer = document.getElementById('nomor_telepon_container');
    const nomorTeleponInput = document.getElementById('nomor_telepon');

    let produkList = [];
    let totalHarga = 0;

    // ====== FITUR CARI PELANGGAN ======
    if (searchPelangganInput) {
        searchPelangganInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            pelangganItems.forEach(item => {
                const text = item.textContent.toLowerCase();
                item.style.display = text.includes(searchTerm) ? 'block' : 'none';
            });
        });
    }

    pelangganItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const selectedValue = this.getAttribute('data-value');
            namaPelangganInput.value = selectedValue;
            $(this).closest('.dropdown-menu').prev().dropdown('toggle');
            if (searchPelangganInput) searchPelangganInput.value = '';
            pelangganItems.forEach(item => item.style.display = 'block');
        });
    });

    // ====== FITUR CARI PRODUK ======
    if (searchProdukInput) {
        searchProdukInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            produkItems.forEach(item => {
                const text = item.textContent.toLowerCase();
                item.style.display = text.includes(searchTerm) ? 'block' : 'none';
            });
        });
    }

    produkItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const produkId = this.getAttribute('data-id');
            const produkNama = this.getAttribute('data-nama');
            const produkKategori = this.getAttribute('data-kategori');
            const produkHarga = this.getAttribute('data-harga');
            const produkStok = this.getAttribute('data-stok');

            produkInput.value = produkNama;
            produkIdInput.value = produkId;
            kategoriInput.value = produkKategori;
            hargaSatuanInput.value = produkHarga;
            stokInput.value = produkStok;
            maxQtySpan.textContent = produkStok;
            qtyInput.max = produkStok;
            qtyInput.value = 1;

            $(this).closest('.dropdown-menu').prev().dropdown('toggle');
            if (searchProdukInput) searchProdukInput.value = '';
            produkItems.forEach(item => item.style.display = 'block');
        });
    });

    // ====== RESET FORM PRODUK ======
    function resetProdukForm() {
        produkInput.value = '';
        produkIdInput.value = '';
        kategoriInput.value = '';
        hargaSatuanInput.value = '';
        stokInput.value = '';
        maxQtySpan.textContent = '0';
        qtyInput.value = 1;
        qtyInput.max = '';
    }

    // ====== TAMBAH PRODUK KE TABEL ======
    tambahProdukBtn.addEventListener('click', function() {
        const produkId = produkIdInput.value;
        const namaProduk = produkInput.value;
        const kategori = kategoriInput.value;
        const hargaSatuan = parseFloat(hargaSatuanInput.value) || 0;
        const stok = parseInt(stokInput.value) || 0;
        const qty = parseInt(qtyInput.value) || 0;

        if (!produkId) {
            Swal.fire({
                icon: 'error',
                title: 'Pilih Produk',
                text: 'Silakan pilih produk terlebih dahulu.',
                confirmButtonColor: '#3085d6',
            });
            return;
        }

        if (qty < 1) {
            Swal.fire({
                icon: 'error',
                title: 'Jumlah Tidak Valid',
                text: 'Jumlah minimal 1.',
                confirmButtonColor: '#3085d6',
            });
            return;
        }

        if (qty > stok) {
            Swal.fire({
                icon: 'error',
                title: 'Stok Tidak Cukup',
                text: `Stok hanya tersedia ${stok}.`,
                confirmButtonColor: '#3085d6',
            });
            return;
        }

        const existingIndex = produkList.findIndex(p => p.id === produkId);

        if (existingIndex >= 0) {
            produkList[existingIndex].qty += qty;
            produkList[existingIndex].subtotal = produkList[existingIndex].qty * hargaSatuan;
        } else {
            produkList.push({
                id: produkId,
                nama: namaProduk,
                kategori: kategori,
                harga: hargaSatuan,
                qty: qty,
                subtotal: qty * hargaSatuan
            });
        }

        updateProdukTable();
        resetProdukForm();
    });

    // ====== UPDATE TABEL PRODUK ======
    function updateProdukTable() {
        produkTable.innerHTML = '';
        totalHarga = 0;

        produkList.forEach((produk, index) => {
            totalHarga += produk.subtotal;
            const row = produkTable.insertRow();
            row.innerHTML = `
                <td>${produk.nama}</td>
                <td>${produk.kategori}</td>
                <td>Rp ${formatRupiah(produk.harga)}</td>
                <td>${produk.qty}</td>
                <td>Rp ${formatRupiah(produk.subtotal)}</td>
                <td>
                    <button type="button" class="btn btn-sm btn-danger hapusProdukBtn" data-index="${index}">
                        Hapus
                    </button>
                </td>
            `;
        });

        totalHargaEl.textContent = `Rp ${formatRupiah(totalHarga)}`;
        updateKembalian();
        updateProdukInputs();
    }

    // ====== HAPUS PRODUK ======
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('hapusProdukBtn')) {
            const index = parseInt(e.target.dataset.index);
            produkList.splice(index, 1);
            updateProdukTable();
        }
    });

    // ====== FORMAT RUPIAH ======
    function formatRupiah(angka) {
        return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    // ====== UPDATE KEMBALIAN ======
    function updateKembalian() {
        const metode = metodePembayaran.value;
        if (metode === 'qris' || metode === 'debit') {
            kembalianInput.value = '-';
        } else {
            const bayar = parseFloat(bayarInput.value) || 0;
            kembalianInput.value = bayar - totalHarga;
        }
    }

    bayarInput.addEventListener('input', updateKembalian);

    // ====== UPDATE INPUT HIDDEN UNTUK SUBMIT ======
    function updateProdukInputs() {
        produkInputsContainer.innerHTML = '';

        produkList.forEach((produk, index) => {
            produkInputsContainer.innerHTML += `
                <input type="hidden" name="produk[${index}][id]" value="${produk.id}">
                <input type="hidden" name="produk[${index}][qty]" value="${produk.qty}">
                <input type="hidden" name="produk[${index}][harga]" value="${produk.harga}">
                <input type="hidden" name="produk[${index}][subtotal]" value="${produk.subtotal}">
            `;
        });

        produkInputsContainer.innerHTML += `
            <input type="hidden" name="total_harga" value="${totalHarga}">
        `;
    }

    // ====== TOGGLE METODE PEMBAYARAN ======
    function toggleMetodePembayaran() {
        const metode = metodePembayaran.value;

        if (metode === 'qris' || metode === 'debit') {
            // Tampilkan nomor telepon
            nomorTeleponContainer.style.display = 'flex';
            nomorTeleponInput.setAttribute('required', 'required');

            // Set bayar = total, kembalian = -
            bayarInput.value = totalHarga;
            bayarInput.readOnly = true;
            kembalianInput.value = '-';
        } else {
            // Sembunyikan nomor telepon
            nomorTeleponContainer.style.display = 'none';
            nomorTeleponInput.removeAttribute('required');
            nomorTeleponInput.value = '';

            // Reset bayar & kembalian
            bayarInput.value = '';
            bayarInput.readOnly = false;
            kembalianInput.value = '';
        }
    }

    // Trigger saat load
    toggleMetodePembayaran();

    // Trigger saat berubah
    metodePembayaran.addEventListener('change', toggleMetodePembayaran);

    // ====== VALIDASI & SUBMIT FORM ======
    document.querySelector('form').addEventListener('submit', async function(e) {
        e.preventDefault();

        if (!namaPelangganInput.value) {
            Swal.fire({
                icon: 'error',
                title: 'Pelanggan Belum Dipilih',
                text: 'Silakan pilih pelanggan terlebih dahulu.',
                confirmButtonColor: '#3085d6',
            });
            return;
        }

        if (produkList.length === 0) {
            Swal.fire({
                icon: 'error',
                title: 'Tidak Ada Produk',
                text: 'Tambahkan minimal 1 produk.',
                confirmButtonColor: '#3085d6',
            });
            return;
        }

        const metode = metodePembayaran.value;
        const nomorTelepon = nomorTeleponInput.value;

        if ((metode === 'qris' || metode === 'debit') && !nomorTelepon) {
            Swal.fire({
                icon: 'error',
                title: 'Nomor Telepon Wajib',
                text: 'Untuk QRIS/Debit, nomor telepon harus diisi.',
                confirmButtonColor: '#3085d6',
            });
            nomorTeleponInput.focus();
            return;
        }

        const bayar = parseFloat(bayarInput.value) || 0;
        const status = bayar >= totalHarga ? 'Lunas' : 'Belum Lunas';

        if (status === 'Belum Lunas') {
            const result = await Swal.fire({
                title: 'Pembayaran Kurang!',
                html: `
                    <p>Transaksi akan disimpan dengan status <strong>Belum Lunas</strong></p>
                    <p>Kekurangan: <b>Rp ${formatRupiah(totalHarga - bayar)}</b></p>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '✅ Simpan Transaksi',
                cancelButtonText: '❌ Batal',
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                reverseButtons: true,
                focusCancel: true
            });

            if (!result.isConfirmed) return;
        }

        // Tambahkan input hidden
        const statusInput = document.createElement('input');
        statusInput.type = 'hidden';
        statusInput.name = 'status';
        statusInput.value = status;
        this.appendChild(statusInput);

        const totalInput = document.createElement('input');
        totalInput.type = 'hidden';
        totalInput.name = 'total_harga';
        totalInput.value = totalHarga;
        this.appendChild(totalInput);

        // Kirim form
        Swal.fire({
            title: 'Menyimpan...',
            html: 'Sedang memproses data transaksi.',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        try {
            const formData = new FormData(this);
            const response = await fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (response.ok && data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: data.message,
                    confirmButtonColor: '#3085d6',
                }).then(() => {
                    window.location.href = data.redirect_url;
                });
            } else {
                throw new Error(data.message || 'Terjadi kesalahan.');
            }
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: error.message,
                confirmButtonColor: '#3085d6',
            });
        }
    });
});
</script>
@endsection