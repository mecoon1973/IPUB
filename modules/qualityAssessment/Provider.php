<?php
namespace Modules\QualityAssessment;

use Illuminate\Support\ServiceProvider;
use Modules\QualityAssessment\Repository\DSDocRaSoatRepository;
use Modules\QualityAssessment\Repository\Impl\DSDocRaSoatRepositoryImpl;
use Modules\QualityAssessment\Service\DSDocRaSoatService;
use Modules\QualityAssessment\Service\Impl\DSDocRaSoatServiceImpl;

class Provider extends ServiceProvider {
    /**
     * Bootstrap the application Service.
     *
     * @return void
     */
    public function boot() {
        $this->loadRoutesFrom(__DIR__.'/routes.php');


        $this->publishes([
            __DIR__.'/View/assets/js' => resource_path('js/qualityAssessment'),
                __DIR__.'/View/assets/css' => public_path('css/qualityAssessment'),
            ], 'assets');


        $this->loadViewsFrom(__DIR__.'/View', 'qualityAssessment');

        // singleton service

        $this->app->singleton(DSDocRaSoatService::class, DSDocRaSoatServiceImpl::class);
        $this->app->singleton(DSDocRaSoatRepository::class, DSDocRaSoatRepositoryImpl::class);

        //

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
