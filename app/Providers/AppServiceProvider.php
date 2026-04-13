<?php

namespace App\Providers;

use App\Models\Atlet;
use App\Models\Coach;
use App\Observers\AtletObserver;
use App\Observers\CoachObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Atlet::observe(AtletObserver::class);
        Coach::observe(CoachObserver::class);
    }
}
