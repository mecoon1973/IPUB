<?php
namespace Modules\System\Service\Impl;

use Core\Object\Paginate;
use Illuminate\Support\Facades\Auth;

use Modules\System\Service\DoituongSNVService;
use Modules\System\Repository\DoituongSNVRepository;
use Core\Repository\CountersOlmRepository as CounterRepository;
use Core\Service\BaseConvertTool;
use Core\Service\BaseService;
use Exception;
use Modules\System\Model\DM_DOITUONG_SNV;
use Modules\System\Object\FilterDoituongSNV;
use Modules\System\Service\CT_DvLoaiSNVService;

class DoituongSNVServiceImpl extends BaseService implements DoituongSNVService
{
    use BaseConvertTool;
    /** @var DoituongSNVRepository */
    protected $baseRepo;

    public function __construct(DoituongSNVRepository $baseRepo) {
        parent::__construct($baseRepo);
    }

    public function getPaginate(FilterDoituongSNV $filter, string $page = 'page-1'): array {
        $conditions = $filter->buildConditions();
        $paginate = new Paginate([
            "conditions" => $conditions,
            "limit" => 15,
            "page" => $page
        ]);

        $result = $this->pagination($paginate);
        return [
            "listResult" => $result->list,
            "pagiInfo" => $result->pagi_info
        ];
    }

    public function getList(FilterDoituongSNV $filter) {
        $result = $this->baseRepo->findAllWithFilter($filter);
        return $result;
    }

    public function store(array $data): DM_DOITUONG_SNV {
        if(data_get($data, "id", 0) != 0) {
            /** @var DM_DOITUONG_SNV $doituongSNV */
            $doituongSNV = $this->baseRepo->get($data["id"]);
            if($doituongSNV) {
                $doituongSNV->update($data);
                return $doituongSNV;
            }
        }
        /** @var DM_DOITUONG_SNV $doituongSNV */
        $doituongSNV = $this->baseRepo->create($data);
        if(!$doituongSNV){
            throw new Exception("Có lỗi xảy ra, vui lòng thử lại");
        }
        return $doituongSNV;
    }

    public function delete(int $id): bool {
        $doituongSNV = $this->baseRepo->get($id);
        if(!$doituongSNV){
            throw new Exception("Đối tượng nhận sách nghiệp vụ không tồn tại");
        }
        $doituongSNV->IsDeleted = true;
        return $doituongSNV->save();
    }

    public function convertDataDoituongSNV() : void {
        dump("convertDataDoituongSNV: START");
        $success = 0;
        $failed = 0;
        $this->baseConvert("convertDataDoituongSNV", $this->baseRepo, [], function($doituongSNV) use (&$success, &$failed) {
            /** @var CT_DvLoaiSNVService $ctDvLoaiSNVService */
            $ctDvLoaiSNVService = app(CT_DvLoaiSNVService::class);
            $listCT_DvLoaiSNV = $ctDvLoaiSNVService->findAll(["ID_DV_SNV" => $doituongSNV->_id, "IsDeleted" => false, "InUsed" => true]);
            $listLoaiSNV = [];
            foreach($listCT_DvLoaiSNV as $ctDvLoaiSNV){
                $listLoaiSNV[] = [
                    "id" => $ctDvLoaiSNV->ID_LOAI_SNV,
                    "SoLuong" => $ctDvLoaiSNV->SoLuong,
                ];
            }
            $doituongSNV->listLoaiSNV = $listLoaiSNV;
            $result = $doituongSNV->save();
            if($result){
                $success++;
            } else {
                $failed++;
            }
        });
        dump("convertDataDoituongSNV: FINISH");
        dump("Success: {$success}");
        dump("Failed: {$failed}");
        dump("Total: " . ($success + $failed));
    }

}
