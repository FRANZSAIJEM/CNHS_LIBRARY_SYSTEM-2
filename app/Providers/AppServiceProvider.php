<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
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
    public function boot()
    {
        Carbon::macro('shortRelativeDiff', function () {
            $diff = $this->diffForHumans(['short' => true]);
            return preg_replace('/\s+ago/', '', $diff);
        });



    }

}
