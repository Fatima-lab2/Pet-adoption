<?php

namespace App\Providers;
use Illuminate\Support\Facades\Blade;
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


public function boot()
{
    Blade::if('role', function ($role) {
        return auth()->check() && auth()->user()->hasRole($role);
    });
}
    /**
     * Bootstrap any application services.
     */
}
