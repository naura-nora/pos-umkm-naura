@extends('layouts.adminlte')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Edit Produk</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('produk.update', $produk->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label>Nama Produk</label>
                    <input type="text" class="form-control" name="nama_produk" value="{{ $produk->nama_produk }}" required>
                </div>
                <div class="form-group">
                    <label>Harga</label>
                    <input type="number" class="form-control" name="harga_produk" value="{{ $produk->harga_produk }}" required>
                </div>
                <div class="form-group">
                    <label>Stok</label>
                    <input type="number" class="form-control" name="stok_produk" value="{{ $produk->stok_produk }}" required>
                </div>
                <div class="form-group">
                    <label>Kategori</label>
                    <select class="form-control" name="kategori_id" required>
                        @foreach($kategori as $kat)
                            <option value="{{ $kat->id }}" {{ $produk->kategori_id == $kat->id ? 'selected' : '' }}>
                                {{ $kat->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Gambar Produk</label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="gambar_produk" name="gambar_produk">
                        <label class="custom-file-label" for="gambar_produk">Pilih file gambar</label>
                    </div>
                    @if($produk->gambar_produk)
                        <div class="mt-2">
                            <img src="{{ asset('adminlte/img/' . $produk->gambar_produk) }}" alt="Gambar Produk" width="150" class="img-thumbnail">
                            <div class="form-check mt-2">
                                <!-- <input class="form-check-input" type="checkbox" name="hapus_gambar" id="hapus_gambar">
                                <label class="form-check-label" for="hapus_gambar">
                                    Hapus gambar saat ini
                                </label> -->
                            </div>
                        </div>
                    @endif
                    <small class="form-text text-muted">format gambar .png/ .jpg/ .jpeg</small>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Update</button>
                <a href="{{ route('produk.index') }}" class="btn btn-secondary mt-3">Kembali</a>
            </form>
        </div>
    </div>
</div>

<!-- Script untuk menampilkan nama file di input -->
@section('scripts')
<script>
    // Menampilkan nama file di input file
    document.querySelector('.custom-file-input').addEventListener('change', function(e) {
        var fileName = document.getElementById("gambar_produk").files[0].name;
        var nextSibling = e.target.nextElementSibling;
        nextSibling.innerText = fileName;
    });
</script>
@endsection
@endsection