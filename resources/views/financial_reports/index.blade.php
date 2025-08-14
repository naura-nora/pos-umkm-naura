@extends('layouts.adminlte')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>Laporan Keuangan</h5>
            @can('create', App\Models\FinancialReport::class)
                <a href="{{ route('financial-reports.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah
                </a>
            @endcan
        </div>
        
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h5>Total Pemasukan</h5>
                            <h4>Rp {{ number_format($totalIncome, 0, ',', '.') }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <h5>Total Pengeluaran</h5>
                            <h4>Rp {{ number_format($totalExpense, 0, ',', '.') }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h5>Saldo</h5>
                            <h4>Rp {{ number_format($balance, 0, ',', '.') }}</h4>
                        </div>
                    </div>
                </div>
            </div>



            <!-- Tambahkan bagian ini untuk menampilkan pendapatan dari transaksi -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5>Pendapatan dari Transaksi</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Total Pendapatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transactionIncome as $income)
                            <tr>
                                <td>{{ $income->report_date }}</td>
                                <td>Rp {{ number_format($income->amount, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>





            <table class="table table-bordered">
                <thead class="bg-light">
                    <tr>
                        <th>Tanggal</th>
                        <th>Deskripsi</th>
                        <th>Jenis</th>
                        <th>Jumlah</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reports as $report)
                    <tr>
                        <td>{{ $report->report_date->format('d/m/Y') }}</td>
                        <td>{{ $report->description }}</td>
                        <td>
                            <span class="badge {{ $report->type == 'income' ? 'bg-success' : 'bg-danger' }}">
                                {{ $report->type == 'income' ? 'Pemasukan' : 'Pengeluaran' }}
                            </span>
                        </td>
                        <td>Rp {{ number_format($report->amount, 0, ',', '.') }}</td>
                        <td>
                            @can('update', $report)
                            <a href="{{ route('financial-reports.edit', $report->id) }}" 
                               class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            @endcan
                            
                            @can('delete', $report)
                            <form action="{{ route('financial-reports.destroy', $report->id) }}" 
                                  method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" 
                                        onclick="return confirm('Hapus laporan ini?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endcan
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $reports->links() }}
        </div>
    </div>
</div>
@endsection