<?php

namespace App\Providers;

use App\Repositories\Location\Contracts\PlaceRepositoryInterface;
use App\Repositories\Location\PlaceRepository;
use App\Services\Location\PlaceService;
use Illuminate\Support\ServiceProvider;

class LocationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Bind the PlaceRepository interface to its concrete implementation
        $this->app->bind(PlaceRepositoryInterface::class, PlaceRepository::class);

        // Bind the PlaceService
        $this->app->singleton(PlaceService::class, function ($app) {
            return new PlaceService($app->make(PlaceRepositoryInterface::class));
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Publish migrations if needed
        // $this->publishes([
        //     __DIR__.'/../../database/migrations' => database_path('migrations'),
        // ], 'teman-jalan-migrations');
    }
}
