<?php

namespace Modules\System\Traits;

use Illuminate\Support\Facades\Auth;
use Modules\System\Object\FilterBosach;
use Modules\System\Object\FilterDoituong;
use Modules\System\Object\FilterDonvi;
use Modules\System\Object\FilterDonviLC;
use Modules\System\Object\FilterLoaiXBP;
use Modules\System\Object\FilterLoaiXbpLc;
use Modules\System\Object\FilterLop;
use Modules\System\Object\FilterMangsach;
use Modules\System\Object\FilterMonhoc;
use Modules\System\Object\FilterNhom;
use Modules\System\Object\FilterQuyen;
use Modules\System\Object\FilterTrangThai;
use Modules\System\Object\FilterTusach;
use Modules\System\Service\BosachService;
use Modules\System\Service\DoituongService;
use Modules\System\Service\DonviLCService;
use Modules\System\Service\DonviService;
use Modules\System\Service\LoaiSnvService;
use Modules\System\Service\LoaiXbpLcService;
use Modules\System\Service\LoaiXBPService;
use Modules\System\Service\LopService;
use Modules\System\Service\MangsachService;
use Modules\System\Service\MonhocService;
use Modules\System\Service\NhomService;
use Modules\System\Service\QuyenService;
use Modules\System\Service\TrangThaiService;
use Modules\System\Service\TusachService;

trait TraitsGetData
{
    public function getDataView(array $getData): array {
        $data = [];
        if(in_array("listDonvi", $getData)){
            /** @var DonviService $donviService */
            $donviService = app(DonviService::class);
            $data['listDonvi'] = $donviService->getAllDonvi(new FilterDonvi([
                "IsDeleted" => false,
            ]));
        }

        if(in_array("listMangsach", $getData)){
            /** @var MangsachService $mangsachService */
            $mangsachService = app(MangsachService::class);
            $data['listMangsach'] = $mangsachService->getList(new FilterMangsach([
                "IsDeleted" => false,
            ]));
        }

        if(in_array("listDoituong", $getData)){
            /** @var DoituongService $doituongService */
            $doituongService = app(DoituongService::class);
            $data['listDoituong'] = $doituongService->getList(new FilterDoituong([
                "IsDeleted" => false,
            ]));
        }

        if(in_array("listLop", $getData)){
            /** @var LopService $lopService */
            $lopService = app(LopService::class);
            $data['listLop'] = $lopService->getListLop(new FilterLop([
                "IsDeleted" => false,
            ]));
        }

        if(in_array("listMonhoc", $getData)){
            /** @var MonhocService $monhocService */
            $monhocService = app(MonhocService::class);
            $data['listMonhoc'] = $monhocService->getListMonhoc(new FilterMonhoc([
                "IsDeleted" => false,
            ]));
        }

        if(in_array("listBosach", $getData)){
            /** @var BosachService $bosachService */
            $bosachService = app(BosachService::class);
            $data['listBosach'] = $bosachService->getList(new FilterBosach([
                "IsDeleted" => false,
            ]));
        }

        if(in_array("listTusach", $getData)){
            /** @var TusachService $tusachService */
            $tusachService = app(TusachService::class);
            $data['listTusach'] = $tusachService->getList(new FilterTusach([
                "IsDeleted" => false,
            ]));
        }

        if(in_array("mapTrangThai", $getData)){
            /** @var TrangThaiService $trangThaiService */
            $trangThaiService = app(TrangThaiService::class);
            $listTrangThai = $trangThaiService->getList(new FilterTrangThai());
            $mapTrangThai = [];
            foreach($listTrangThai as $trangThai){
                $mapTrangThai[$trangThai->MaTrangThai] = $trangThai->TenTrangThai;
            }
            $data['mapTrangThai'] = $mapTrangThai;
        }

        if(in_array("listLoaiXbpLc", $getData)){
            /** @var LoaiXbpLcService $loaiXbpLcService */
            $loaiXbpLcService = app(LoaiXbpLcService::class);
            $listLoaiXbpLc = $loaiXbpLcService->getList(new FilterLoaiXbpLc([
                "IsDeleted" => false,
            ]));
            $data['listLoaiXbpLc'] = $listLoaiXbpLc;
        }

        if(in_array("listDonviLC", $getData)){
            /** @var DonviLCService $donviLCService */
            $donviLCService = app(DonviLCService::class);
            $listDonviLC = $donviLCService->getList(new FilterDonviLC([
                "IsDeleted" => false,
            ]));
            $data['listDonviLC'] = $listDonviLC;
        }

        if(in_array("listNhom", $getData)){
            /** @var NhomService $nhomService */
            $nhomService = app(NhomService::class);
            $data['listNhom'] = $nhomService->getAllNhom(new FilterNhom([
                "IsDeleted" => false,
            ]));
        }

        if(in_array("listQuyen", $getData)){
            /** @var QuyenService $quyenService */
            $quyenService = app(QuyenService::class);
            $data['listQuyen'] = $quyenService->getAllQuyen(new FilterQuyen([
                "IsDeleted" => false,
            ]));
        }
        if(in_array("listLoaiXBP", $getData)){
            /** @var LoaiXBPService $loaiXBPService */
            $loaiXBPService = app(LoaiXBPService::class);
            $data['listLoaiXBP'] = $loaiXBPService->getList(new FilterLoaiXBP([
                "IsDeleted" => false,
            ]));
        }
        if(in_array("listLoaiSNV", $getData)){
            /** @var LoaiSnvService $loaiSnvService */
            $loaiSnvService = app(LoaiSnvService::class);
            $data['listLoaiSNV'] = $loaiSnvService->findAll([
                "IsDeleted" => false,
            ]);
        }

        return $data;
    }
}
