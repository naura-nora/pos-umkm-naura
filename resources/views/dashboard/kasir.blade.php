@extends('layouts.adminlte')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="p-4 rounded shadow" style="background: #001f3f; background: linear-gradient(to right, #001f3f, #003366);">
                <h2 class="text-white text-bold">Selamat datang, {{ auth()->user()->name }}!</h2>
                <p class="text-light mb-0">Hari ini: {{ now()->format('l, d F Y') }}</p>
            </div>
        </div>
    </div>

    <!-- Cards Section -->
    <div class="row">
        <!-- Transaksi Hari Ini -->
        <div class="col-xl-4 col-md-6 mb-4">
            <a href="{{ route('transaksi.index', ['filter' => 'today']) }}" class="text-decoration-none">
                <div class="card shadow h-100 py-2 stat-card" style="border-left: 4px solid #f6c23e;">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="h6 font-weight-bold text-warning text-uppercase mb-1">
                                    Transaksi Hari Ini</div>
                                <div class="h3 mb-0 font-weight-bold text-dark">{{ $transaksiHariIni }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-receipt fa-3x text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Pendapatan Hari Ini -->
        <div class="col-xl-4 col-md-6 mb-4">
            <a href="{{ route('transaksi.index') }}" class="text-decoration-none">
                <div class="card shadow h-100 py-2 stat-card" style="border-left: 4px solid #f6c23e;">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="h6 font-weight-bold text-warning text-uppercase mb-1">
                                    Pendapatan Hari Ini</div>
                                <div class="h3 mb-0 font-weight-bold text-dark">Rp {{ number_format($pendapatanHariIni,0,',','.') }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-money-bill-wave fa-3x text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Total Pendapatan Kasir -->
        <div class="col-xl-4 col-md-6 mb-4">
            <a href="{{ route('transaksi.index', ['user_id' => auth()->id()]) }}" class="text-decoration-none">
                <div class="card shadow h-100 py-2 stat-card" style="border-left: 4px solid #f6c23e;">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="h6 font-weight-bold text-warning text-uppercase mb-1">
                                    Total Pendapatan Saya</div>
                                <div class="h3 mb-0 font-weight-bold text-dark">Rp {{ number_format($totalPendapatanKasir,0,',','.') }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user-tie fa-3x text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Transaksi Terakhir -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between text-bold" style="background: #001f3f; background: linear-gradient(to right, #001f3f, #003366);">
                    <h6 class="m-0 font-weight-bold text-white">Transaksi Terakhir</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Kode Transaksi</th>
                                    <th>Pelanggan</th>
                                    <th>Total</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transaksiTerakhir as $transaksi)
                                <tr>
                                    <td>{{ $transaksi->kode }}</td>
                                    <td>{{ $transaksi->nama_pelanggan }}</td>
                                    <td>Rp {{ number_format($transaksi->total,0,',','.') }}</td>
                                    <td>{{ $transaksi->tanggal->format('d-m-Y') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .stat-card {
        transition: all 0.3s ease;
        transform-origin: center;
    }
    .stat-card:hover {
        transform: scale(0.98);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .welcome-card {
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }
</style>
@endsection