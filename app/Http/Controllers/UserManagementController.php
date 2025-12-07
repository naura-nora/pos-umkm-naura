<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserManagementController extends Controller
{
    public function index(Request $request)
{
    $search = $request->input('search');
    
    $users = User::with('roles')
        ->when($search, function($query, $search) {
            return $query->where('name', 'like', '%'.$search.'%')
                        ->orWhere('email', 'like', '%'.$search.'%')
                        ->orWhereHas('roles', function($q) use ($search) {
                            $q->where('name', 'like', '%'.$search.'%');
                        });
        })
        ->orderBy('created_at', 'desc')
        ->paginate(5);
    
    return view('user-management.index', compact('users'));
}

    public function create()
    {
        $roles = Role::all();
        return view('user-management.create', compact('roles'));
    }

    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8',
        'role' => 'sometimes|exists:roles,name'
    ]);

    // Ambil semua input, kecuali _token dan role (karena role diproses terpisah)
    $userData = $request->except(['_token', 'role']);

    // Hash password
    $userData['password'] = Hash::make($userData['password']);

    // Tentukan role (default: pelanggan)
    $role = $request->role ?? 'pelanggan';

    // Jika role = pelanggan → generate kode_pelanggan
    if ($role === 'pelanggan') {
        $tahun = date('y'); // Ambil 2 digit tahun terakhir, misal: 25

        // Cari kode pelanggan terakhir tahun ini
        $lastPelanggan = User::where('kode_pelanggan', 'like', "CS{$tahun}%")
            ->orderBy('kode_pelanggan', 'desc')
            ->first();

        $sequence = 1;
        if ($lastPelanggan) {
            // Ambil 4 digit terakhir dari kode_pelanggan
            $lastSequence = (int) substr($lastPelanggan->kode_pelanggan, -4);
            $sequence = $lastSequence + 1;
        }

        // Format: CS250001, CS250002, dst
        $kodePelanggan = 'CS' . $tahun . str_pad($sequence, 4, '0', STR_PAD_LEFT);

        // Tambahkan ke data user
        $userData['kode_pelanggan'] = $kodePelanggan;
    }

    // Simpan user
    $user = User::create($userData);

    // Assign role
    $user->assignRole($role);

    return redirect()->route('user-management.index')->with('success', 'User berhasil dibuat!');
}

    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('user-management.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('user-management.edit', compact('user', 'roles'));
    }
    

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|exists:roles,name'
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        
        $user->syncRoles($request->role);

        return redirect()->route('user-management.index')->with('success', 'User berhasil diperbarui!.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('user-management.index')->with('success', 'User deleted successfully.');
    }
}