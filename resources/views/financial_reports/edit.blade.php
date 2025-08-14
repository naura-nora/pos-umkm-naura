@extends('layouts.adminlte')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h5>Edit Laporan Keuangan</h5>
        </div>
        
        <div class="card-body">
            <form action="{{ route('financial-reports.update', $financialReport->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-group mb-3">
                    <label for="report_date">Tanggal</label>
                    <input type="date" name="report_date" id="report_date" 
                           class="form-control" value="{{ $financialReport->report_date->format('Y-m-d') }}" required>
                </div>
                
                <div class="form-group mb-3">
                    <label for="description">Deskripsi</label>
                    <input type="text" name="description" id="description" 
                           class="form-control" value="{{ $financialReport->description }}" required>
                </div>
                
                <div class="form-group mb-3">
                    <label for="type">Jenis</label>
                    <select name="type" id="type" class="form-control" required>
                        <option value="income" {{ $financialReport->type == 'income' ? 'selected' : '' }}>Pemasukan</option>
                        <option value="expense" {{ $financialReport->type == 'expense' ? 'selected' : '' }}>Pengeluaran</option>
                    </select>
                </div>
                
                <div class="form-group mb-3">
                    <label for="amount">Jumlah</label>
                    <input type="number" name="amount" id="amount" 
                           class="form-control" min="0" step="0.01" 
                           value="{{ $financialReport->amount }}" required>
                </div>
                
                @if($financialReport->type == 'expense')
                <div class="form-group">
                    <label>Penanggung Jawab</label>
                    <input type="text" name="responsible" class="form-control" 
                        value="{{ $financialReport->responsible }}" required>
                </div>
                @endif


                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="{{ route('financial-reports.index') }}" class="btn btn-secondary">
                    Batal
                </a>
            </form>
        </div>
    </div>
</div>
@endsection