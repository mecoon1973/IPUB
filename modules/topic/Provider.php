<?php
namespace Modules\Topic;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Modules\Topic\Repository\CT_Detai_CongDoanRepository;
use Modules\Topic\Repository\CT_PhieuDkDetai_BtvRepository;
use Modules\Topic\Repository\Impl\CT_Detai_CongDoanRepositoryImpl;
use Modules\Topic\Repository\Impl\CT_PhieuDkDetai_BtvRepositoryImpl;
use Modules\Topic\Service\PhieuDkDetaiService;
use Modules\Topic\Service\Impl\PhieuDkDetaiServiceImpl;
use Modules\Topic\Repository\PhieuDkDetaiRepository;
use Modules\Topic\Repository\Impl\PhieuDkDetaiRepositoryImpl;
use Modules\Topic\Repository\Impl\QDInRepositoryImpl;
use Modules\Topic\Repository\QDInRepository;
use Modules\Topic\Service\CT_Detai_CongDoanService;
use Modules\Topic\Service\CT_PhieuDkDetai_BtvService;
use Modules\Topic\Service\Impl\CT_Detai_CongDoanServiceImpl;
use Modules\Topic\Service\Impl\CT_PhieuDkDetai_BtvServiceImpl;
use Modules\Topic\Service\Impl\QDInServiceImpl;
use Modules\Topic\Service\QDInService;

class Provider extends ServiceProvider {
    /**
     * Bootstrap the application Service.
     *
     * @return void
     */
    public function boot() {
        $this->loadRoutesFrom(__DIR__.'/routes.php');

        $this->publishes([
            __DIR__.'/View/assets/js' => resource_path('js/user'),
            __DIR__.'/View/assets/css' => public_path('css/user'),
        ], 'assets');


        $this->loadViewsFrom(__DIR__.'/View', 'topic');

        $this->app->bind(PhieuDkDetaiService::class, PhieuDkDetaiServiceImpl::class);
        $this->app->bind(PhieuDkDetaiRepository::class, PhieuDkDetaiRepositoryImpl::class);

        $this->app->bind(CT_PhieuDkDetai_BtvService::class, CT_PhieuDkDetai_BtvServiceImpl::class);
        $this->app->bind(CT_PhieuDkDetai_BtvRepository::class, CT_PhieuDkDetai_BtvRepositoryImpl::class);
        $this->app->bind(CT_Detai_CongDoanService::class, CT_Detai_CongDoanServiceImpl::class);
        $this->app->bind(CT_Detai_CongDoanRepository::class, CT_Detai_CongDoanRepositoryImpl::class);
        $this->app->bind(QDInService::class, QDInServiceImpl::class);
        $this->app->bind(QDInRepository::class, QDInRepositoryImpl::class);
        // Gate::define('update-user', 'Modules\User\Policy\UserPolicy@isPermission');

    }

    /**
     * Register the application Service.
     *
     * @return void
     */
    public function register() {
        $this->mergeConfigFrom(__DIR__ . "/config.php", "topic");
    }

}
