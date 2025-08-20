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
        $this->middleware('role:admin,kasir')->except(['destroy']);
        $this->middleware('role:admin')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $isAdmin = auth()->user()->hasRole('admin');

        $query = $isAdmin 
            ? Transaksi::with(['user', 'detailTransaksi.produk'])
            : Transaksi::where('user_id', auth()->id())->with('detailTransaksi.produk');

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
        $kembalian = max(0, $bayar - $total);
        
        // Tentukan status berdasarkan pembayaran
        $status = $bayar >= $total ? 'Lunas' : 'Belum Lunas';

        // Validasi stok
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
            'kembalian' => $kembalian,
            'tanggal' => $request->tanggal,
            'status' => $status // Gunakan status yang sudah ditentukan
        ]);

        // Simpan detail & kurangi stok
        foreach ($request->produk as $item) {
            $produk = Produk::find($item['id']);
            $transaksi->detailTransaksi()->create([
                'produk_id' => $item['id'],
                'qty' => $item['qty'],
                'harga' => $item['harga'],
                'subtotal' => $item['qty'] * $item['harga']
            ]);
            $produk->decrement('stok_produk', $item['qty']);
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil disimpan.',
            'redirect_url' => route('transaksi.index')
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}

    public function show(Transaksi $transaksi)
    {
        if (auth()->user()->hasRole('admin') || $transaksi->user_id === auth()->id()) {
            $transaksi->load(['user', 'detailTransaksi.produk.kategori']);
            return view('transaksi.show', compact('transaksi'));
        }

        abort(403, 'Anda tidak memiliki izin untuk melihat transaksi ini.');
    }

    public function edit(Transaksi $transaksi)
    {
        // Load relasi
        $transaksi->load('detailTransaksi.produk.kategori');
        $produks = Produk::with('kategori')->get();

        // Siapkan data untuk JavaScript
        $produkList = $transaksi->detailTransaksi->map(function ($detail) {
            return [
                'id' => $detail->produk_id,
                'nama' => $detail->produk->nama_produk,
                'kategori' => $detail->produk->kategori->nama_kategori ?? 'Tidak ada kategori',
                'harga' => (float) $detail->harga,
                'qty' => (int) $detail->qty,
                'subtotal' => (float) $detail->subtotal,
            ];
        });

        return view('transaksi.edit', compact('transaksi', 'produks', 'produkList'));
    }

    public function update(Request $request, Transaksi $transaksi)
{
    $request->validate([
        'nama_pelanggan' => 'required|string|max:255',
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
        // Kembalikan stok dari data lama
        foreach ($transaksi->detailTransaksi as $detail) {
            $produk = $detail->produk;
            $produk->increment('stok_produk', $detail->qty);
        }

        $total = $request->total_harga;
        $bayar = $request->bayar;
        $kembalian = max(0, $bayar - $total);
        
        // Tentukan status berdasarkan pembayaran
        $status = $bayar >= $total ? 'Lunas' : 'Belum Lunas';

        // Validasi stok baru
        foreach ($request->produk as $item) {
            $produk = Produk::find($item['id']);
            if ($produk->stok_produk < $item['qty']) {
                throw new \Exception("Stok produk {$produk->nama_produk} tidak mencukupi");
            }
        }

        // Update transaksi
        $transaksi->update([
            'nama_pelanggan' => $request->nama_pelanggan,
            'total' => $total,
            'bayar' => $bayar,
            'kembalian' => $kembalian,
            'tanggal' => $request->tanggal,
            'status' => $status
        ]);

        // Hapus detail lama
        $transaksi->detailTransaksi()->delete();

        // Simpan detail baru
        foreach ($request->produk as $item) {
            $produk = Produk::find($item['id']);
            $transaksi->detailTransaksi()->create([
                'produk_id' => $item['id'],
                'qty' => $item['qty'],
                'harga' => $item['harga'],
                'subtotal' => $item['qty'] * $item['harga']
            ]);
            $produk->decrement('stok_produk', $item['qty']);
        }

        DB::commit();

        return redirect()->route('transaksi.index')
            ->with('success', 'Transaksi berhasil diperbarui!');

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withErrors(['error' => $e->getMessage()])->withInput();
    }
}

    public function destroy(Transaksi $transaksi)
    {
        DB::transaction(function () use ($transaksi) {
            foreach ($transaksi->detailTransaksi as $detail) {
                $produk = $detail->produk;
                $produk->increment('stok_produk', $detail->qty);
            }
            $transaksi->detailTransaksi()->delete();
            $transaksi->delete();
        });

        return redirect()->route('transaksi.index')
            ->with('success', 'Transaksi berhasil dihapus!');
    }
}