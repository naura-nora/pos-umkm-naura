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
            
            // Redirect semua user ke dashboard setelah login
            return redirect()->intended(route('dashboard'));
        }

        // perubahan
         if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        
        // Log activity
        activity()
            ->causedBy(auth()->user())
            ->withProperties(['ip' => $request->ip()])
            ->log('login');

        return redirect()->intended('dashboard');
    }
        // end perubahan

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

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

    // Auto assign role pelanggan
    $user->assignRole('pelanggan');

    auth()->login($user);

    return redirect('/dashboard')->with('success', 'Registrasi berhasil!');
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