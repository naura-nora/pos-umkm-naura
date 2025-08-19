<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    public function __construct()
{
    $this->middleware('auth');
    // Kasir dan admin bisa akses semua method kecuali destroy
    $this->middleware('role:admin,kasir')->except(['destroy']);
    // Hanya admin yang bisa destroy
    $this->middleware('role:admin')->only(['destroy']);
}

    public function index(Request $request)
{
    // Cek role user
    $isAdmin = auth()->user()->hasRole('admin');
    
    // Query berdasarkan role
    $query = $isAdmin 
        ? Transaksi::with(['user', 'detailTransaksi.produk'])
        : Transaksi::where('user_id', auth()->id())->with('detailTransaksi.produk');
        
        // Filter pencarian
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_pelanggan', 'like', "%$search%")
                  ->orWhere('kode', 'like', "%$search%")
                  ->orWhere('tanggal', 'like', "%$search%");
            });
        }
        
        
        $transaksi = $query->latest()->paginate(5);
        
        return view('transaksi.index', compact('transaksi', 'isAdmin'));
    }

    public function create()
    {
        $produks = Produk::with('kategori')
               ->where('stok_produk', '>', 0)
               ->get();
               
        return view('transaksi.create', compact('produks'));
    }

    public function store(Request $request)
    {
        \Log::debug('Data Request:', $request->all());
        
        // Validasi input
        $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'bayar' => 'required|numeric|min:0',
            'produk' => 'required|array|min:1',
            'produk.*.id' => 'required|exists:produk,id',
            'produk.*.qty' => 'required|integer|min:1',
            'produk.*.harga' => 'required|numeric|min:0',
            'total_harga' => 'required|numeric|min:0'
        ]);


        DB::beginTransaction();


        try {

        $total = $request->total_harga;
        $bayar = $request->bayar;
        
        if ($bayar < $total) {
            throw new \Exception("Jumlah pembayaran kurang dari total transaksi");
        }

        // Validasi stok sebelum transaksi
        foreach ($request->produk as $item) {
            $produk = Produk::find($item['id']);
            if ($produk->stok_produk < $item['qty']) {
                throw new \Exception("Stok produk {$produk->nama_produk} tidak mencukupi");
            }
        }



            // Buat transaksi
            $transaksi = Transaksi::create([
                'user_id' => Auth::id(),
                'kode' => 'TRX-' . date('YmdHis') . rand(100, 999),
                'nama_pelanggan' => $request->nama_pelanggan,
                'total' => $total,
                'bayar' => $bayar,
                'kembalian' => $bayar - $total,
                'tanggal' => $request->tanggal,
                'status' => $bayar >= $total ? 'Lunas' : 'Belum Lunas'
            ]);

            // Simpan detail transaksi dan kurangi stok
        foreach ($request->produk as $item) {
            $produk = Produk::find($item['id']);
            
            $transaksi->detailTransaksi()->create([
                'produk_id' => $item['id'],
                'qty' => $item['qty'],
                'harga' => $item['harga'],
                'subtotal' => $item['qty'] * $item['harga']
            ]);

            // Kurangi stok produk
            $produk->decrement('stok_produk', $item['qty']);
        }

        DB::commit();

        return redirect()->route('transaksi.index')
            ->with('success', 'Transaksi berhasil dibuat!');

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withErrors(['error' => $e->getMessage()]);
    }
}


    public function show(Transaksi $transaksi)
{
    // Admin bisa akses semua transaksi
    if (auth()->user()->hasRole('admin')) {
        $transaksi->load(['user', 'detailTransaksi.produk.kategori']);
        return view('transaksi.show', compact('transaksi'));
    }
    
    // Kasir hanya bisa akses transaksinya sendiri
    if ($transaksi->user_id === auth()->id()) {
        $transaksi->load(['user', 'detailTransaksi.produk.kategori']);
        return view('transaksi.show', compact('transaksi'));
    }

    abort(403, 'Anda tidak memiliki izin untuk melihat transaksi ini.');
}

    public function edit(Transaksi $transaksi)
    {
        // Debugging - bisa dihapus setelah berhasil
    \Log::info('User mencoba edit transaksi', [
        'user' => auth()->user(),
        'transaksi_user' => $transaksi->user_id
    ]);

    // Admin bisa edit semua
    if (auth()->user()->hasRole('admin')) {
        $produks = Produk::with('kategori')->get();
        $transaksi->load('detailTransaksi.produk');
        return view('transaksi.edit', compact('transaksi', 'produks'));
    }
    
    // Kasir hanya bisa edit transaksi sendiri
    if (auth()->user()->hasRole('kasir') && $transaksi->user_id == auth()->id()) {
        $produks = Produk::with('kategori')->get();
        $transaksi->load('detailTransaksi.produk');
        return view('transaksi.edit', compact('transaksi', 'produks'));
    }

    abort(403, 'Anda tidak memiliki izin untuk mengedit transaksi ini.');
    }

    // perubahan
    public function update(Request $request, Transaksi $transaksi)
{
    $request->validate([
        'nama_pelanggan' => 'required',
        'tanggal' => 'required|date',
        'produk' => 'required|array|min:1',
        'produk.*.id' => 'required|exists:produk,id',
        'produk.*.qty' => 'required|integer|min:1',
        'produk.*.harga' => 'required|numeric|min:0',
        'bayar' => 'required|numeric|min:0',
        'total_harga' => 'required|numeric|min:0'
    ]);

    DB::beginTransaction();

    try {
        // Kembalikan stok lama
        foreach ($transaksi->detailTransaksi as $detail) {
            $produk = $detail->produk;
            $produk->increment('stok_produk', $detail->qty);
        }

        // Update transaksi utama
        $transaksi->update([
            'nama_pelanggan' => $request->nama_pelanggan,
            'total' => $request->total_harga,
            'bayar' => $request->bayar,
            'kembalian' => $request->bayar - $request->total_harga,
            'tanggal' => $request->tanggal,
            'status' => 'Lunas'
        ]);

        // Hapus detail lama
        $transaksi->detailTransaksi()->delete();

        // Simpan detail baru dan kurangi stok
        foreach ($request->produk as $item) {
            $produk = Produk::find($item['id']);
            
            if ($produk->stok_produk < $item['qty']) {
                throw new \Exception("Stok produk {$produk->nama_produk} tidak mencukupi");
            }

            $transaksi->detailTransaksi()->create([
                'produk_id' => $item['id'],
                'qty' => $item['qty'],
                'harga' => $item['harga'],
                'subtotal' => $item['qty'] * $item['harga']
            ]);

            // Kurangi stok produk
            $produk->decrement('stok_produk', $item['qty']);
        }

        DB::commit();

        return redirect()->route('transaksi.index')
            ->with('success', 'Transaksi berhasil diperbarui!');

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withErrors(['error' => $e->getMessage()]);
    }
}
    //  end perubahan

    public function destroy(Transaksi $transaksi)
{
    DB::transaction(function () use ($transaksi) {
        // Kembalikan stok produk
        foreach ($transaksi->detailTransaksi as $detail) {
            $produk = $detail->produk;
            $produk->increment('stok_produk', $detail->qty);
        }
        
        // Hapus detail transaksi
        $transaksi->detailTransaksi()->delete();
        
        // Hapus transaksi
        $transaksi->delete();
    });

    return redirect()->route('transaksi.index')
        ->with('success', 'Transaksi berhasil dihapus!');
}
}