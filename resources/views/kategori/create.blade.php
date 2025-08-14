@extends('layouts.adminlte')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header text-white text-bold" style="background: #001f3f; background: linear-gradient(to right, #001f3f, #003366);">
            <h3 class="card-title">Tambah Kategori Baru</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('kategori.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Nama Kategori</label>
                    <input type="text" name="nama_kategori" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('kategori.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection