<?php
namespace Modules\System\Service\Impl;

use Core\Object\Paginate;
use Illuminate\Support\Facades\Auth;

use Modules\System\Service\DonviLCService;
use Modules\System\Repository\DonviLCRepository;
use Core\Repository\CountersOlmRepository as CounterRepository;

use Core\Service\BaseService;
use Core\Service\BaseConvertTool;
use Exception;
use Modules\System\Model\DM_DONVILC;
use Modules\System\Object\FilterDonviLC;
use Modules\System\Service\CT_DonviLC_LoaiXBPLCService;

class DonviLCServiceImpl extends BaseService implements DonviLCService
{
    use BaseConvertTool;
    /** @var DonviLCRepository */
    protected $baseRepo;

    public function __construct(DonviLCRepository $baseRepo) {
        parent::__construct($baseRepo);
    }

    public function getPaginate(FilterDonviLC $filter, string $page = 'page-1'): array {
        $conditions = $filter->buildConditions();
        $paginate = new Paginate([
            "conditions" => $conditions,
            "limit" => 15,
            "page" => $page,
            "sorted" => $filter->buildSort()
        ]);
        $result = $this->pagination($paginate);
        return [
            "listResult" => $result->list,
            "pagiInfo" => $result->pagi_info
        ];
    }

    public function getList(FilterDonviLC $filter) {
        $result = $this->baseRepo->findAllWithFilter($filter);
        return $result;
    }

    public function store(array $data): DM_DONVILC {
        if(data_get($data, "id", 0) != 0) {
            /** @var DM_DONVILC $donviLC */
            $donviLC = $this->baseRepo->get($data["id"]);
            if($donviLC) {
                $donviLC->update($data);
                return $donviLC;
            }
        }

        /** @var DM_DONVILC $donviLC */
        $donviLC = $this->counterRepo->retry("ipub_dm_donvi_lc", function($id) use ($data) {
            $data["id"] = $id;
            return $this->baseRepo->create($data);
        });

        if(!$donviLC){
            throw new Exception("Có lỗi xảy ra, vui lòng thử lại");
        }
        return $donviLC;
    }

    public function delete(int $id): bool {
        $donviLC = $this->baseRepo->get($id);
        if(!$donviLC){
            throw new Exception("Đơn vị lưu chuyển không tồn tại");
        }
        $donviLC->IsDeleted = true;
        return $donviLC->save();
    }

    public function convertDataCTDonviLcLoaiXbpLc(): void {
        dump("start");
        $countSuccess = 0;
        $countError = 0;
        /** @var CT_DonviLC_LoaiXBPLCService $ctDonviLC_LoaiXBPLCService */
        $ctDonviLC_LoaiXBPLCService = app(CT_DonviLC_LoaiXBPLCService::class);
        $this->baseConvert("convertDataCTDonviLcLoaiXbpLc", $this->baseRepo, [], function($donviLC) use (&$countSuccess, &$countError, $ctDonviLC_LoaiXBPLCService) {
            $condition = [
                "ID_DONVI_LC" => $donviLC->_id,
                "IsDeleted" => false,
                "InUsed" => true,
            ];
            $loaiXbpLc = [];
            $ctDonviLcLoaiXbpLcs = $ctDonviLC_LoaiXBPLCService->findAll($condition);
            foreach ($ctDonviLcLoaiXbpLcs as $ctDonviLcLoaiXbpLC) {
                $loaiXbpLc[] = [
                    "ID_LOAI_XBP_LC" => $ctDonviLcLoaiXbpLC->ID_LOAI_XBP_LC,
                    "SoLuong" => $ctDonviLcLoaiXbpLC->SoLuong,
                ];
            }
            $donviLC->LoaiXbpLc = $loaiXbpLc;
            $result = $donviLC->save();
            if($result){
                $countSuccess++;
            }else{
                $countError++;
            }
        });

        dump("success: $countSuccess, error: $countError.");
    }

}
