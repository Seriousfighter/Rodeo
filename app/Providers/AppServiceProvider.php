<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

//
use App\Services\Interfaces\ClienteInterface;
use App\Services\ClienteService;
//

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ClienteInterface::class, ClienteService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
