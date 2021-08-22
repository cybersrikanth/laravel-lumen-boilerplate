<?php

namespace App\Providers;

use App\Repository\Eloquent\Users\UserRepository;
use App\Repository\Interfaces\Users\UserRepositoryInterface;
use App\Service\Core\JwtService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->initAlias();
        $this->app->register(EloquentRepositoryServiceProvider::class);

        $this->app->singleton(JwtService::class, function($app){
            return new JwtService();
        });
        $this->app->singleton('DocGen', 'ApiDoc\DocGenerator');
    }

    private function initAlias()
    {
        $aliases = config('app.aliases');
        foreach ($aliases as $key => $class) {
            class_alias($class, $key);
        }
    }
}
