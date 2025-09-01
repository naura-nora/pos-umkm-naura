@extends('layouts.adminlte')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header text-light text-bold" style="background: #001f3f; background: linear-gradient(to right, #001f3f, #003366);">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title">Detail Transaksi #{{ $transaksi->kode }}</h3>
                <div>
                    <a href="{{ route('transaksi.index') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali
                    </a>
                    <button onclick="window.print()" class="btn btn-light btn-sm ml-2">
                        <i class="fas fa-print mr-1"></i> Cetak
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-warning">
                            <h3 class="card-title">Informasi Transaksi</h3>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">Kode</th>
                                    <td>{{ $transaksi->kode }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal</th>
                                    <td>{{ $transaksi->tanggal->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Kasir</th>
                                    <td>{{ $transaksi->user->name }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <span class="badge {{ $transaksi->status === 'Lunas' ? 'bg-success' : 'bg-warning' }}">
                                            {{ $transaksi->status }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-warning">
                            <h3 class="card-title">Informasi Pelanggan</h3>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">Nama</th>
                                    <td>{{ $transaksi->nama_pelanggan ?: '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header" style="background: #001f3f; background: linear-gradient(to right, #001f3f, #003366);">
                    <h3 class="card-title text-white">Detail Pembelian</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="bg-secondary">
                                <!-- <tr class="text-light" style="background: #001f3f; background: linear-gradient(to right, #001f3f, #003366);"> -->
                                    <th width="5%">No</th>
                                    <th width="15%">Gambar</th>
                                    <th>Produk</th>
                                    <th>Kategori</th>
                                    <th width="15%">Harga</th>
                                    <th width="10%">Qty</th>
                                    <th width="20%">Subtotal</th>
                                <!-- </tr> -->
                            </thead>
                            <tbody>
                                @forelse($transaksi->detailTransaksi as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="text-center">
                                        @if($item->produk && $item->produk->gambar_produk)
                                            @php
                                                // Path gambar di public/adminlte/img
                                                $gambarPath = 'adminlte/img/' . $item->produk->gambar_produk;
                                                // Path lengkap untuk pengecekan
                                                $fullPath = public_path($gambarPath);
                                            @endphp
                                            
                                            @if(file_exists($fullPath))
                                                <img src="{{ asset($gambarPath) }}" 
                                                    alt="{{ $item->produk->nama_produk }}" 
                                                    class="img-thumbnail" 
                                                    style="max-height: 100px; max-width: 100px;">
                                            @else
                                                <img src="{{ asset('adminlte/img/default-product.png') }}" 
                                                    alt="Gambar tidak ditemukan" 
                                                    class="img-thumbnail" 
                                                    style="max-height: 100px; max-width: 100px;">
                                                <div class="text-danger small">File tidak ada: {{ $item->produk->gambar_produk }}</div>
                                            @endif
                                        @else
                                            <img src="{{ asset('adminlte/img/default-product.png') }}" 
                                                alt="Tidak ada gambar" 
                                                class="img-thumbnail" 
                                                style="max-height: 100px; max-width: 100px;">
                                        @endif
                                    </td>
                                    <td>{{ $item->produk->nama_produk ?? 'Produk dihapus' }}</td>
                                    <td>
                                        @if($item->produk && $item->produk->kategori)
                                            {{ $item->produk->kategori->nama_kategori }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-right">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                    <td class="text-center">{{ $item->qty }}</td>
                                    <td class="text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">Tidak ada item</td>
                                </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="6" class="text-right">Total</th>
                                    <th class="text-right">Rp {{ number_format($transaksi->total, 0, ',', '.') }}</th>
                                </tr>
                                <tr>
                                    <th colspan="6" class="text-right">Bayar</th>
                                    <th class="text-right">Rp {{ number_format($transaksi->bayar, 0, ',', '.') }}</th>
                                </tr>
                                <tr>
                                    <th colspan="6" class="text-right">Kembalian</th>
                                    <th class="text-right">Rp {{ number_format($transaksi->kembalian, 0, ',', '.') }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <div class="mt-3 text-right">
                <small class="text-muted">Dicetak pada: {{ now()->format('d/m/Y H:i') }}</small>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        .card, .card * {
            visibility: visible;
        }
        .card {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            border: none;
            box-shadow: none;
        }
        .no-print, .card-header .btn {
            display: none !important;
        }
        .card-header {
            border-bottom: 1px solid #ddd;
        }
        img {
            max-width: 50px !important;
            max-height: 50px !important;
        }
    }
    .bg-gray {
        background-color: #f4f6f9;
    }
    .table th {
        white-space: nowrap;
    }
    .img-fluid {
        max-width: 100%;
        height: auto;
        border-radius: 4px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
</style>
@endsection