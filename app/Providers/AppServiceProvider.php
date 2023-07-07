<?php

namespace App\Providers;

use Yajra\DataTables\DataTables;
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
        // DataTables::buttons(['excel']);
    }
}
