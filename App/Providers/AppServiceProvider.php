<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ClientService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(ClientService::class, function ($app) {
            return new ClientService();
        });
    }
}
