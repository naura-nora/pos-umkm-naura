@extends('layouts.adminlte')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header bg-warning">
            <h3 class="card-title">Edit Kategori</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('kategori.update', $kategori->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label>Nama Kategori</label>
                    <input type="text" name="nama_kategori" class="form-control" 
                           value="{{ $kategori->nama_kategori }}" required>
                </div>
                
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>
</div>
@endsection