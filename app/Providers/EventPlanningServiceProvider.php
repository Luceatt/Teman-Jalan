<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Features\EventPlanning\Repositories\Contracts\RundownRepositoryInterface;
use App\Features\EventPlanning\Repositories\RundownRepository;
use App\Features\EventPlanning\Repositories\Contracts\ActivityRepositoryInterface;
use App\Features\EventPlanning\Repositories\ActivityRepository;

class EventPlanningServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            RundownRepositoryInterface::class,
            RundownRepository::class
        );

        $this->app->bind(
            ActivityRepositoryInterface::class,
            ActivityRepository::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}