<?php
namespace Modules\Page;

use Modules\Page\Console\Commands\Convert;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;


class Provider extends ServiceProvider {
    /**
     * Bootstrap the application Service.
     *
     * @return void
     */
    public function boot() {
        $this->loadRoutesFrom(__DIR__.'/routes.php');

        if ($this->app->runningInConsole()) {
            $this->commands([
                Convert::class,
            ]);
        }

        // Blade::componentNamespace('Modules\\User\\View\\Components', 'user');

        $this->publishes([
            __DIR__.'/View/assets/js' => resource_path('js/user'),
            __DIR__.'/View/assets/css' => public_path('css/user'),
        ], 'assets');


        $this->loadViewsFrom(__DIR__.'/View', 'page');

        // Gate::define('update-user', 'Modules\User\Policy\UserPolicy@isPermission');

    }

    /**
     * Register the application Service.
     *
     * @return void
     */
    public function register() {

        $this->mergeConfigFrom(
            __DIR__.'/config.php', 'page'
        );

    }

}
