<?php

namespace App\Providers;

use App\Repository\Eloquent\Users\SessionRepository;
use App\Repository\Eloquent\Users\UserRepository;
use App\Repository\Interfaces\Users\SessionRepositoryInterface;
use App\Repository\Interfaces\Users\UserRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class EloquentRepositoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(SessionRepositoryInterface::class, SessionRepository::class);
    }
}
