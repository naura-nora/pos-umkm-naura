@extends('layouts.adminlte')

@section('content')
<div class="content">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-3 align-items-center">
                <div class="col-sm-8">
                    <h1>Dashboard</h1>
                    <p>Selamat datang, <strong>{{ auth()->user()->name }}</strong>!.</p>
                </div>
                <div class="col-sm-4 text-sm-right text-center">
                    <a href="{{ route('transaksi.create') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-plus"></i> Buat Transaksi Baru
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row g-3"> <!-- gunakan gutter untuk jarak antar box -->
                <!-- Total Transaksi Hari Ini -->
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="small-box bg-info shadow-sm">
                        <div class="inner">
                            <h3>{{ $totalTransaksiHariIni ?? 0 }}</h3>
                            <p>Total Transaksi Hari Ini</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-receipt"></i>
                        </div>
                        <a href="{{ route('transaksi.index') }}" class="small-box-footer">
                            Lihat Semua <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Total Pendapatan Hari Ini -->
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="small-box bg-success shadow-sm">
                        <div class="inner">
                            <h3>Rp {{ number_format($totalPendapatanHariIni ?? 0, 0, ',', '.') }}</h3>
                            <p>Total Pendapatan Hari Ini</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <a href="#" class="small-box-footer">
                            Lihat Laporan <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Produk Terlaris -->
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="small-box bg-warning shadow-sm">
                        <div class="inner">
                            <!-- <h4 class="mb-1">{{ $produkTerlaris->nama_produk ?? '-' }}</h4> -->
                             <h3>{{ $jumlahProduk ?? 0 }}</h3>
                            <p>Produk Terlaris Hari Ini</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <a href="{{ route('produk.index') }}" class="small-box-footer">
                            Lihat Produk <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Jumlah Produk -->
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="small-box bg-danger shadow-sm">
                        <div class="inner">
                            <h3>{{ $jumlahProduk ?? 0 }}</h3>
                            <p>Jumlah Produk</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-boxes"></i>
                        </div>
                        <a href="{{ route('produk.index') }}" class="small-box-footer">
                            Kelola Produk <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Grafik Pendapatan Bulanan -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary">
                            <h3 class="card-title text-white"><i class="fas fa-chart-line"></i> Grafik Pendapatan Bulanan</h3>
                        </div>
                        <div class="card-body">
                            <canvas id="pendapatanChart" style="height: 320px; max-height: 320px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const labels = {!! json_encode($chartLabels ?? []) !!};
    const data = {!! json_encode($chartData ?? []) !!};

    const ctx = document.getElementById('pendapatanChart').getContext('2d');
    const pendapatanChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: data,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 3,
                fill: true,
                tension: 0.3,
                pointRadius: 5,
                pointHoverRadius: 7,
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            },
            plugins: {
                legend: { display: true, position: 'top' },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
</script>
@endsection
