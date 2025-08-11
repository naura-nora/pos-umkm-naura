<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return view('dashboard.admin', [
                'totalTransaksiHariIni' => 10,
                'totalPendapatanHariIni' => 500000,
                'jumlahProduk' => 25,
                'chartLabels' => ['Januari', 'Februari'],
                'chartData' => [500000, 600000],
            ]);
        }

        if ($user->role === 'kasir') {
            return view('dashboard.kasir');
        }

        if ($user->role === 'pelanggan') {
            return view('dashboard.pelanggan');
        }

        abort(403, 'Role tidak dikenali.');
    }
}