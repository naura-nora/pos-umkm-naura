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
    
    // Profile Routes
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('profile.show');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/update', [ProfileController::class, 'update'])->name('profile.update');
        Route::get('/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password');
        Route::post('/update-password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
});
    
    // Produk - Read only untuk semua role
    Route::get('/produk', [ProdukController::class, 'index'])->name('produk.index');
    Route::get('/produk/{produk}', [ProdukController::class, 'show'])->name('produk.show');
    
    // Transaksi Routes
    Route::prefix('transaksi')->group(function () {
        // CRU untuk kasir dan admin
        Route::middleware(['role:admin,kasir'])->group(function () {
            Route::get('/', [TransaksiController::class, 'index'])->name('transaksi.index');
            Route::get('/create', [TransaksiController::class, 'create'])->name('transaksi.create');
            Route::post('/', [TransaksiController::class, 'store'])->name('transaksi.store');
            Route::get('/{transaksi}', [TransaksiController::class, 'show'])->name('transaksi.show');
            Route::get('/{transaksi}/edit', [TransaksiController::class, 'edit'])->name('transaksi.edit');
            Route::put('/{transaksi}', [TransaksiController::class, 'update'])->name('transaksi.update');
        });
        
        // Hanya admin yang bisa hapus transaksi
        Route::delete('/{transaksi}', [TransaksiController::class, 'destroy'])
            ->middleware('role:admin')
            ->name('transaksi.destroy');
        Route::get('/transaksi/{id}/detail', [TransaksiController::class, 'detail'])->name('transaksi.detail');
    });
    
    // Admin-only routes
    Route::prefix('admin')->middleware(['role:admin'])->group(function () {
        // Produk Management
        Route::prefix('produk')->group(function () {
            Route::get('/create', [ProdukController::class, 'create'])->name('produk.create');
            Route::post('/', [ProdukController::class, 'store'])->name('produk.store');
            Route::get('/{produk}/edit', [ProdukController::class, 'edit'])->name('produk.edit');
            Route::put('/{produk}', [ProdukController::class, 'update'])->name('produk.update');
            Route::delete('/{produk}', [ProdukController::class, 'destroy'])->name('produk.destroy');
        });
        
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

        // Financial Reports
        Route::prefix('financial-reports')->name('financial-reports.')->group(function () {
            Route::get('/', [FinancialReportController::class, 'index'])->name('index');
            Route::get('/create', [FinancialReportController::class, 'create'])->name('create');
            Route::post('/', [FinancialReportController::class, 'store'])->name('store');
            Route::get('/{financial_report}/edit', [FinancialReportController::class, 'edit'])->name('edit');
            Route::put('/{financial_report}', [FinancialReportController::class, 'update'])->name('update');
            Route::delete('/{financial_report}', [FinancialReportController::class, 'destroy'])->name('destroy');
            
            // Route khusus untuk pemasukan manual
            Route::get('/income/create', [FinancialReportController::class, 'createIncome'])->name('income.create');
            Route::post('/income', [FinancialReportController::class, 'storeIncome'])->name('income.store');
        });
    });
});

// Fallback Route untuk handle 404
Route::fallback(function () {
    return redirect()->route(auth()->check() ? 'dashboard' : 'login')
           ->with('error', 'Halaman tidak ditemukan');
});