<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        $routeName = $request->route()->getName();

        // 1. Admin → akses penuh
        if ($user->role === 'admin') {
            return $next($request);
        }

        // 2. Kasir
        if ($user->role === 'kasir') {
            $kasirRoutes = [
                'dashboard',
                'produk.index',
                'produk.show',
                'transaksi.index',
                'transaksi.create',
                'transaksi.store',
                'transaksi.show',
                'transaksi.edit',
                'transaksi.update',
            ];

            if (in_array($routeName, $kasirRoutes)) {
                return $next($request);
            }

            abort(403, 'Anda tidak memiliki izin mengakses halaman ini.');
        }

        // 3. Pelanggan
        if ($user->role === 'pelanggan') {
            $pelangganRoutes = [
                'dashboard',
                'produk.index',
                'produk.show',
            ];

            if (in_array($routeName, $pelangganRoutes)) {
                return $next($request);
            }

            abort(403, 'Anda tidak memiliki izin mengakses halaman ini.');
        }

        // Jika role tidak dikenali
        abort(403, 'Anda tidak memiliki izin mengakses halaman ini.');
    }
}
