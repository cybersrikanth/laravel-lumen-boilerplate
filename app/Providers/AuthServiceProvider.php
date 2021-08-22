<?php

namespace App\Providers;

use App\Models\User;
use App\Service\Core\JwtService;
use App\Service\Users\SessionService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot(
        JwtService $jwt_service,
        SessionService $session_service
    ) {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

        $this->app['auth']->viaRequest('api', function ($request) use ($session_service, $jwt_service) {
            
            $token = (string)Str::of($request->header('Authorization'))->after('Bearer ');
            if (!$token) return null;
            
            $claims = $jwt_service->validate($token);
            return $session_service->getUser($claims['session_id']);
        });
    }
}
