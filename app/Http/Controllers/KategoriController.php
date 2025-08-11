<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KategoriController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function index()
    {
        $kategori = Kategori::orderBy('created_at', 'desc')->paginate(7);
        return view('kategori.index', compact('kategori'));
    }

    public function create()
    {
        return view('kategori.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori,nama_kategori',
        ]);

        try {
            DB::beginTransaction();
            
            Kategori::create($request->all());
            
            DB::commit();
            
            return redirect()->route('kategori.index')
                ->with('success', 'Kategori berhasil ditambahkan');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menambahkan kategori: '.$e->getMessage());
        }
    }

    public function show(string $id)
    {
        abort(404);
    }

    public function edit(string $id)
    {
        $kategori = Kategori::findOrFail($id);
        return view('kategori.edit', compact('kategori'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori,nama_kategori,'.$id,
        ]);

        try {
            DB::beginTransaction();
            
            $kategori = Kategori::findOrFail($id);
            $kategori->update($request->all());
            
            DB::commit();
            
            return redirect()->route('kategori.index')
                ->with('success', 'Kategori berhasil diperbarui');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal memperbarui kategori: '.$e->getMessage());
        }
    }

    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();
            
            $kategori = Kategori::findOrFail($id);
            $kategori->delete();
            
            DB::commit();
            
            return redirect()->route('kategori.index')
                ->with('success', 'Kategori berhasil dihapus');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menghapus kategori: '.$e->getMessage());
        }
    }
}