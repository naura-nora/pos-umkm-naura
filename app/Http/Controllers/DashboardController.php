<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Kategori;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // if ($user->hasRole('admin')) {
        //     return view('dashboard.admin');
        // } 

        if ($user->hasRole('admin')) {
            // Data statistik
            $totalProduk = Produk::count();
            $transaksiHariIni = Transaksi::whereDate('created_at', Carbon::today())->count();
            $totalKategori = Kategori::count();
            $penggunaSistem = User::count();
            $totalUser = User::count();
            
            // Produk stok rendah
            $stokRendah = Produk::with('kategori')
                ->where('stok_produk', '<', 10)
                ->orderBy('stok_produk', 'asc')
                ->limit(5)
                ->get();
            
            // Data grafik transaksi 7 hari terakhir
            
            // Data grafik transaksi 7 hari terakhir (dari detail_transaksi)
            $dates = collect();
            $totals = collect();
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::today()->subDays($i)->format('Y-m-d');
                $total = DetailTransaksi::whereDate('created_at', $date)->sum('subtotal');

                $dates->push(Carbon::parse($date)->format('d M'));
                $totals->push($total);
            }

            return view('dashboard.admin', compact(
                'totalProduk',
                'transaksiHariIni',
                'totalKategori',
                'totalUser',
                'stokRendah',
                'dates',
                'totals'
            ));
        }
        
        if ($user->hasRole('kasir')) {
            return view('dashboard.kasir');
        } 
        
        if ($user->hasRole('pelanggan')) {
            return view('dashboard.pelanggan');
        }

        abort(403, 'Role tidak dikenali.');
    }
}