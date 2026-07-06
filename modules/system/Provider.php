<?php
namespace Modules\System;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Modules\System\Repository\BienMoiTruongRepository;
use Modules\System\Repository\ChucnangRepository;
use Modules\System\Repository\DonviRepository;
use Modules\System\Repository\HDXBRepository;
use Modules\System\Repository\Impl\ChucnangRepositoryImpl;
use Modules\System\Repository\Impl\DonviRepositoryImpl;
use Modules\System\Repository\Impl\HDXBRepositoryImpl;
use Modules\System\Repository\Impl\LopRepositoryImpl;
use Modules\System\Repository\Impl\MangsachRepositoryImpl;
use Modules\System\Repository\Impl\MonhocRepositoryImpl;
use Modules\System\Repository\Impl\NhomCanboRepositoryImpl;
use Modules\System\Repository\Impl\QuyenRepositoryImpl;
use Modules\System\Repository\NhomRepository;
use Modules\System\Repository\Impl\NhomRepositoryImpl;
use Modules\System\Repository\Impl\PhanheRepositoryImpl;
use Modules\System\Repository\LopRepository;
use Modules\System\Repository\MangsachRepository;
use Modules\System\Repository\MonhocRepository;
use Modules\System\Repository\NhomCanboRepository;
use Modules\System\Repository\PhanheRepository;
use Modules\System\Repository\QuyenRepository;
use Modules\System\Service\ChucnangService;
use Modules\System\Service\DonviService;
use Modules\System\Service\HDXBService;
use Modules\System\Service\Impl\ChucnangServiceImpl;
use Modules\System\Service\Impl\DonviServiceImpl;
use Modules\System\Service\Impl\HDXBServiceImpl;
use Modules\System\Service\Impl\LopServiceImpl;
use Modules\System\Service\Impl\MangsachServiceImpl;
use Modules\System\Service\Impl\MonhocServiceImpl;
use Modules\System\Service\Impl\NhomCanboServiceImpl;
use Modules\System\Service\Impl\NhomServiceImpl;
use Modules\System\Service\Impl\PhanheServiceImpl;
use Modules\System\Service\Impl\QuyenServiceImpl;
use Modules\System\Service\MangsachService;
use Modules\System\Service\LopService;
use Modules\System\Service\MonhocService;
use Modules\System\Service\NhomCanboService;
use Modules\System\Service\NhomService;
use Modules\System\Service\PhanheService;
use Modules\System\Service\QuyenService;
use Modules\System\Service\BosachService;
use Modules\System\Service\Impl\BosachServiceImpl;
use Modules\System\Repository\BosachRepository;
use Modules\System\Repository\CanboQuyenRepository;
use Modules\System\Repository\ChucvuRepository;
use Modules\System\Repository\ChuyenmonRepository;
use Modules\System\Repository\Impl\BosachRepositoryImpl;
use Modules\System\Repository\DoituongRepository;
use Modules\System\Repository\DoituongSNVRepository;
use Modules\System\Repository\Impl\DoituongSNVRepositoryImpl;
use Modules\System\Repository\Impl\ChucvuRepositoryImpl;
use Modules\System\Repository\Impl\ChuyenmonRepositoryImpl;
use Modules\System\Repository\Impl\DoituongRepositoryImpl;
use Modules\System\Repository\Impl\LoaiXBPRepositoryImpl;
use Modules\System\Repository\Impl\MangsachCXBRepositoryImpl;
use Modules\System\Repository\Impl\NgoaiNguRepositoryImpl;
use Modules\System\Repository\Impl\TusachRepositoryImpl;
use Modules\System\Repository\CongviecchebaninRepository;
use Modules\System\Repository\CongviecthietkeRepository;
use Modules\System\Repository\CT_DonviLC_LoaiXBPLCRepository;
use Modules\System\Repository\CT_DvLoaiSNVRepository;
use Modules\System\Repository\DonviLCRepository;
use Modules\System\Repository\FunctionQuyenRepository;
use Modules\System\Repository\Impl\BienMoiTruongRepositoryImpl;
use Modules\System\Repository\Impl\CanboQuyenRepositoryImpl;
use Modules\System\Repository\Impl\CongviecchebaninRepositoryImpl;
use Modules\System\Repository\Impl\CongviecthietkeRepositoryImpl;
use Modules\System\Repository\Impl\CT_DonviLC_LoaiXBPLCRepositoryImpl;
use Modules\System\Repository\Impl\CT_DvLoaiSNVRepositoryImpl;
use Modules\System\Repository\Impl\DonviLCRepositoryImpl;
use Modules\System\Repository\Impl\FunctionQuyenRepositoryImpl;
use Modules\System\Repository\Impl\LoaiSnvRepositoryImpl;
use Modules\System\Repository\Impl\LoaiXbpLcRepositoryImpl;
use Modules\System\Repository\Impl\NhomQuyenRepositoryImpl;
use Modules\System\Repository\Impl\TemplateExcelRepositoryImpl;
use Modules\System\Repository\Impl\SystemLogRepositoryImpl;
use Modules\System\Repository\Impl\TrangThaiRepositoryImpl;
use Modules\System\Repository\LoaiSnvRepository;
use Modules\System\Repository\LoaiXbpLcRepository;
use Modules\System\Repository\LoaiXBPRepository;
use Modules\System\Repository\MangsachCXBRepository;
use Modules\System\Repository\NgoaiNguRepository;
use Modules\System\Repository\NhomQuyenRepository;
use Modules\System\Repository\TemplateExcelRepository;
use Modules\System\Repository\SystemLogRepository;
use Modules\System\Repository\TrangThaiRepository;
use Modules\System\Repository\TusachRepository;
use Modules\System\Service\BienMoiTruongService;
use Modules\System\Service\CanboQuyenService;
use Modules\System\Service\ChucvuService;
use Modules\System\Service\ChuyenmonService;
use Modules\System\Service\CongviecchebaninService;
use Modules\System\Service\CongviecthietkeService;
use Modules\System\Service\CT_DonviLC_LoaiXBPLCService;
use Modules\System\Service\CT_DvLoaiSNVService;
use Modules\System\Service\DoituongService;
use Modules\System\Service\DoituongSNVService;
use Modules\System\Service\DonviLCService;
use Modules\System\Service\FunctionQuyenService;
use Modules\System\Service\Impl\BienMoiTruongServiceImpl;
use Modules\System\Service\Impl\CanboQuyenServiceImpl;
use Modules\System\Service\Impl\ChucvuServiceImpl;
use Modules\System\Service\Impl\ChuyenmonServiceImpl;
use Modules\System\Service\Impl\CongviecchebaninServiceImpl;
use Modules\System\Service\Impl\DoituongServiceImpl;
use Modules\System\Service\Impl\CongviecthietkeServiceImpl;
use Modules\System\Service\Impl\CT_DonviLC_LoaiXBPLCServiceImpl;
use Modules\System\Service\Impl\CT_DvLoaiSNVServiceImpl;
use Modules\System\Service\Impl\DoituongSNVServiceImpl;
use Modules\System\Service\Impl\DonviLCServiceImpl;
use Modules\System\Service\Impl\FunctionQuyenServiceImpl;
use Modules\System\Service\Impl\LoaiSnvServiceImpl;
use Modules\System\Service\Impl\LoaiXbpLcServiceImpl;
use Modules\System\Service\Impl\LoaiXBPServiceImpl;
use Modules\System\Service\Impl\MangsachCXBServiceImpl;
use Modules\System\Service\Impl\NgoaiNguServiceImpl;
use Modules\System\Service\Impl\NhomQuyenServiceImpl;
use Modules\System\Service\Impl\TemplateExcelServiceImpl;
use Modules\System\Service\Impl\SystemLogServiceImpl;
use Modules\System\Service\Impl\TrangThaiServiceImpl;
use Modules\System\Service\Impl\TusachServiceImpl;
use Modules\System\Service\LoaiSnvService;
use Modules\System\Service\LoaiXbpLcService;
use Modules\System\Service\LoaiXBPService;
use Modules\System\Service\MangsachCXBService;
use Modules\System\Service\NgoaiNguService;
use Modules\System\Service\NhomQuyenService;
use Modules\System\Service\TemplateExcelService;
use Modules\System\Service\SystemLogService;
use Modules\System\Service\TrangThaiService;
use Modules\System\Service\TusachService;


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


        $this->loadViewsFrom(__DIR__.'/View', 'system');

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

        $this->app->singleton(DonviService::class, DonviServiceImpl::class);
        $this->app->singleton(DonviRepository::class, DonviRepositoryImpl::class);
        $this->app->singleton(HDXBService::class, HDXBServiceImpl::class);
        $this->app->singleton(HDXBRepository::class, HDXBRepositoryImpl::class);
        $this->app->singleton(QuyenService::class, QuyenServiceImpl::class);
        $this->app->singleton(QuyenRepository::class, QuyenRepositoryImpl::class);
        $this->app->singleton(NhomService::class, NhomServiceImpl::class);
        $this->app->singleton(NhomRepository::class, NhomRepositoryImpl::class);
        $this->app->singleton(ChucnangService::class, ChucnangServiceImpl::class);
        $this->app->singleton(ChucnangRepository::class, ChucnangRepositoryImpl::class);
        $this->app->singleton(PhanheService::class, PhanheServiceImpl::class);
        $this->app->singleton(PhanheRepository::class, PhanheRepositoryImpl::class);
        $this->app->singleton(LopService::class, LopServiceImpl::class);
        $this->app->singleton(LopRepository::class, LopRepositoryImpl::class);
        $this->app->singleton(MonhocService::class, MonhocServiceImpl::class);
        $this->app->singleton(MonhocRepository::class, MonhocRepositoryImpl::class);
        $this->app->singleton(MangsachService::class, MangsachServiceImpl::class);
        $this->app->singleton(MangsachRepository::class, MangsachRepositoryImpl::class);
        $this->app->singleton(BosachService::class, BosachServiceImpl::class);
        $this->app->singleton(BosachRepository::class, BosachRepositoryImpl::class);
        $this->app->singleton(DoituongService::class, DoituongServiceImpl::class);
        $this->app->singleton(DoituongRepository::class, DoituongRepositoryImpl::class);
        $this->app->singleton(TusachService::class, TusachServiceImpl::class);
        $this->app->singleton(TusachRepository::class, TusachRepositoryImpl::class);
        $this->app->singleton(ChuyenmonService::class, ChuyenmonServiceImpl::class);
        $this->app->singleton(ChuyenmonRepository::class, ChuyenmonRepositoryImpl::class);
        $this->app->singleton(ChucvuService::class, ChucvuServiceImpl::class);
        $this->app->singleton(ChucvuRepository::class, ChucvuRepositoryImpl::class);
        $this->app->singleton(LoaiXBPService::class, LoaiXBPServiceImpl::class);
        $this->app->singleton(LoaiXBPRepository::class, LoaiXBPRepositoryImpl::class);
        $this->app->singleton(MangsachCXBService::class, MangsachCXBServiceImpl::class);
        $this->app->singleton(MangsachCXBRepository::class, MangsachCXBRepositoryImpl::class);
        $this->app->singleton(NgoaiNguService::class, NgoaiNguServiceImpl::class);
        $this->app->singleton(NgoaiNguRepository::class, NgoaiNguRepositoryImpl::class);
        $this->app->singleton(CongviecchebaninService::class, CongviecchebaninServiceImpl::class);
        $this->app->singleton(CongviecchebaninRepository::class, CongviecchebaninRepositoryImpl::class);
        $this->app->singleton(CongviecthietkeService::class, CongviecthietkeServiceImpl::class);
        $this->app->singleton(CongviecthietkeRepository::class, CongviecthietkeRepositoryImpl::class);
        $this->app->singleton(SystemLogService::class, SystemLogServiceImpl::class);
        $this->app->singleton(SystemLogRepository::class, SystemLogRepositoryImpl::class);
        $this->app->singleton(BienMoiTruongService::class, BienMoiTruongServiceImpl::class);
        $this->app->singleton(BienMoiTruongRepository::class, BienMoiTruongRepositoryImpl::class);
        $this->app->singleton(TrangThaiService::class, TrangThaiServiceImpl::class);
        $this->app->singleton(TrangThaiRepository::class, TrangThaiRepositoryImpl::class);
        $this->app->singleton(DonviLCService::class, DonviLCServiceImpl::class);
        $this->app->singleton(DonviLCRepository::class, DonviLCRepositoryImpl::class);
        $this->app->singleton(LoaiXbpLcService::class, LoaiXbpLcServiceImpl::class);
        $this->app->singleton(LoaiXbpLcRepository::class, LoaiXbpLcRepositoryImpl::class);
        $this->app->singleton(FunctionQuyenService::class, FunctionQuyenServiceImpl::class);
        $this->app->singleton(FunctionQuyenRepository::class, FunctionQuyenRepositoryImpl::class);
        $this->app->singleton(DoituongSNVService::class, DoituongSNVServiceImpl::class);
        $this->app->singleton(DoituongSNVRepository::class, DoituongSNVRepositoryImpl::class);
        $this->app->singleton(LoaiSnvService::class, LoaiSnvServiceImpl::class);
        $this->app->singleton(LoaiSnvRepository::class, LoaiSnvRepositoryImpl::class);
        $this->app->singleton(TemplateExcelService::class, TemplateExcelServiceImpl::class);
        $this->app->singleton(TemplateExcelRepository::class, TemplateExcelRepositoryImpl::class);


        // các singleton chỉ dùng để chạy gộp dữ liệu
        $this->app->singleton(CT_DvLoaiSNVService::class, CT_DvLoaiSNVServiceImpl::class);
        $this->app->singleton(CT_DvLoaiSNVRepository::class, CT_DvLoaiSNVRepositoryImpl::class);
        $this->app->singleton(CanboQuyenService::class, CanboQuyenServiceImpl::class);
        $this->app->singleton(CanboQuyenRepository::class, CanboQuyenRepositoryImpl::class);
        $this->app->singleton(NhomQuyenService::class, NhomQuyenServiceImpl::class);
        $this->app->singleton(NhomQuyenRepository::class, NhomQuyenRepositoryImpl::class);
        $this->app->singleton(CT_DonviLC_LoaiXBPLCService::class, CT_DonviLC_LoaiXBPLCServiceImpl::class);
        $this->app->singleton(CT_DonviLC_LoaiXBPLCRepository::class, CT_DonviLC_LoaiXBPLCRepositoryImpl::class);
        $this->app->singleton(NhomCanboService::class, NhomCanboServiceImpl::class);
        $this->app->singleton(NhomCanboRepository::class, NhomCanboRepositoryImpl::class);

        //
    }

}
