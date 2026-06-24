<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Core\Helper;
use Core\Repository\CountersOlmRepository;
use Core\Repository\CountersOlmRepositoryImpl;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        $this->app->singleton('base.helper', function() {
            return new Helper;
        });

        $this->app->singleton(
            CountersOlmRepository::class, CountersOlmRepositoryImpl::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
