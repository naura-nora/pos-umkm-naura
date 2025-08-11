<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->get();
        $roles = Role::all();
        return view('user-management.index', compact('users', 'roles'));
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
        'password' => 'required|string|min:8|confirmed',
        'role' => 'sometimes|exists:roles,name' // Ubah required menjadi sometimes
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

    // Jika role tidak dipilih, assign role pelanggan
    $role = $request->role ?? 'pelanggan';
    $user->assignRole($role);

    return redirect()->route('user-management.index')->with('success', 'User berhasil dibuat!');
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