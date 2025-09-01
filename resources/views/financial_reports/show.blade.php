@extends('layouts.adminlte')

@section('title', 'Transaksi Tanggal ' . $tanggalFormat)

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header" style="background: linear-gradient(to right, #001f3f, #003366);">
            <h3 class="card-title text-light">
                <i class="fas fa-calendar-alt mr-2"></i>
                Transaksi Tanggal: <strong>{{ $tanggalFormat }}</strong>
            </h3>
            <div class="card-tools">
                <a href="{{ route('financial-reports.index') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali ke Laporan
                </a>
            </div>
        </div>

        <div class="card-body">
            @if($transaksi->isEmpty())
                <div class="text-center text-muted py-4">
                    <i class="fas fa-receipt fa-3x mb-3"></i>
                    <p>Tidak ada transaksi pada tanggal <strong>{{ $tanggalFormat }}</strong>.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="bg-secondary text-white text-center">
                            <tr>
                                <th width="5%">No</th>
                                <th width="20%">Pelanggan</th>
                                <th width="15%">Total</th>
                                <th width="15%">Kasir</th>
                                <th width="15%">Tanggal</th>
                                <th width="10%">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transaksi as $trx)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                
                                <td>{{ $trx->nama_pelanggan ?? 'Umum' }}</td>
                                <td class="text-center">Rp {{ number_format($trx->total, 0, ',', '.') }}</td>
                                <td>{{ $trx->user->name }}</td>
                                <td class="text-center">
                                    {{ \Carbon\Carbon::parse($trx->tanggal)->format('d/m/Y') }}<br>
                                    
                                </td>
                                <td class="text-center">
                                    @if($trx->status == 'Lunas')
                                        <span class="badge badge-success">Lunas</span>
                                    @elseif($trx->status == 'Piutang')
                                        <span class="badge badge-warning text-dark">Piutang</span>
                                    @else
                                        <span class="badge badge-secondary">{{ $trx->status }}</span>
                                    @endif
                                </td>
                                Rp {{ number_format($trx->total, 0, ',', '.') }}
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Ringkasan -->
                <div class="card mt-3">
                    <div class="card-body text-center">
                        <h5 class="mb-2">Total Transaksi: <strong>{{ $transaksi->count() }}</strong></h5>
                        <h5 class="mb-0 text-dark">
                            <strong>Pemasukan: Rp {{ number_format($transaksi->sum('total'), 0, ',', '.') }}</strong>
                        </h5>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('css')
<style>
    .card-title strong {
        color: white;
    }
    .table th, .table td {
        vertical-align: middle;
    }
    .table th {
        text-align: center;
    }
    .text-right {
        text-align: right;
    }
    .badge {
        font-size: 90%;
        padding: .5em .7em;
    }
</style>
@endsection