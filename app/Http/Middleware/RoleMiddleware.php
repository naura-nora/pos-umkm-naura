<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    // public function handle(Request $request, Closure $next, ...$roles): Response
    // {
    //     $user = $request->user();
        
    //     if (!$user) {
    //         return redirect()->route('login');
    //     }

    //     // Admin memiliki akses penuh
    //     if ($user->hasRole('admin')) {
    //         return $next($request);
    //     }

    //     // Cek role yang diizinkan
    //     foreach ($roles as $role) {
    //         if ($user->hasRole($role)) {
    //             return $next($request);
    //         }
    //     }

    //     abort(403, 'Anda tidak memiliki izin mengakses halaman ini.');
    // }

    public function handle(Request $request, Closure $next, ...$roles): Response
{
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    $user = auth()->user();
    
    // Skip untuk super admin
    if ($user->hasRole('admin')) {
        return $next($request);
    }

    // Cek role yang diizinkan
    foreach ($roles as $role) {
        if ($user->hasRole($role)) {
            return $next($request);
        }
    }

    abort(403, 'Unauthorized');
}
}