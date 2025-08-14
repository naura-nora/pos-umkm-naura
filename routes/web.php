<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FinancialReportController;

// Redirect root ke login
Route::redirect('/', '/login')->name('home');

// Authentication Routes
Route::controller(AuthController::class)->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', 'showLoginForm')->name('login');
        Route::post('/login', 'login');
        Route::get('/register', 'showRegistrationForm')->name('register');
        Route::post('/register', 'register');
    });
    
    Route::post('/logout', 'logout')
        ->middleware('auth')
        ->name('logout');
});

// Authenticated Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    // Rute untuk semua role (index dan show)
    Route::get('/produk', [ProdukController::class, 'index'])->name('produk.index');
    Route::get('/produk/{produk}', [ProdukController::class, 'show'])->name('produk.show');
    
    // Transaksi - CRU untuk kasir dan admin
    Route::middleware(['role:admin,kasir'])->group(function () {
        Route::resource('transaksi', TransaksiController::class)
            ->except(['destroy'])
            ->names([
                'index' => 'transaksi.index',
                'create' => 'transaksi.create',
                'store' => 'transaksi.store',
                'show' => 'transaksi.show',
                'edit' => 'transaksi.edit',
                'update' => 'transaksi.update'
            ]);
    });
    
    // Hanya admin yang bisa hapus transaksi
    Route::delete('/transaksi/{transaksi}', [TransaksiController::class, 'destroy'])
        ->middleware('role:admin')
        ->name('transaksi.destroy');
    
    // Admin-only routes
    Route::prefix('admin')->middleware(['role:admin'])->group(function () {
        // Produk Management
        Route::get('/produk/create', [ProdukController::class, 'create'])->name('produk.create');
        Route::post('/produk', [ProdukController::class, 'store'])->name('produk.store');
        Route::get('/produk/{produk}/edit', [ProdukController::class, 'edit'])->name('produk.edit');
        Route::put('/produk/{produk}', [ProdukController::class, 'update'])->name('produk.update');
        Route::delete('/produk/{produk}', [ProdukController::class, 'destroy'])->name('produk.destroy');

        
        // Kategori Management
        Route::resource('kategori', KategoriController::class)
            ->except(['show'])
            ->names([
                'index' => 'kategori.index',
                'create' => 'kategori.create',
                'store' => 'kategori.store',
                'edit' => 'kategori.edit',
                'update' => 'kategori.update',
                'destroy' => 'kategori.destroy'
            ]);
        
        // User Management
        Route::prefix('user-management')->name('user-management.')->group(function () {
            Route::get('/', [UserManagementController::class, 'index'])->name('index');
            Route::get('/create', [UserManagementController::class, 'create'])->name('create');
            Route::post('/', [UserManagementController::class, 'store'])->name('store');
            Route::get('/{user}/edit', [UserManagementController::class, 'edit'])->name('edit');
            Route::put('/{user}', [UserManagementController::class, 'update'])->name('update');
            Route::delete('/{user}', [UserManagementController::class, 'destroy'])->name('destroy');
        });
        
        // Activity Log
        Route::get('/aktivitas', [ActivityLogController::class, 'index'])->name('aktivitas.index');

        Route::resource('financial-reports', FinancialReportController::class)
            ->except(['show'])
            ->names([
                'index' => 'financial-reports.index',
                'create' => 'financial-reports.create',
                'store' => 'financial-reports.store',
                'edit' => 'financial-reports.edit',
                'update' => 'financial-reports.update',
                'destroy' => 'financial-reports.destroy'
            ]);

    });
});

// Fallback Route untuk handle 404
Route::fallback(function () {
    return redirect()->route(auth()->check() ? 'dashboard' : 'login')
           ->with('error', 'Halaman tidak ditemukan');
});