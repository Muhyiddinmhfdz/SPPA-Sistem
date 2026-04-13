<?php

namespace App\Observers;

use App\Models\Coach;
use App\Services\CaborCountSyncService;

class CoachObserver
{
    public function created(Coach $coach): void
    {
        app(CaborCountSyncService::class)->sync($coach->cabor_id);
    }

    public function updated(Coach $coach): void
    {
        $syncService = app(CaborCountSyncService::class);

        $originalCaborId = $coach->getOriginal('cabor_id');
        if ($originalCaborId && (int) $originalCaborId !== (int) $coach->cabor_id) {
            $syncService->sync((int) $originalCaborId);
        }

        $syncService->sync($coach->cabor_id);
    }

    public function deleted(Coach $coach): void
    {
        app(CaborCountSyncService::class)->sync($coach->cabor_id);
    }

    public function restored(Coach $coach): void
    {
        app(CaborCountSyncService::class)->sync($coach->cabor_id);
    }

    public function forceDeleted(Coach $coach): void
    {
        app(CaborCountSyncService::class)->sync($coach->cabor_id);
    }
}
