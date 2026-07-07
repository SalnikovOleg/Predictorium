<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(\App\Repositories\Web\SimplePageRepository::class, function ($app) {
            return new \App\Repositories\Web\SimplePageRepository(new \App\Models\SimplePage());
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
    }
}
