<?php
namespace Modules\Topic\Service\Impl;

use Illuminate\Support\Facades\Auth;

use Modules\Topic\Service\PhieuDkDetaiService;
use Modules\Topic\Repository\PhieuDkDetaiRepository;
use Core\Repository\CountersOlmRepository as CounterRepository;

use Core\Service\BaseService;
use Core\Object\Paginate;
use Core\Service\BaseConvertTool;
use Modules\Topic\Object\FilterPhieuDkDetai;
use Modules\Topic\Model\PHIEU_DK_DETAI;

use Exception;
use Modules\Topic\Service\CT_PhieuDkDetai_BtvService;

class PhieuDkDetaiServiceImpl extends BaseService implements PhieuDkDetaiService
{
    use BaseConvertTool;
    /** @var PhieuDkDetaiRepository */
    protected $baseRepo;

    public function __construct(PhieuDkDetaiRepository $baseRepo) {
        parent::__construct($baseRepo);
    }

    public function getPaginate(FilterPhieuDkDetai $filter, string $page = 'page-1'): array {
        $conditions = $filter->buildConditions();
        $paginate = new Paginate([
            "conditions" => $conditions,
            "limit" => 10,
            "page" => $page,
        ]);
        $result = $this->pagination($paginate);
        return [
            "listResult" => $result->list,
            "pagiInfo" => $result->pagi_info
        ];
    }

    public function getList(FilterPhieuDkDetai $filter) {
        return $this->baseRepo->findAllWithFilter($filter);
    }

    public function store(array $data): PHIEU_DK_DETAI {
        if(data_get($data, "id", 0) != 0) {
            /** @var PHIEU_DK_DETAI $phieuDkDetai */
            $phieuDkDetai = $this->baseRepo->get($data["id"]);
            if($phieuDkDetai) {
                $phieuDkDetai->update($data);
                return $phieuDkDetai;
            }
        }

        /** @var PHIEU_DK_DETAI $phieuDkDetai */
        $phieuDkDetai = $this->baseRepo->create($data);
        if(!$phieuDkDetai){
            throw new Exception("Có lỗi xảy ra, vui lòng thử lại");
        }
        return $phieuDkDetai;
    }

    public function delete(int $id): bool {
        $phieuDkDetai = $this->baseRepo->get($id);
        if(!$phieuDkDetai){
            throw new Exception("PhieuDkDetai không tồn tại");
        }
        $phieuDkDetai->IsDeleted = true;
        return $phieuDkDetai->save();
    }

    public function convertDataListBTV(){
        dump("convertDataListBTV: START");
        $this->baseRepo->update(
            ["BienTapVien" => ""],
            ["idListBTV" => []]
          );
        dump("convertDataListBTV: FINISH  BienTapVien = __");
        $this->baseConvert("convertDataListBTV", $this->baseRepo, [
            "BienTapVien" => ['$ne' => ""]
        ], function($phieuDkDetai) {
            $listBTV = [];
            if(isset($phieuDkDetai->BienTapVien) && $phieuDkDetai->BienTapVien != ""){
                /** @var CT_PhieuDkDetai_BtvService $ct_phieuDkDetai_bTVService */
                $ct_phieuDkDetai_bTVService = app(CT_PhieuDkDetai_BtvService::class);
                $listCTPhieuDkDetai = $ct_phieuDkDetai_bTVService->findAll([
                    "ID_PHIEU_DK_DETAI" => $phieuDkDetai->_id,
                    "IsDeleted" => false
                ]);
                foreach ($listCTPhieuDkDetai as $ctPhieuDkDetai) {
                    $listBTV[] = $ctPhieuDkDetai->ID_CANBO;
                }
            }
            $phieuDkDetai->idListBTV = $listBTV;
            $phieuDkDetai->save();
        });
        dump("convertDataListBTV: FINISH");
    }
}
