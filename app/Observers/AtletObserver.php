<?php

namespace App\Observers;

use App\Models\Atlet;
use App\Services\CaborCountSyncService;

class AtletObserver
{
    public function created(Atlet $atlet): void
    {
        app(CaborCountSyncService::class)->sync($atlet->cabor_id);
    }

    public function updated(Atlet $atlet): void
    {
        $syncService = app(CaborCountSyncService::class);

        $originalCaborId = $atlet->getOriginal('cabor_id');
        if ($originalCaborId && (int) $originalCaborId !== (int) $atlet->cabor_id) {
            $syncService->sync((int) $originalCaborId);
        }

        $syncService->sync($atlet->cabor_id);
    }

    public function deleted(Atlet $atlet): void
    {
        app(CaborCountSyncService::class)->sync($atlet->cabor_id);
    }

    public function restored(Atlet $atlet): void
    {
        app(CaborCountSyncService::class)->sync($atlet->cabor_id);
    }

    public function forceDeleted(Atlet $atlet): void
    {
        app(CaborCountSyncService::class)->sync($atlet->cabor_id);
    }
}
