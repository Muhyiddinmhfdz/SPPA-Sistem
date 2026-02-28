<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\CaborController;

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
});
