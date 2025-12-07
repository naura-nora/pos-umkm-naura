<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::attempt($credentials, $request->remember)) {
        $request->session()->regenerate();

        activity()
            ->causedBy(auth()->user())
            ->withProperties(['ip' => $request->ip()])
            ->log('login');

        return redirect()->route('dashboard');
    }

    return back()->withErrors([
        'email' => 'Email atau password salah',
    ])->onlyInput('email');
}


    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Ambil 2 digit tahun terakhir
        $tahun = date('y');

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

        // Buat user dengan kode_pelanggan
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'kode_pelanggan' => $kodePelanggan, // ← TAMBAHKAN INI
        ]);

        // Auto assign role pelanggan
        $user->assignRole('pelanggan');

        // Login otomatis
        auth()->login($user);

        // Log activity
        activity()
            ->causedBy($user)
            ->log('register');

        return redirect('/dashboard')->with('success', 'Registrasi berhasil! Kode pelanggan Anda: ' . $kodePelanggan);
    }

    public function logout(Request $request)
    {
        // Log activity sebelum logout
        activity()
            ->causedBy(auth()->user())
            ->withProperties(['ip' => $request->ip()])
            ->log('logout');


        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}