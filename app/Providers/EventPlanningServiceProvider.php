<?php

namespace App\Providers;

use App\Repositories\EventPlanning\ActivityRepository;
use App\Repositories\EventPlanning\Contracts\ActivityRepositoryInterface;
use App\Repositories\EventPlanning\Contracts\RundownRepositoryInterface;
use App\Repositories\EventPlanning\RundownRepository;
use Illuminate\Support\ServiceProvider;

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
