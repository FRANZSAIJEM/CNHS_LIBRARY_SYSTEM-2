<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class CarbonServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
        public function boot()
        {
            // Set the Carbon configuration to use a shorter format
            Carbon::setLocale('en'); // You can change 'en' to your desired locale.

            // Customize the short format settings
            Carbon::setShortRelativeDiffOptions([
                'now' => 'now',
                'second' => 's',
                'minute' => 'm',
                'hour' => 'h',
                'day' => 'd',
                'week' => 'w',
                'month' => 'mo',
                'year' => 'y',
            ]);
        }

}
