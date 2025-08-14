@extends('layouts.adminlte')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h5>Tambah Laporan Keuangan</h5>
        </div>
        
        <div class="card-body">
            <form action="{{ route('financial-reports.store') }}" method="POST">
                @csrf
                
                <div class="form-group mb-3">
                    <label for="report_date">Tanggal</label>
                    <input type="date" name="report_date" id="report_date" 
                           class="form-control" required>
                </div>
                
                <div class="form-group mb-3">
                    <label for="description">Deskripsi</label>
                    <input type="text" name="description" id="description" 
                           class="form-control" required>
                </div>
                
                <div class="form-group mb-3">
                    <label for="type">Jenis</label>
                    <select name="type" id="type" class="form-control" required>
                        <option value="income">Pemasukan</option>
                        <option value="expense">Pengeluaran</option>
                    </select>
                </div>
                
                <div class="form-group mb-3">
                    <label for="amount">Jumlah</label>
                    <input type="number" name="amount" id="amount" 
                           class="form-control" min="0" step="0.01" required>
                </div>
                
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('financial-reports.index') }}" class="btn btn-secondary">
                    Batal
                </a>
            </form>
        </div>
    </div>
</div>
@endsection