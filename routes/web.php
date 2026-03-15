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
use App\Http\Controllers\PembinaanPrestasiController;
use App\Http\Controllers\KompetisiController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {});

Route::name('dashboard.')->middleware('auth')->prefix('dashboard')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('index');
    Route::get('/medal-details/{caborId}', [DashboardController::class, 'getMedalDetails'])->name('medal-details');
    Route::get('/performance-test-details/{id}', [DashboardController::class, 'getPerformanceTestDetails'])->name('performance-test-detail');
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

    // Jenis Tes (Parameter Tes Fisik) Routes
    Route::get('jenis-tes', [App\Http\Controllers\JenisTesController::class, 'index'])->name('jenis-tes.index');
    Route::post('jenis-tes', [App\Http\Controllers\JenisTesController::class, 'store'])->name('jenis-tes.store');
    Route::get('jenis-tes/items/{categoryId}', [App\Http\Controllers\JenisTesController::class, 'getItems'])->name('jenis-tes.get-items');
    Route::post('jenis-tes/item-store', [App\Http\Controllers\JenisTesController::class, 'storeItem'])->name('jenis-tes.store-item');
    Route::delete('jenis-tes/item-delete/{id}', [App\Http\Controllers\JenisTesController::class, 'destroyItem'])->name('jenis-tes.delete-item');
    Route::get('jenis-tes/detail/{id}', [App\Http\Controllers\JenisTesController::class, 'getDetail'])->name('jenis-tes.detail');

    Route::get('jenis-tes/scores/{itemId}', [App\Http\Controllers\JenisTesController::class, 'getScores'])->name('jenis-tes.get-scores');
    Route::post('jenis-tes/score-store', [App\Http\Controllers\JenisTesController::class, 'storeScore'])->name('jenis-tes.store-score');
    Route::delete('jenis-tes/score-delete/{id}', [App\Http\Controllers\JenisTesController::class, 'destroyScore'])->name('jenis-tes.delete-score');

    // Training Type Routes
    Route::resource('training-type', TrainingTypeController::class)->except(['create']);
    Route::get('training-type/components/{typeId}', [TrainingTypeController::class, 'getComponents'])->name('training-type.get-components');
    Route::post('training-type/component-store', [TrainingTypeController::class, 'storeComponent'])->name('training-type.store-component');
    Route::delete('training-type/component-delete/{id}', [TrainingTypeController::class, 'destroyComponent'])->name('training-type.delete-component');

    // Score Management Routes
    Route::get('training-type/scores/{componentId}', [TrainingTypeController::class, 'getScores'])->name('training-type.get-scores');
    Route::post('training-type/score-store', [TrainingTypeController::class, 'storeScore'])->name('training-type.store-score');
    Route::delete('training-type/score-delete/{id}', [TrainingTypeController::class, 'destroyScore'])->name('training-type.delete-score');
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

// ── Pembinaan Prestasi ────────────────────────────────────────────────────────
Route::middleware('auth')->prefix('pembinaan-prestasi')->name('pembinaan-prestasi.')->group(function () {
    Route::get('/', [PembinaanPrestasiController::class, 'index'])->name('index');
    Route::post('/', [PembinaanPrestasiController::class, 'store'])->name('store');
    Route::get('/training-data', [PembinaanPrestasiController::class, 'getTrainingData'])->name('training-data');
    Route::get('/get-components', [PembinaanPrestasiController::class, 'getComponents'])->name('components');
    Route::get('/{id}/edit', [PembinaanPrestasiController::class, 'edit'])->name('edit');
    Route::put('/{id}', [PembinaanPrestasiController::class, 'update'])->name('update');
    Route::delete('/{id}', [PembinaanPrestasiController::class, 'destroy'])->name('destroy');
});

// ── Kompetisi ─────────────────────────────────────────────────────────────────
Route::middleware('auth')->prefix('kompetisi')->name('kompetisi.')->group(function () {
    Route::get('/', [KompetisiController::class, 'index'])->name('index');
    Route::post('/', [KompetisiController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [KompetisiController::class, 'edit'])->name('edit');
    Route::put('/{id}', [KompetisiController::class, 'update'])->name('update');
    Route::delete('/{id}', [KompetisiController::class, 'destroy'])->name('destroy');
    Route::get('/atlets/{caborId}', [KompetisiController::class, 'getAtletsByCabor'])->name('get-atlets');
});

// ── Tes Performa ──────────────────────────────────────────────────────────────
Route::middleware('auth')->prefix('tes-performa')->name('tes-performa.')->group(function () {
    Route::get('/', [App\Http\Controllers\PerformanceTestController::class, 'index'])->name('index');
    Route::post('/', [App\Http\Controllers\PerformanceTestController::class, 'store'])->name('store');
    Route::get('/atlet-data/{atletId}', [App\Http\Controllers\PerformanceTestController::class, 'getAtletData'])->name('atlet-data');
    Route::get('/test-items/{atletId}', [App\Http\Controllers\PerformanceTestController::class, 'getTestItems'])->name('test-items');
    Route::get('/{id}', [App\Http\Controllers\PerformanceTestController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [App\Http\Controllers\PerformanceTestController::class, 'edit'])->name('edit');
    Route::put('/{id}', [App\Http\Controllers\PerformanceTestController::class, 'update'])->name('update');
    Route::delete('/{id}', [App\Http\Controllers\PerformanceTestController::class, 'destroy'])->name('destroy');
});
