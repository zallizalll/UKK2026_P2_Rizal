<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TarifController;
use App\Http\Controllers\KendaraanController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Petugas\DashboardController as PetugasDashboard;
use App\Http\Controllers\Owner\DashboardController as OwnerDashboard;

// ===== AUTH =====
Route::get('/', [AuthController::class, 'showLogin']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// ===== ADMIN =====
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

        Route::get('/users',         [UserController::class, 'index'])->name('users');
        Route::post('/users',        [UserController::class, 'store'])->name('users.store');
        Route::put('/users/{id}',    [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::get('/users/print/{role}', [UserController::class, 'print'])->name('users.print');

        Route::get('/tarif',         [TarifController::class, 'index'])->name('tarif');
        Route::post('/tarif',        [TarifController::class, 'store'])->name('tarif.store');
        Route::put('/tarif/{id}',    [TarifController::class, 'update'])->name('tarif.update');
        Route::delete('/tarif/{id}', [TarifController::class, 'destroy'])->name('tarif.destroy');

        Route::get('kendaraan', [KendaraanController::class, 'index'])->name('kendaraan');
        Route::post('kendaraan', [KendaraanController::class, 'store'])->name('kendaraan.store');
        Route::put('kendaraan/{id}', [KendaraanController::class, 'update'])->name('kendaraan.update');
        Route::delete('kendaraan/{id}', [KendaraanController::class, 'destroy'])->name('kendaraan.destroy');

        Route::get('/area',      fn() => abort(404))->name('area');
        Route::get('/laporan',   fn() => abort(404))->name('laporan');
        Route::get('/transaksi', fn() => abort(404))->name('transaksi');
    });

// ===== PETUGAS =====
Route::middleware(['auth', 'role:petugas'])
    ->prefix('petugas')->name('petugas.')
    ->group(function () {
        Route::get('/dashboard', [PetugasDashboard::class, 'index'])->name('dashboard');

        Route::get('/transaksi', fn() => abort(404))->name('transaksi');
        Route::get('/riwayat',   fn() => abort(404))->name('riwayat');
    });

// ===== OWNER =====
Route::middleware(['auth', 'role:owner'])
    ->prefix('owner')->name('owner.')
    ->group(function () {
        Route::get('/dashboard',  [OwnerDashboard::class, 'index'])->name('dashboard');
        Route::get('/laporan',    fn() => abort(404))->name('laporan');
        Route::get('/pendapatan', fn() => abort(404))->name('pendapatan');
    });
