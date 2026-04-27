<?php

namespace App\Providers;

use App\Models\Lease;
use App\Observers\LeaseObserver;
use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Carbon::setLocale('ar');
        Lease::observe(LeaseObserver::class);
    }
}