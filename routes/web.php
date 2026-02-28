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
use App\Http\Controllers\CekKesehatanController;
use App\Http\Controllers\RiwayatKesehatanController;
use App\Http\Controllers\MonitoringLatihanController;
use App\Http\Controllers\RiwayatLatihanController;
use App\Http\Controllers\TrainingTypeController;

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

    // Training Type Routes
    Route::resource('training-type', TrainingTypeController::class)->except(['create', 'show']);
    Route::get('training-type/components/{typeId}', [TrainingTypeController::class, 'getComponents'])->name('training-type.get-components');
    Route::post('training-type/component-store', [TrainingTypeController::class, 'storeComponent'])->name('training-type.store-component');
    Route::delete('training-type/component-delete/{id}', [TrainingTypeController::class, 'destroyComponent'])->name('training-type.delete-component');
});

// ── Cek Kesehatan ─────────────────────────────────────────────────────────────
Route::middleware('auth')->prefix('cek-kesehatan')->name('cek-kesehatan.')->group(function () {
    Route::get('/', [CekKesehatanController::class, 'indexAtlet'])->name('index');
    Route::get('/datatable/atlet', [CekKesehatanController::class, 'indexAtlet'])->name('datatable.atlet');
    Route::get('/datatable/pelatih', [CekKesehatanController::class, 'indexPelatih'])->name('datatable.pelatih');
    Route::get('/persons', [CekKesehatanController::class, 'getPersonsByCabor'])->name('persons');
    Route::post('/', [CekKesehatanController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [CekKesehatanController::class, 'edit'])->name('edit');
    Route::put('/{id}', [CekKesehatanController::class, 'update'])->name('update');
    Route::delete('/{id}', [CekKesehatanController::class, 'destroy'])->name('destroy');
});

// ── Riwayat Kesehatan ─────────────────────────────────────────────────────────
Route::middleware('auth')->prefix('riwayat-kesehatan')->name('riwayat-kesehatan.')->group(function () {
    Route::get('/', [RiwayatKesehatanController::class, 'index'])->name('index');
    Route::get('/data/atlet', [RiwayatKesehatanController::class, 'dataAtlet'])->name('data.atlet');
    Route::get('/data/pelatih', [RiwayatKesehatanController::class, 'dataPelatih'])->name('data.pelatih');
    Route::get('/detail', [RiwayatKesehatanController::class, 'detailRiwayat'])->name('detail');
});

// ── Monitoring Latihan ────────────────────────────────────────────────────────
Route::middleware('auth')->prefix('monitoring-latihan')->name('monitoring-latihan.')->group(function () {
    Route::get('/', [MonitoringLatihanController::class, 'indexAtlet'])->name('index');
    Route::get('/datatable/atlet', [MonitoringLatihanController::class, 'indexAtlet'])->name('datatable.atlet');
    Route::get('/datatable/pelatih', [MonitoringLatihanController::class, 'indexPelatih'])->name('datatable.pelatih');
    Route::get('/persons', [MonitoringLatihanController::class, 'getPersonsByCabor'])->name('persons');
    Route::post('/', [MonitoringLatihanController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [MonitoringLatihanController::class, 'edit'])->name('edit');
    Route::put('/{id}', [MonitoringLatihanController::class, 'update'])->name('update');
    Route::delete('/{id}', [MonitoringLatihanController::class, 'destroy'])->name('destroy');
});

// ── Riwayat Latihan ───────────────────────────────────────────────────────────
Route::middleware('auth')->prefix('riwayat-latihan')->name('riwayat-latihan.')->group(function () {
    Route::get('/', [RiwayatLatihanController::class, 'index'])->name('index');
    Route::get('/data/atlet', [RiwayatLatihanController::class, 'dataAtlet'])->name('data.atlet');
    Route::get('/data/pelatih', [RiwayatLatihanController::class, 'dataPelatih'])->name('data.pelatih');
    Route::get('/detail', [RiwayatLatihanController::class, 'detailRiwayat'])->name('detail');
});
