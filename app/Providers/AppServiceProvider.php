<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Format tanggal berbahasa Indonesia ("21 Juli 2026")
        Carbon::setLocale('id');

        // Pagination memakai view bertema Mivent (resources/views/vendor/pagination/mivent.blade.php)
        Paginator::defaultView('vendor.pagination.mivent');

        // Di production paksa URL https
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
