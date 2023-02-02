<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/admin/dashboard';
    // public const ADMIN = '/admin/dashboard';
    // public const SUBADMIN = '/admin/dashboard';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            // Route::prefix('api')
            //     ->middleware('api')
            //     ->group(base_path('routes/api.php'));
            Route::prefix('api')
                ->middleware('api')
                ->as('api.')
                ->namespace($this->namespace."\\API")
                ->group(base_path('routes/api.php'));

            Route::prefix('api/V1')
                ->middleware('api')
                ->as('api.')
                ->namespace($this->namespace."\\API")
                ->group(base_path('routes/api_V1.php'));     
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60);
        });
    }
}
