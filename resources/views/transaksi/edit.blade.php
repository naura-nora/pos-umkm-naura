@extends('layouts.adminlte')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <h4 class="card-header text-bold text-white" style="background: #001f3f; background: linear-gradient(to right, #001f3f, #003366);">Edit Transaksi - {{ $transaksi->kode }}</h4>

                <div class="card-body">
                    <!-- HAPUS FORM DALAM FORM - ini penyebab utama masalah -->
                    <form method="POST" action="{{ route('transaksi.update', $transaksi->id) }}" id="transaksiForm">
                        @csrf
                        @method('PUT')

                        <!-- Informasi Pelanggan -->
                        <div class="form-group row">
                            <label for="nama_pelanggan" class="col-md-4 col-form-label text-md-right">Nama Pelanggan</label>
                            <div class="col-md-6">
                                <input id="nama_pelanggan" type="text" class="form-control @error('nama_pelanggan') is-invalid @enderror" 
                                       name="nama_pelanggan" value="{{ old('nama_pelanggan', $transaksi->nama_pelanggan) }}" required autofocus>
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
                                       name="tanggal" value="{{ old('tanggal', $transaksi->tanggal->toDateString()) }}" required>
                                @error('tanggal')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Form untuk menambahkan produk -->
                        <div class="card mb-4">
                            <div class="card-header">Tambah/Edit Produk</div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label for="produk_id" class="col-md-4 col-form-label text-md-right">Produk</label>
                                    <div class="col-md-6">
                                        <select id="produk_id" class="form-control">
                                            <option value="">Pilih Produk</option>
                                            @foreach($produks as $produk)
                                                <option value="{{ $produk->id }}" 
                                                        data-kategori="{{ $produk->kategori->nama_kategori ?? 'Tidak ada kategori' }}" 
                                                        data-harga="{{ $produk->harga_produk }}"
                                                        data-stok="{{ $produk->stok_produk }}"
                                                        data-nama="{{ $produk->nama_produk }}">
                                                    {{ $produk->nama_produk }}
                                                </option>
                                            @endforeach
                                        </select>
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
                                        <th id="totalHarga">{{ $transaksi->total }}</th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <!-- Informasi Pembayaran -->
                        <div class="form-group row mt-4">
                            <label for="bayar" class="col-md-4 col-form-label text-md-right">Jumlah Bayar</label>
                            <div class="col-md-6">
                                <input id="bayar" type="number" class="form-control @error('bayar') is-invalid @enderror" 
                                       name="bayar" min="0" value="{{ old('bayar', $transaksi->bayar) }}" required>
                                @error('bayar')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Kembalian (Auto Fill) -->
                        <div class="form-group row">
                            <label for="kembalian" class="col-md-4 col-form-label text-md-right">Kembalian</label>
                            <div class="col-md-6">
                                <input id="kembalian" type="number" class="form-control" 
                                       value="{{ old('kembalian', $transaksi->kembalian) }}" readonly>
                            </div>
                        </div>

                        <!-- Hidden input untuk menyimpan data produk -->
                        <div id="produkInputsContainer">
                            <!-- Input tersembunyi untuk produk akan ditambahkan di sini -->
                        </div>

                        <div class="form-group row mb-0 mt-4">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Update Transaksi
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
    const produkSelect = document.getElementById('produk_id');
    const kategoriInput = document.getElementById('kategori');
    const hargaSatuanInput = document.getElementById('harga_satuan');
    const stokInput = document.getElementById('stok');
    const qtyInput = document.getElementById('qty');
    const maxQtySpan = document.getElementById('max-qty');
    const tambahProdukBtn = document.getElementById('tambahProdukBtn');
    const produkTable = document.getElementById('produkTable').getElementsByTagName('tbody')[0];
    const totalHargaEl = document.getElementById('totalHarga');
    const bayarInput = document.getElementById('bayar');
    const kembalianInput = document.getElementById('kembalian');
    const produkInputsContainer = document.getElementById('produkInputsContainer');
    const transaksiForm = document.getElementById('transaksiForm');
    
    // Ambil data dari controller (sudah diproses)
    let produkList = @json($produkList);
    let totalHarga = {{ $transaksi->total }};

    // Inisialisasi tabel dengan data existing
    updateProdukTable();

    // Auto fill ketika produk dipilih
    produkSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        kategoriInput.value = selectedOption.dataset.kategori || 'Tidak ada kategori';
        hargaSatuanInput.value = selectedOption.dataset.harga || '0';
        stokInput.value = selectedOption.dataset.stok || '0';
        maxQtySpan.textContent = selectedOption.dataset.stok || '0';
        qtyInput.max = selectedOption.dataset.stok || '';
        qtyInput.value = 1;
    });

    // Tambah produk
    tambahProdukBtn.addEventListener('click', function() {
        const selectedOption = produkSelect.options[produkSelect.selectedIndex];
        const produkId = produkSelect.value;
        const namaProduk = selectedOption.dataset.nama || '';
        const kategori = selectedOption.dataset.kategori || 'Tidak ada kategori';
        const hargaSatuan = parseFloat(selectedOption.dataset.harga) || 0;
        const stok = parseInt(selectedOption.dataset.stok) || 0;
        const qty = parseInt(qtyInput.value) || 0;

        if (!produkId) return alert('Pilih produk terlebih dahulu');
        if (qty < 1) return alert('Jumlah tidak valid');
        if (qty > stok) return alert('Jumlah melebihi stok');

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
        resetForm();
    });

    // Hapus produk
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('hapusProdukBtn')) {
            const index = parseInt(e.target.dataset.index);
            produkList.splice(index, 1);
            updateProdukTable();
        }
    });

    // Hitung kembalian
    bayarInput.addEventListener('input', updateKembalian);
    function updateKembalian() {
        const bayar = parseFloat(bayarInput.value) || 0;
        const kembalian = bayar - totalHarga;
        kembalianInput.value = kembalian > 0 ? kembalian : 0;
    }

    // Format Rupiah
    function formatRupiah(angka) {
        return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    // Reset form
    function resetForm() {
        produkSelect.value = '';
        kategoriInput.value = '';
        hargaSatuanInput.value = '';
        stokInput.value = '';
        qtyInput.value = 1;
        maxQtySpan.textContent = '0';
    }

    // Update tabel
    function updateProdukTable() {
        produkTable.innerHTML = '';
        totalHarga = 0;

        produkList.forEach((produk, index) => {
            totalHarga += produk.subtotal;
            const row = produkTable.insertRow();
            row.innerHTML = `
                <td>${produk.nama}</td>
                <td>${produk.kategori}</td>
                <td>${formatRupiah(produk.harga)}</td>
                <td>${produk.qty}</td>
                <td>${formatRupiah(produk.subtotal)}</td>
                <td>
                    <button type="button" class="btn btn-sm btn-danger hapusProdukBtn" data-index="${index}">
                        Hapus
                    </button>
                </td>
            `;
        });

        totalHargaEl.textContent = formatRupiah(totalHarga);
        updateKembalian();
        updateProdukInputs();
    }

    // Update input tersembunyi
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

    // Handle form submission
    transaksiForm.addEventListener('submit', function(e) {
        // Validasi minimal 1 produk
        if (produkList.length === 0) {
            e.preventDefault();
            alert('Tambahkan minimal 1 produk!');
            return false;
        }
        
        // Validasi pembayaran
        const bayar = parseFloat(bayarInput.value) || 0;
        if (bayar < totalHarga) {
            if (!confirm('Pembayaran kurang dari total. Transaksi akan disimpan sebagai Belum Lunas. Lanjutkan?')) {
                e.preventDefault();
                return false;
            }
        }
        
        return true;
    });
});
</script>
@endsection