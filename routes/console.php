<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Services\CaborCountSyncService;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('cabor:sync-counts', function (CaborCountSyncService $syncService) {
    $syncService->syncAll();
    $this->info('Sinkronisasi jumlah atlet/pelatih aktif per cabor selesai.');
})->purpose('Sync active athlete and coach counts for all cabor');
