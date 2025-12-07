@extends('layouts.adminlte')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header text-light text-bold" style="background: #001f3f; background: linear-gradient(to right, #001f3f, #003366);">
            <h3 class="card-title">Edit Retur</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('retur.update', $retur->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Info Transaksi -->
                <div class="form-group row">
                    <label class="col-md-4 col-form-label text-md-right">Transaksi</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" 
                               value="{{ $retur->transaksi->kode }} - {{ $retur->transaksi->nama_pelanggan }}" 
                               readonly>
                    </div>
                </div>

                <!-- Info Produk -->
                <div class="form-group row">
                    <label class="col-md-4 col-form-label text-md-right">Produk</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" 
                               value="{{ $retur->produk->nama_produk }}" 
                               readonly>
                    </div>
                </div>


                <!-- Jumlah Retur -->
                <!-- <div class="form-group row">
                    <label for="qty" class="col-md-4 col-form-label text-md-right">Jumlah Retur</label>
                    <div class="col-md-6">
                        <input id="qty" type="number" class="form-control @error('qty') is-invalid @enderror" 
                            name="qty" value="{{ old('qty', $retur->qty) }}" min="1" required>
                        @error('qty')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div> -->


                <!-- Jumlah Retur -->
                <div class="form-group row">
                    <label for="qty" class="col-md-4 col-form-label text-md-right">Jumlah Retur</label>
                    <div class="col-md-6">
                        <input id="qty" type="number" class="form-control @error('qty') is-invalid @enderror" 
                            name="qty" value="{{ old('qty', $retur->qty) }}" min="1" required readonly>
                        @error('qty')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <!-- Alasan Retur -->
                <div class="form-group row">
                    <label for="alasan" class="col-md-4 col-form-label text-md-right">Alasan Retur</label>
                    <div class="col-md-6">
                        <textarea id="alasan" class="form-control @error('alasan') is-invalid @enderror" 
                                  name="alasan" rows="3" required>{{ old('alasan', $retur->alasan) }}</textarea>
                        @error('alasan')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

    

                <div class="form-group row mb-0">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-primary">Update Retur</button>
                        <a href="{{ route('retur.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection