<?php

namespace App\Services;

use App\Models\Atlet;
use App\Models\Cabor;
use App\Models\Coach;

class CaborCountSyncService
{
    public function syncAll(): void
    {
        Cabor::withTrashed()->select('id')->chunkById(200, function ($cabors) {
            foreach ($cabors as $cabor) {
                $this->sync($cabor->id);
            }
        });
    }

    public function sync(?int $caborId): void
    {
        if (!$caborId) {
            return;
        }

        $cabor = Cabor::withTrashed()->find($caborId);
        if (!$cabor) {
            return;
        }

        $athleteCount = Atlet::query()
            ->where('cabor_id', $caborId)
            ->where('is_active', 1)
            ->count();

        $coachCount = Coach::query()
            ->where('cabor_id', $caborId)
            ->where('is_active', 1)
            ->count();

        $cabor->forceFill([
            'active_athletes_count' => $athleteCount,
            'active_coaches_count' => $coachCount,
        ])->saveQuietly();
    }
}
