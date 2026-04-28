<?php

namespace App\Providers;

use App\Models\Lease;
use App\Observers\LeaseObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Lease::observe(LeaseObserver::class);
    }
}
