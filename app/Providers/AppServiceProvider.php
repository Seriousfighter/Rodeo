<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

//
use App\Services\Interfaces\ClienteInterface;
use App\Services\ClienteService;
use App\Services\Interfaces\RodeoInterface;
use App\Services\RodeoService;
use App\Services\Interfaces\AnimalInterface;
use App\Services\AnimalService;
//

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ClienteInterface::class, ClienteService::class);
        $this->app->bind(RodeoInterface::class, RodeoService::class);
        $this->app->bind(AnimalInterface::class, AnimalService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
