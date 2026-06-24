<?php
namespace Modules\User;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Modules\User\Repository\DonviRepository;
use Modules\User\Repository\Impl\DonviRepositoryImpl;
use Modules\User\Repository\Impl\UserRepositoryImpl;
use Modules\User\Repository\UserRepository;
use Modules\User\Service\DonviService;
use Modules\User\Service\Impl\DonviServiceImpl;
use Modules\User\Service\Impl\UserServiceImpl;
use Modules\User\Service\UserService;

class Provider extends ServiceProvider {
    /**
     * Bootstrap the application Service.
     *
     * @return void
     */
    public function boot() {
        $this->loadRoutesFrom(__DIR__.'/routes.php');

        // Blade::componentNamespace('Modules\\User\\View\\Components', 'user');

        $this->publishes([
            __DIR__.'/View/assets/js' => resource_path('js/user'),
            __DIR__.'/View/assets/css' => public_path('css/user'),
        ], 'assets');


        $this->loadViewsFrom(__DIR__.'/View', 'user');

        // Gate::define('update-user', 'Modules\User\Policy\UserPolicy@isPermission');

    }

    /**
     * Register the application Service.
     *
     * @return void
     */
    public function register() {

        // $this->mergeConfigFrom(
        //     __DIR__.'/config.php', 'user'
        // );


        $this->app->singleton(UserService::class, UserServiceImpl::class);
        $this->app->singleton(UserRepository::class, UserRepositoryImpl::class);
    }

}
