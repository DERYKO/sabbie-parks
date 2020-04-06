<?php

namespace App\Providers;

use App\Observers\ParkingSpotObserver;
use App\ParkingSpot;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
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
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        ParkingSpot::observe(ParkingSpotObserver::class);
        Passport::routes();

    }
}
