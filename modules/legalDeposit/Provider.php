<?php
namespace Modules\LegalDeposit;

use Illuminate\Support\ServiceProvider;
use Modules\legalDeposit\Repository\Impl\PhieuNhapLCRepositoryImpl;
use Modules\LegalDeposit\Repository\Impl\ToKhaiLuuChuyenRepositoryImpl;
use Modules\legalDeposit\Repository\PhieuNhapLCRepository;
use Modules\LegalDeposit\Repository\ToKhaiLuuChuyenRepository;
use Modules\legalDeposit\Service\Impl\PhieuNhapLCServiceImpl;
use Modules\LegalDeposit\Service\Impl\ToKhaiLuuChuyenServiceImpl;
use Modules\legalDeposit\Service\PhieuNhapLCService;
use Modules\LegalDeposit\Service\ToKhaiLuuChuyenService;

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
            __DIR__.'/View/assets/js' => resource_path('js/legalDeposit'),
                __DIR__.'/View/assets/css' => public_path('css/legalDeposit'),
            ], 'assets');


        $this->loadViewsFrom(__DIR__.'/View', 'legalDeposit');

        $this->app->singleton(PhieuNhapLCService::class, PhieuNhapLCServiceImpl::class);
        $this->app->singleton(PhieuNhapLCRepository::class, PhieuNhapLCRepositoryImpl::class);
        $this->app->singleton(ToKhaiLuuChuyenService::class, ToKhaiLuuChuyenServiceImpl::class);
        $this->app->singleton(ToKhaiLuuChuyenRepository::class, ToKhaiLuuChuyenRepositoryImpl::class);
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

        //
    }

}
