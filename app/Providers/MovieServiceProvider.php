<?php

namespace App\Providers;

use App\Services\MovieService;
use Illuminate\Support\ServiceProvider;

/**
 * Class MovieServiceProvider
 *
 * @package App\Providers
 * @author Tengyu Wang
 */
class MovieServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(MovieService::class, function ($app) {
            return new MovieService();
        });
    }
}
