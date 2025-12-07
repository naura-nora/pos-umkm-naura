<?php

namespace App\Http\Controllers;

use App\Models\Retur;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Produk;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReturController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,kasir');
    }

    public function index()
    {
        $returs = Retur::with(['transaksi', 'produk', 'user'])
            ->latest()
            ->paginate(10);

        return view('retur.index', compact('returs'));
    }

    public function create()
    {
        // Ambil transaksi Lunas yang masih punya produk yang bisa diretur
        $transaksis = Transaksi::where('status', 'Lunas')
            ->with(['detailTransaksi.produk'])
            ->orderBy('tanggal', 'desc')
            ->get()
            ->filter(function ($transaksi) {
                // Cek apakah masih ada produk yang bisa diretur
                foreach ($transaksi->detailTransaksi as $detail) {
                    $totalRetur = Retur::where('transaksi_id', $transaksi->id)
                        ->where('produk_id', $detail->produk_id)
                        ->sum('qty');
                    
                    if (($detail->qty - $totalRetur) > 0) {
                        return true; // Masih ada produk yang bisa diretur
                    }
                }
                return false; // Tidak ada produk yang bisa diretur
            });

        return view('retur.create', compact('transaksis'));
    }


    public function getProduk($transaksiId)
{
    try {
        $detailTransaksi = DetailTransaksi::with('produk')
            ->where('transaksi_id', $transaksiId)
            ->get();

        $produkData = [];

        foreach ($detailTransaksi as $item) {
            $totalRetur = Retur::where('transaksi_id', $transaksiId)
                ->where('produk_id', $item->produk_id)
                ->sum('qty');

            $sisaQty = $item->qty - $totalRetur;

            if ($sisaQty > 0) {
                $produkData[] = [
                    'id' => $item->produk_id,
                    'nama_produk' => $item->produk->nama_produk,
                    'qty' => $sisaQty,
                    'harga' => $item->harga,
                    'stok_tersedia' => $item->produk->stok_produk, // ✅ Tambahkan ini
                ];
            }
        }

        return response()->json([
            'success' => true,
            'data' => $produkData
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error mengambil data produk: ' . $e->getMessage()
        ], 500);
    }
}



    public function store(Request $request)
{
    $request->validate([
        'transaksi_id' => 'required|exists:transaksi,id',
        'produk_id' => 'required|exists:produk,id',
        'qty' => 'required|integer|min:1',
        'alasan' => 'required|string|max:500',
        'metode_pengembalian' => 'required|in:barang_baru,uang_kembali',
    ]);

    // Cek apakah produk ada di transaksi
    $detail = DetailTransaksi::where('transaksi_id', $request->transaksi_id)
        ->where('produk_id', $request->produk_id)
        ->first();

    if (!$detail) {
        return back()->withErrors(['produk_id' => 'Produk tidak ditemukan dalam transaksi ini.']);
    }

    // Cek apakah jumlah retur tidak melebihi jumlah beli
    if ($request->qty > $detail->qty) {
        return back()->withErrors(['qty' => 'Jumlah retur tidak boleh melebihi jumlah pembelian.']);
    }

    // Cek apakah jumlah retur tidak melebihi sisa qty
    $totalReturSebelumnya = Retur::where('transaksi_id', $request->transaksi_id)
        ->where('produk_id', $request->produk_id)
        ->sum('qty');

    $sisaQty = $detail->qty - $totalReturSebelumnya;

    if ($request->qty > $sisaQty) {
        return back()->withErrors(['qty' => 'Jumlah retur melebihi sisa jumlah yang bisa diretur.']);
    }

    // ✅ HANYA BUAT RETUR DENGAN STATUS = 'pending' — TIDAK ADA EFEK STOK ATAU UANG
    $retur = Retur::create([
        'transaksi_id' => $request->transaksi_id,
        'produk_id' => $request->produk_id,
        'qty' => $request->qty,
        'alasan' => $request->alasan,
        'user_id' => auth()->id(),
        'metode_pengembalian' => $request->metode_pengembalian,
        'status' => 'pending', // 👈 Ini sudah default, tapi kita pastikan
    ]);

    return redirect()->route('retur.index')->with('success', 'Retur berhasil diajukan. Menunggu persetujuan admin.');
    }

        public function edit($id)
    {
        $retur = Retur::findOrFail($id);
        return view('retur.edit', compact('retur'));
    }


    public function update(Request $request, $id)
    {
        // Validasi data
        $validator = Validator::make($request->all(), [
            'qty' => 'required|integer|min:1',
            'alasan' => 'required|string|max:255',
            
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Find the retur record
        $retur = Retur::findOrFail($id);

        // Update data
        $retur->update([
            'qty' => $request->qty,
            'alasan' => $request->alasan,
            
        ]);

        return redirect()->route('retur.index')
            ->with('success', 'Data retur berhasil diperbarui');
    }


    public function show($id)
    {
        $retur = Retur::with(['transaksi', 'produk.kategori', 'user'])->findOrFail($id);
        return view('retur.show', compact('retur'));
    }

    public function destroy($id)
    {
        $retur = Retur::findOrFail($id);
        $retur->delete();

        return redirect()->route('retur.index')->with('success', 'Retur berhasil di hapus!');
    }

    public function approve(Retur $retur)
    {
        if ($retur->status !== 'pending') {
            return redirect()->back()->withErrors('Retur tidak dalam status menunggu.');
        }

        DB::beginTransaction();

        try {
            // ✅ INI ADALAH LOGIKA LAMA YANG SUDAH BERJALAN — KITA JALANKAN SEKARANG
            if ($retur->metode_pengembalian === 'barang_baru') {
                $produk = Produk::findOrFail($retur->produk_id);

                if ($produk->stok_produk < $retur->qty) {
                    DB::rollBack();
                    return redirect()->back()->withErrors([
                        'qty' => 'Stok produk tidak mencukupi untuk penggantian. Stok tersedia: ' . $produk->stok_produk
                    ]);
                }

                $produk->stok_produk -= $retur->qty;
                $produk->save();
            }

            if ($retur->metode_pengembalian === 'uang_kembali') {
                $detail = DetailTransaksi::where('transaksi_id', $retur->transaksi_id)
                    ->where('produk_id', $retur->produk_id)
                    ->first();

                $jumlahUangKembali = $retur->qty * $detail->harga;

                \App\Models\FinancialReport::create([
                    'report_date' => now()->toDateString(),
                    'description' => "Pengembalian uang retur produk {$detail->produk->nama_produk} (Transaksi: {$detail->transaksi->kode})",
                    'type' => 'expense',
                    'amount' => $jumlahUangKembali,
                    'income' => -$jumlahUangKembali, // 👈 Tetap pakai logika lama
                    'user_id' => auth()->id(),
                    'transaksi_id' => $retur->transaksi_id,
                    'retur_id' => $retur->id,
                    'source' => 'uang_kembali',
                ]);
            }

            // ✅ SETELAH SEMUA LOGIKA BERJALAN, BARU UPDATE STATUS
            $retur->status = 'approved';
            $retur->save();

            DB::commit();

            return redirect()->route('retur.index')->with('success', 'Retur berhasil disetujui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['general' => 'Gagal menyetujui retur: ' . $e->getMessage()]);
        }
    }

    public function reject(Retur $retur)
    {
        if ($retur->status !== 'pending') {
            return redirect()->back()->withErrors('Retur tidak dalam status menunggu.');
        }

        $retur->status = 'rejected';
        $retur->save();

        return redirect()->route('retur.index')->with('warning', 'Retur berhasil ditolak.');
    }
}