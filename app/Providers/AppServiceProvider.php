<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    protected function mapApiRoutes()
{
    Route::prefix('api')
         ->middleware('api')
         ->namespace($this->namespace) // Ensure this matches your controller namespace
         ->group(base_path('routes/api.php'));
}
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
