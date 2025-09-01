@extends('layouts.print')

@section('content')
<style>
    body {
        font-family: 'Source Sans Pro', sans-serif;
        color: #333;
        background: #fff;
        margin: 0;
        padding: 20px;
    }

    .container {
        max-width: 100%;
        margin: 0 auto;
    }

    /* Tombol Kembali - hanya muncul di layar */
    .back-button {
        margin-bottom: 20px;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background-color: #6c757d;
        color: white;
        text-decoration: none;
        padding: 10px 16px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: background-color 0.2s;
    }

    .btn-back:hover {
        background-color: #5a6268;
        color: white;
    }

    .header {
        text-align: center;
        margin-bottom: 20px;
        border-bottom: 2px solid #000;
        padding-bottom: 10px;
    }

    .header h1 {
        margin: 0;
        font-size: 24px;
        color: #000;
    }

    .header p {
        margin: 5px 0 0;
        color: #555;
        font-size: 14px;
    }

    .summary {
        display: flex;
        justify-content: space-around;
        gap: 10px;
        margin: 15px 0;
        flex-wrap: wrap;
    }

    .summary-box {
        flex: 1;
        min-width: 200px;
        padding: 12px;
        background: #f8f9fa;
        border: 1px solid #ddd;
        border-radius: 8px;
        text-align: center;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .summary-box strong {
        display: block;
        font-size: 14px;
        color: #444;
    }

    .summary-box span {
        font-size: 18px;
        font-weight: bold;
        color: #28a745;
    }

    .card {
        margin-bottom: 30px;
        page-break-inside: avoid;
    }

    .card-header {
        background-color: #001f3f;
        background: linear-gradient(to right, #001f3f, #003366);
        color: white;
        font-size: 16px;
        font-weight: bold;
        padding: 10px 15px;
        border-radius: 6px 6px 0 0;
        text-align: center;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        margin: 0;
        font-size: 13px;
    }

    .table th, .table td {
        padding: 8px 10px;
        border: 1px solid #ccc;
        text-align: center;
        vertical-align: top;
    }

    .table th {
        background-color: #f0f0f0;
        color: #333;
        font-weight: bold;
    }

    .text-left {
        text-align: left;
    }

    .amount {
        font-weight: bold;
        color: #155724;
    }

    .amount.expense {
        color: #c82333;
    }

    .no-data {
        text-align: center;
        font-style: italic;
        color: #999;
        padding: 20px;
    }

    .footer {
        text-align: center;
        font-size: 12px;
        color: #777;
        margin-top: 40px;
        padding-top: 10px;
        border-top: 1px dashed #ccc;
    }

    /* Atur tampilan saat cetak */
    @media print {
        .back-button {
            display: none !important;
        }

        body {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            padding: 10px;
        }

        @page {
            margin: 1cm;
            size: A4;
        }

        .card {
            break-inside: avoid;
        }

        .footer {
            page-break-after: avoid;
        }
    }
</style>

<div class="container">

    <!-- Tombol Kembali (Hanya di layar, tidak dicetak) -->
    <div class="back-button">
        <a href="{{ route('financial-reports.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Kembali ke Laporan Keuangan
        </a>
    </div>

    <!-- Header Laporan -->
    <div class="header">
        <h1>LAPORAN KEUANGAN</h1>
        <p><strong>POS UMKM</strong></p>
        <p>Tanggal Cetak: {{ now()->format('d M Y H:i') }}</p>
    </div>

    <!-- Ringkasan Keuangan -->
    <div class="summary">
        <div class="summary-box">
            <strong>Pemasukan</strong>
            <span>Rp {{ number_format($totalIncome, 0, ',', '.') }}</span>
        </div>
        <div class="summary-box">
            <strong>Pengeluaran</strong>
            <span style="color: #c82333;">Rp {{ number_format($totalExpense, 0, ',', '.') }}</span>
        </div>
        <div class="summary-box">
            <strong>Saldo Bersih</strong>
            <span style="color: #004085;">Rp {{ number_format($balance, 0, ',', '.') }}</span>
        </div>
    </div>

    <!-- Pemasukan dari Transaksi -->
    <div class="card">
        <div class="card-header">PEMASUKAN DARI TRANSAKSI</div>
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Item Terjual</th>
                    <th>Tanggal</th>
                    <th>Jumlah</th>
                    <th>Kasir</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactionIncomes as $transaction)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $transaction->total_items }} item</td>
                    <td>{{ \Carbon\Carbon::parse($transaction->report_date)->format('d/m/Y') }}</td>
                    <td class="amount">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</td>
                    <td>{{ $transaction->cashiers }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="no-data">Tidak ada data transaksi</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pemasukan Lain -->
    <div class="card">
        <div class="card-header">PEMASUKAN LAINNYA</div>
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Deskripsi</th>
                    <th>Tanggal</th>
                    <th>Jumlah</th>
                    <th>Kasir</th>
                </tr>
            </thead>
            <tbody>
                @forelse($manualIncomes as $income)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td class="text-left">{{ $income->description }}</td>
                    <td>{{ $income->report_date->format('d/m/Y') }}</td>
                    <td class="amount">Rp {{ number_format($income->amount, 0, ',', '.') }}</td>
                    <td>{{ $income->user->name }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="no-data">Tidak ada pemasukan manual</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pengeluaran -->
    <div class="card">
        <div class="card-header">PENGELUARAN</div>
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Deskripsi</th>
                    <th>Tanggal</th>
                    <th>Jumlah</th>
                    <th>Pengeluar</th>
                </tr>
            </thead>
            <tbody>
                @forelse($expenses as $expense)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td class="text-left">{{ $expense->description }}</td>
                    <td>{{ $expense->report_date->format('d/m/Y') }}</td>
                    <td class="amount expense">Rp {{ number_format($expense->amount, 0, ',', '.') }}</td>
                    <td>{{ $expense->user->name }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="no-data">Tidak ada data pengeluaran</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Footer -->
    <div class="footer">
        Laporan ini dicetak secara otomatis melalui sistem. <br>
        &copy; {{ now()->year }} POS UMKM. Hak Cipta Dilindungi.
    </div>

</div>

<!-- Auto-print saat halaman selesai dimuat -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Tambahkan jeda kecil agar CSS dan konten benar-benar siap
        setTimeout(() => {
            window.print();
        }, 500);
    });
</script>

@endsection