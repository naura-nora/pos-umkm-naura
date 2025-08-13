@extends('layouts.adminlte')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="welcome-card p-4 rounded shadow" style="background: #001f3f; background: linear-gradient(to right, #001f3f, #003366);">
                <h2 class="text-white text-bold">Selamat datang, {{ auth()->user()->name }}!</h2>
                <p class="text-light mb-0">Hari ini: {{ now()->format('l, d F Y') }}</p>
            </div>
        </div>
    </div>

    <!-- Statistik Cepat -->
<style>
    /* Efek hover untuk semua card */
    .stat-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .stat-card:hover {
        transform: scale(0.97);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <a href="{{ route('produk.index') }}" class="text-decoration-none">
                <div class="card shadow h-100 py-2 stat-card" style="border-left: 4px solid #f6c23e;">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-md font-weight-bold text-warning text-uppercase mb-1">
                                    Total Produk</div>
                                <div class="h5 mb-0 font-weight-bold text-dark">{{ $totalProduk }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-boxes fa-2x text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <a href="{{ route('transaksi.index', ['filter' => 'today']) }}" class="text-decoration-none">
                <div class="card shadow h-100 py-2 stat-card" style="border-left: 4px solid #f6c23e;">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-md font-weight-bold text-warning text-uppercase mb-1">
                                    Transaksi Hari Ini</div>
                                <div class="h5 mb-0 font-weight-bold text-dark">{{ $transaksiHariIni }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-receipt fa-2x text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

<div class="col-xl-3 col-md-6 mb-4">
    <a href="{{ route('kategori.index') }}" class="text-decoration-none">
        <div class="card shadow h-100 py-2 stat-card" style="border-left: 4px solid #f6c23e;">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-md font-weight-bold text-warning text-uppercase mb-1">
                            Total Kategori</div>
                        <div class="h5 mb-0 font-weight-bold text-dark">{{ $totalKategori }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-tags fa-2x text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </a>
</div>

<div class="col-xl-3 col-md-6 mb-4">
    <a href="{{ route('user-management.index') }}" class="text-decoration-none">
        <div class="card shadow h-100 py-2 stat-card" style="border-left: 4px solid #f6c23e;">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-md font-weight-bold text-warning text-uppercase mb-1">
                            Pengguna Sistem</div>
                        <div class="h5 mb-0 font-weight-bold text-dark">{{ $totalUser }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </a>
</div>
</div>

    </div>

    <!-- Grafik dan Quick Actions -->
    <div class="row">
        <!-- Grafik Transaksi -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between" style="background: #001f3f; background: linear-gradient(to right, #001f3f, #003366);">
                    <h6 class="m-0 font-weight-bold text-white">Statistik Transaksi 7 Hari Terakhir</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="transaksiChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between" style="background: #001f3f; background: linear-gradient(to right, #001f3f, #003366);">
                    <h6 class="m-0 font-weight-bold text-white">Aksi Cepat</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('produk.create') }}" class="btn btn-warning btn-icon-split mb-3" style="width:100%;">
                            <span class="icon text-white-50">
                                <i class="fas fa-plus"></i>
                            </span>
                            <span class="text">Tambah Produk Baru</span>
                        </a>
                        <a href="{{ route('transaksi.create') }}" class="btn btn-warning btn-icon-split mb-3" style="width:100%;">
                            <span class="icon text-white-50">
                                <i class="fas fa-cash-register"></i>
                            </span>
                            <span class="text">Buat Transaksi Baru</span>
                        </a>
                        <a href="{{ route('user-management.create') }}" class="btn btn-warning btn-icon-split mb-3" style="width:100%;">
                            <span class="icon text-white-50">
                                <i class="fas fa-user-plus"></i>
                            </span>
                            <span class="text">Tambah User Baru</span>
                        </a>
                        <a href="{{ route('kategori.create') }}" class="btn btn-warning btn-icon-split" style="width:100%;">
                            <span class="icon text-white-50">
                                <i class="fas fa-tag"></i>
                            </span>
                            <span class="text">Tambah Kategori</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Produk Stok Rendah -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between" style="background: #001f3f; background: linear-gradient(to right, #001f3f, #003366);">
                    <h6 class="m-0 font-weight-bold text-white">Produk Dengan Stok Rendah</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="bg-secondary">
                                <tr>
                                    <th>Nama Produk</th>
                                    <th>Kategori</th>
                                    <th>Stok Tersedia</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($stokRendah as $produk)
                                <tr>
                                    <td>{{ $produk->nama_produk }}</td>
                                    <td>{{ $produk->kategori->nama_kategori ?? '-' }}</td>
                                    <td class="{{ $produk->stok_produk < 5 ? 'text-danger font-weight-bold' : '' }}">
                                        {{ $produk->stok_produk }}
                                    </td>
                                    <td>
                                        <a href="{{ route('produk.edit', $produk->id) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i> Restock
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">Tidak ada produk dengan stok rendah</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Grafik Transaksi
    const ctx = document.getElementById('transaksiChart').getContext('2d');
    const transaksiChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($dates) !!},
            datasets: [{
                label: 'Jumlah Transaksi',
                data: {!! json_encode($totals) !!},
                backgroundColor: 'rgba(78, 115, 223, 0.05)',
                borderColor: 'rgba(78, 115, 223, 1)',
                pointBackgroundColor: 'rgba(78, 115, 223, 1)',
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: 'rgba(78, 115, 223, 1)',
                tension: 0.3
            }]
        },
        options: {
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
</script>
@endsection

@endsection