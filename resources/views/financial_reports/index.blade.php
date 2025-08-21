@extends('layouts.adminlte')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center text-white text-bold" style="background: #001f3f; background: linear-gradient(to right, #001f3f, #003366);">
    <h3 class="mb-0 text-bold">Laporan Keuangan</h3>
    <div class="ml-auto d-flex"> <!-- Tambah ml-auto di sini -->
        <div class="btn-rounded mr-2"> <!-- Jarak antar tombol 2 -->
            <a href="{{ route('financial-reports.create') }}" class="btn btn-light text-dark btn-rounded">
                <i class="fas fa-plus-circle mr-1"></i> Tambah Pemasukan / Pengeluaran
            </a>
        </div>
    </div>
</div>
        
        <div class="card-body">
            <!-- Summary Cards -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card bg-warning text-white">
                        <div class="card-body text-center">
                            <h5><i class="fas fa-wallet mr-2"></i>Total Pemasukan</h5>
                            <h3>Rp {{ number_format($totalIncome, 0, ',', '.') }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-warning text-white">
                        <div class="card-body text-center">
                            <h5><i class="fas fa-shopping-cart mr-2"></i>Total Pengeluaran</h5>
                            <h3>Rp {{ number_format($totalExpense, 0, ',', '.') }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-warning text-white">
                        <div class="card-body text-center">
                            <h5><i class="fas fa-piggy-bank mr-2"></i>Saldo</h5>
                            <h3>Rp {{ number_format($balance, 0, ',', '.') }}</h3>
                        </div>
                    </div>
                </div>
            </div>

        
            <!-- Tabel Pemasukan dari Transaksi -->
            <div class="card mb-4">
                <div class="card-header text-bold text-white" style="background: #001f3f; background: linear-gradient(to right, #001f3f, #003366);>
                    <h5 class="mb-0"><i class="fas fa-cash-register mr-2"></i>Pemasukan dari Transaksi</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0 table-bordered">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="30%">Barang Keluar</th>
                                    <th width="15%">Tanggal</th>
                                    <th width="15%">Jumlah</th>
                                    <th width="25%">Kasir</th>
                                    <th width="10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactionIncomes as $index => $transaction)
                                <tr>
                                    <td>{{ $transactionIncomes->firstItem() + $index }}</td>
                                    <td>{{ $transaction->total_items }} item</td>
                                    <td>{{ \Carbon\Carbon::parse($transaction->report_date)->format('d/m/Y') }}</td>
                                    <td>Rp {{ number_format($transaction->amount, 0, ',', '.') }}</td>
                                    <td>{{ $transaction->cashiers }}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('transaksi.index', ['date' => $transaction->report_date]) }}" 
                                            class="btn btn-sm btn-warning" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @can('delete', App\Models\Transaksi::class)
                                            <form action="#" method="POST" onsubmit="return confirm('Hapus semua transaksi tanggal ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada data transaksi</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    {{ $transactionIncomes->links() }}
                </div>
            </div>


            <!-- Tabel Pemasukan Lain -->
            <div class="card mb-4">
                <div class="card-header text-bold text-white" style="background: #001f3f; background: linear-gradient(to right, #001f3f, #003366);>
                    <h5 class="mb-0"><i class="fas fa-money-bill-wave mr-2"></i>Pemasukan Lain</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0 table-bordered">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="30%">Deskripsi</th>
                                    <th width="15%">Tanggal</th>
                                    <th width="15%">Jumlah</th>
                                    <th width="25%">Penanggung Jawab</th>
                                    <th width="10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($manualIncomes as $index => $income)
                                <tr>
                                    <td>{{ $manualIncomes->firstItem() + $index }}</td>
                                    <td>{{ $income->description }}</td>
                                    <td>{{ $income->report_date->format('d/m/Y') }}</td>
                                    <td>Rp {{ number_format($income->amount, 0, ',', '.') }}</td>
                                    <td>{{ $income->user->name }}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('financial-reports.edit', $income->id) }}" 
                                            class="btn btn-sm btn-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('financial-reports.destroy', $income->id) }}" 
                                                method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" 
                                                        onclick="return confirm('Hapus laporan ini?')"
                                                        title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada data pemasukan</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    {{ $manualIncomes->links() }}
                </div>
            </div>


            <!-- Tabel Pengeluaran -->
            <div class="card">
                <div class="card-header text-bold text-white" style="background: #001f3f; background: linear-gradient(to right, #001f3f, #003366);>
                    <h5 class="mb-0"><i class="fas fa-receipt mr-2"></i>Pengeluaran</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="30%">Deskripsi</th>
                                    <th width="15%">Tanggal</th>
                                    <th width="15%">Jumlah</th>
                                    <th width="25%">Penanggung Jawab</th>
                                    <th width="10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($expenses as $index => $expense)
                                <tr>
                                    <td>{{ $expenses->firstItem() + $index }}</td>
                                    <td>{{ $expense->description }}</td>
                                    <td>{{ $expense->report_date->format('d/m/Y') }}</td>
                                    <td>Rp {{ number_format($expense->amount, 0, ',', '.') }}</td>
                                    <td>{{ $expense->user->name }}</td>
                                    <!-- <td>{{ $expense->responsible }}</td> -->
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('financial-reports.edit', $expense->id) }}" 
                                            class="btn btn-sm btn-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('financial-reports.destroy', $expense->id) }}" 
                                                method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" 
                                                        onclick="return confirm('Hapus laporan ini?')"
                                                        title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada data pengeluaran</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    {{ $expenses->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection