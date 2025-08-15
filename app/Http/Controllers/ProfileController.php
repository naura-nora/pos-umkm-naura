<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('profile.show', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
{
    $user = auth()->user();
    
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
        // Remove phone and address validation if columns don't exist
    ]);

    $user->update([
        'name' => $request->name,
        'email' => $request->email,
        // Don't include phone and address in the update if columns don't exist
    ]);

    return redirect()->route('profile.show')
        ->with('success', 'Profil berhasil diperbarui!');
}

    public function changePassword()
    {
        return view('profile.change-password');
    }


    public function updatePhoto(Request $request)
{
    $request->validate([
        'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
    ]);

    if ($request->hasFile('photo')) {
        // Hapus foto lama jika ada
        if (auth()->user()->photo && Storage::exists('public/'.auth()->user()->photo)) {
            Storage::delete('public/'.auth()->user()->photo);
        }
        
        // Simpan foto baru
        $path = $request->file('photo')->store('profile-photos', 'public');
        
        // Update path foto di database
        auth()->user()->update(['photo' => $path]);
    }

    return back()->with('success', 'Foto profil berhasil diperbarui!');
}

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ],
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini salah']);
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return redirect()->route('profile.show')
            ->with('success', 'Password berhasil diubah!');
    }
}