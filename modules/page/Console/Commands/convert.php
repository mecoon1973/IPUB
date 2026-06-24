<?php

namespace Modules\Page\Console\Commands;

use Illuminate\Console\Command;
use Modules\System\Service\ChucnangService;
use Modules\System\Service\DoituongSNVService;
use Modules\System\Service\DonviLCService;
use Modules\System\Service\NhomCanboService;
use Modules\System\Service\NhomService;
use Modules\System\Service\QuyenService;
use Modules\Topic\Service\PhieuDkDetaiService;
use Modules\User\Service\UserService;

class Convert extends Command {
    protected $signature = 'convert {name} {--skip=0} {--limit=0} {--log=0} {--extra=null}';
    protected $description = 'Convert data';


    public function __construct() {
        parent::__construct();
    }

    public function handle() {
        $name = $this->argument('name');

        switch($name) {
            case 'convertDataCanBoQuyen':
                /** @var UserService $userService */
                $userService = app(UserService::class);
                $userService->convertDataCanBoQuyen();
                break;
            case 'convertDataNhomCanboToNewSystem':
                /** @var NhomCanboService $nhomCanboService */
                $nhomCanboService = app(NhomCanboService::class);
                $nhomCanboService->convertDataNhomCanboToNewSystem();
                break;
            case 'convertDataChucnang':
                /** @var ChucnangService $chucnangService */
                $chucnangService = app(ChucnangService::class);
                $chucnangService->convertDataChucnang();
                break;
            case 'convertDataListBTV':
                /** @var PhieuDkDetaiService $phieuDkDetaiService */
                $phieuDkDetaiService = app(PhieuDkDetaiService::class);
                $phieuDkDetaiService->convertDataListBTV();
                break;
            case 'convertDataCTDonviLcLoaiXbpLc':
                /** @var DonviLCService $donviLCService */
                $donviLCService = app(DonviLCService::class);
                $donviLCService->convertDataCTDonviLcLoaiXbpLc();
                break;
            case 'convertDataFunctionQuyen':
                /** @var QuyenService $quyenService */
                $quyenService = app(QuyenService::class);
                $quyenService->convertDataFunctionQuyen();
                break;
            case 'convertDataNhomQuyen':
                /** @var NhomService $nhomService */
                $nhomService = app(NhomService::class);
                $nhomService->convertDataNhomQuyen();
                break;
            case 'convertDataDoituongSNV':
                /** @var DoituongSNVService $doituongSNVService */
                $doituongSNVService = app(DoituongSNVService::class);
                $doituongSNVService->convertDataDoituongSNV();
                break;
            default:
                dump("Viết sai tên function rồi. Kiểm tra lại đi");
                die();
        }
    }
}
