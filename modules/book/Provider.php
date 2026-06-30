<?php
namespace Modules\Book;

use Modules\Page\Console\Commands\Convert;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Modules\Book\Repository\Impl\SachRepositoryImpl;
use Modules\Book\Repository\SachRepository;
use Modules\Book\Service\Impl\SachServiceImpl;
use Modules\Book\Service\SachService;

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


        $this->loadViewsFrom(__DIR__.'/View', 'book');

        // Gate::define('update-user', 'Modules\User\Policy\UserPolicy@isPermission');

    }

    /**
     * Register the application Service.
     *
     * @return void
     */
    public function register() {

        $this->app->singleton(SachService::class, SachServiceImpl::class);
        $this->app->singleton(SachRepository::class, SachRepositoryImpl::class);

        $this->mergeConfigFrom(
            __DIR__.'/config.php', 'book'
        );

    }

}
