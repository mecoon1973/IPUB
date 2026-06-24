<?php
namespace Modules\System\Service\Impl;

use Core\Object\Paginate;
use Illuminate\Support\Facades\Auth;

use Modules\System\Service\LoaiXbpLcService;
use Modules\System\Repository\LoaiXbpLcRepository;

use Core\Service\BaseService;
use Exception;
use Modules\System\Model\DM_LOAI_XBP_LC;
use Modules\System\Object\FilterLoaiXbpLc;

class LoaiXbpLcServiceImpl extends BaseService implements LoaiXbpLcService
{
    /** @var LoaiXbpLcRepository */
    protected $baseRepo;

    public function __construct(LoaiXbpLcRepository $baseRepo) {
        parent::__construct($baseRepo);
    }

    public function getPaginate(FilterLoaiXbpLc $filter, string $page): array {
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
    public function getList(FilterLoaiXbpLc $filter) {
        $result = $this->baseRepo->findAllWithFilter($filter);
        return $result;
    }

    public function store(array $data): DM_LOAI_XBP_LC {
        if(data_get($data, "_id", 0) != 0) {
            /** @var DM_LOAI_XBP_LC $loaiXbpLc */
            $loaiXbpLc = $this->baseRepo->get($data["_id"]);
            if($loaiXbpLc) {
                $loaiXbpLc->update($data);
                return $loaiXbpLc;
            }
        }

        /** @var DM_LOAI_XBP_LC $loaiXbpLc */
        $loaiXbpLc = $this->baseRepo->create($data);
        if(!$loaiXbpLc){
            throw new Exception("Có lỗi xảy ra, vui lòng thử lại");
        }
        return $loaiXbpLc;
    }

    public function delete(int $id): bool {
        $loaiXbpLc = $this->baseRepo->get($id);
        if(!$loaiXbpLc){
            throw new Exception("Lớp không tồn tại");
        }
        $loaiXbpLc->IsDeleted = true;
        return $loaiXbpLc->save();
    }
}
