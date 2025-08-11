<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\ActivityLogController;

// Redirect root to login
Route::redirect('/', '/login');

// Authentication Routes
Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'login');
    Route::get('/register', 'showRegistrationForm')->name('register');
    Route::post('/register', 'register');
    Route::post('/logout', 'logout')->name('logout');
});

// Authenticated Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard - accessible to all roles
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    // Profile - accessible to all roles
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    // Produk - Read only for all roles
    Route::get('/produk', [ProdukController::class, 'index'])->name('produk.index');
    Route::get('/produk/{produk}', [ProdukController::class, 'show'])->name('produk.show');
    
    // Transaksi Routes
    Route::prefix('transaksi')->group(function () {
        // Common routes for both roles
        Route::get('/', [TransaksiController::class, 'index'])->name('transaksi.index');
        Route::get('/create', [TransaksiController::class, 'create'])->name('transaksi.create');
        Route::post('/', [TransaksiController::class, 'store'])->name('transaksi.store');
        Route::get('/{transaksi}', [TransaksiController::class, 'show'])->name('transaksi.show');
        
        // Edit/Update routes with additional middleware
        Route::middleware(['role:admin,kasir'])->group(function () {
            Route::get('/{transaksi}/edit', [TransaksiController::class, 'edit'])->name('transaksi.edit');
            Route::put('/{transaksi}', [TransaksiController::class, 'update'])->name('transaksi.update');
        });
        
        // Delete only for admin
        Route::delete('/{transaksi}', [TransaksiController::class, 'destroy'])
            ->middleware('role:admin')
            ->name('transaksi.destroy');
    });
    
    // Admin-only routes
    Route::prefix('admin')->middleware(['role:admin'])->group(function () {
        // Produk Management
        Route::get('/produk/create', [ProdukController::class, 'create'])->name('produk.create');
        Route::post('/produk', [ProdukController::class, 'store'])->name('produk.store');
        Route::get('/produk/{produk}/edit', [ProdukController::class, 'edit'])->name('produk.edit');
        Route::put('/produk/{produk}', [ProdukController::class, 'update'])->name('produk.update');
        Route::delete('/produk/{produk}', [ProdukController::class, 'destroy'])->name('produk.destroy');
        
        // Kategori Management
        Route::resource('kategori', KategoriController::class);
        
        // User Management
        Route::prefix('user-management')->group(function () {
            Route::get('/', [UserManagementController::class, 'index'])->name('user-management.index');
            Route::resource('users', UserManagementController::class);
        });
        
        // Activity Log
        Route::get('/aktivitas', [ActivityLogController::class, 'index'])->name('aktivitas.index');
    });
});