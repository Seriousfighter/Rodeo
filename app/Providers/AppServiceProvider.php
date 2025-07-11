<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

//
use App\Services\Interfaces\ClientInterface;
use App\Services\ClientService;
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
        $this->app->bind(ClientInterface::class, ClientService::class);
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
