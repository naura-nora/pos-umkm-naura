@extends('layouts.adminlte')

@section('title', 'Detail Pengeluaran - ' . $financialReport->description)

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center text-white text-bold" style="background: #001f3f; background: linear-gradient(to right, #001f3f, #003366);">
            <h3 class="mb-0">Detail Pengeluaran</h3>
            <div>
                <a href="{{ route('financial-reports.index') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-warning">
                            <h5 class="card-title">Informasi Pengeluaran</h5>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">Deskripsi</th>
                                    <td>{{ $financialReport->description }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal</th>
                                    <td>{{ $financialReport->report_date->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Jumlah</th>
                                    <td class="text-right">Rp {{ number_format($financialReport->amount, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th>Penanggung Jawab</th>
                                    <td>{{ $financialReport->user->name }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <span class="badge bg-success">Terproses</span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-warning">
                            <h5 class="card-title">Informasi Retur</h5>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">ID Retur</th>
                                    <td>{{ $retur->id }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Retur</th>
                                    <td>{{ $retur->created_at->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Diajukan Oleh</th>
                                    <td>{{ $retur->user->name }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        @php
                                            $statusBadge = [
                                                'pending' => 'bg-warning',
                                                'approved' => 'bg-success',
                                                'rejected' => 'bg-danger'
                                            ];
                                            $statusText = [
                                                'pending' => 'Menunggu',
                                                'approved' => 'Disetujui',
                                                'rejected' => 'Ditolak'
                                            ];
                                        @endphp
                                        <span class="badge {{ $statusBadge[$retur->status] ?? 'bg-secondary' }}">
                                            {{ $statusText[$retur->status] ?? $retur->status }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-info" style="background: #001f3f; background: linear-gradient(to right, #001f3f, #003366);">
                    <h3 class="card-title text-white">Detail Produk yang Dikembalikan</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="bg-secondary">
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="15%">Gambar</th>
                                    <th>Produk</th>
                                    <th>Kategori</th>
                                    <th width="15%">Harga Satuan</th>
                                    <th width="10%">Qty Retur</th>
                                    <th width="20%">Total Retur</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td class="text-center">
                                        @if($retur->produk && $retur->produk->gambar_produk)
                                            @php
                                                $gambarPath = 'adminlte/img/' . $retur->produk->gambar_produk;
                                                $fullPath = public_path($gambarPath);
                                            @endphp
                                            @if(file_exists($fullPath))
                                                <img src="{{ asset($gambarPath) }}" 
                                                    alt="{{ $retur->produk->nama_produk }}" 
                                                    class="img-thumbnail" 
                                                    style="max-height: 100px; max-width: 100px;">
                                            @else
                                                <img src="{{ asset('adminlte/img/default-product.png') }}" 
                                                    alt="Gambar tidak ditemukan" 
                                                    class="img-thumbnail" 
                                                    style="max-height: 100px; max-width: 100px;">
                                            @endif
                                        @else
                                            <img src="{{ asset('adminlte/img/default-product.png') }}" 
                                                alt="Tidak ada gambar" 
                                                class="img-thumbnail" 
                                                style="max-height: 100px; max-width: 100px;">
                                        @endif
                                    </td>
                                    <td>{{ $retur->produk->nama_produk ?? 'Produk dihapus' }}</td>
                                    <td>
                                        @if($retur->produk && $retur->produk->kategori)
                                            {{ $retur->produk->kategori->nama_kategori }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        @if($retur->produk)
                                            Rp {{ number_format($retur->produk->harga_produk, 0, ',', '.') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $retur->qty }}</td>
                                    <td class="text-right">
                                        @if($retur->produk)
                                            Rp {{ number_format($retur->produk->harga_produk * $retur->qty, 0, ',', '.') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="mt-3 text-right">
                <small class="text-muted">Dicetak pada: {{ now()->format('d/m/Y') }}</small>
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