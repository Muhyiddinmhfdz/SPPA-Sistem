<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\CaborController;
use App\Http\Controllers\CoachController;
use App\Http\Controllers\KlasifikasiDisabilitasController;
use App\Http\Controllers\MedisController;
use App\Http\Controllers\AtletController;
use App\Http\Controllers\JenisDisabilitasController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {});

Route::name('dashboard.')->middleware('auth')->prefix('dashboard')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('index');
});

// Remove trailing dot for the direct dashboard route to fix the 'Route [dashboard] not defined' error
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::name('master.')->middleware('auth')->prefix('master')->group(function () {
    // Role Management Routes
    Route::resource('role', RoleController::class)->except(['create', 'show']);

    // User Management Routes
    Route::resource('user', UserController::class)->except(['create', 'show']);

    // Cabor Management Routes
    Route::resource('cabor', CaborController::class)->except(['create', 'show']);

    // Coach Management Routes
    Route::resource('coach', CoachController::class)->except(['create', 'show']);

    // Klasifikasi Disabilitas Routes
    Route::resource('klasifikasi-disabilitas', KlasifikasiDisabilitasController::class)->except(['create', 'show']);

    // Medis Routes
    Route::resource('medis', MedisController::class)->except(['create', 'show']);

    // Atlet Routes
    Route::resource('atlet', AtletController::class)->except(['create', 'show']);

    // Jenis Disabilitas Routes
    Route::resource('jenis-disabilitas', JenisDisabilitasController::class)->except(['create', 'show']);
});
