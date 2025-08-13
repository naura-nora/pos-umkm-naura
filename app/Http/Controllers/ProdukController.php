<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $search = $request->input('search');
    
    $produks = Produk::with('kategori')
        ->when($search, function($query, $search) {
            return $query->where('nama_produk', 'like', '%'.$search.'%')
                        ->orWhere('harga_produk', 'like', '%'.$search.'%')
                        ->orWhereHas('kategori', function($q) use ($search) {
                            $q->where('nama_kategori', 'like', '%'.$search.'%');
                        });
        })
        ->orderBy('created_at', 'desc')
        ->paginate(5);
    
    return view('produk.index', compact('produks'));
}
    /**
     * Show the form for creating a new product.
     */
    public function create()
{
    $kategori = Kategori::all();
    
    if ($kategori->isEmpty()) {
        return redirect()->route('kategori.index')
            ->with('error', 'Belum ada kategori tersedia. Silakan buat kategori terlebih dahulu.');
    }

    return view('produk.create', compact('kategori'));
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'harga_produk' => 'required|integer|min:0',
            'stok_produk' => 'required|integer|min:0',
            'kategori_id' => 'required|exists:kategori,id',
            'gambar_produk' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['nama_produk', 'harga_produk', 'stok_produk', 'kategori_id']);

        // Upload gambar jika ada
        if ($request->hasFile('gambar_produk')) {
            $file = $request->file('gambar_produk');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('adminlte/img/'), $filename);
            $data['gambar_produk'] = $filename;
        }

        Produk::create($data);

        return redirect()->route('produk.index')
            ->with('success', 'Produk berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Produk $produk)
    {
        $produk->load('kategori');
        return view('produk.show', compact('produk'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Produk $produk)
    {
        $kategori = Kategori::all();
        return view('produk.edit', compact('produk', 'kategori'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Produk $produk)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'harga_produk' => 'required|integer|min:0',
            'stok_produk' => 'required|integer|min:0',
            'kategori_id' => 'required|exists:kategori,id',
            'gambar_produk' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['nama_produk', 'harga_produk', 'stok_produk', 'kategori_id']);

        // Handle gambar baru
        if ($request->hasFile('gambar_produk')) {
            // Hapus gambar lama jika ada
            if ($produk->gambar_produk && file_exists(public_path('adminlte/img/' . $produk->gambar_produk))) {
                unlink(public_path('adminlte/img/' . $produk->gambar_produk));
            }

            $file = $request->file('gambar_produk');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('adminlte/img/'), $filename);
            $data['gambar_produk'] = $filename;
        }

        $produk->update($data);

        return redirect()->route('produk.index')
            ->with('success', 'Produk berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Produk $produk)
    {
        // Hapus gambar jika ada
        if ($produk->gambar_produk && file_exists(public_path('adminlte/img/' . $produk->gambar_produk))) {
            unlink(public_path('adminlte/img/' . $produk->gambar_produk));
        }

        $produk->delete();

        return redirect()->route('produk.index')
            ->with('success', 'Produk berhasil dihapus!');
    }
}