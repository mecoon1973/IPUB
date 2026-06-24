<?php
namespace Modules\System\Service\Impl;

use Core\Object\Paginate;
use Illuminate\Support\Facades\Auth;

use Modules\System\Service\CongviecthietkeService;
use Modules\System\Repository\CongviecthietkeRepository;
use Core\Repository\CountersOlmRepository as CounterRepository;

use Core\Service\BaseService;
use Exception;
use Modules\System\Model\DM_CONG_VIEC_THIET_KE;
use Modules\System\Object\FilterCongviecthietke;

class CongviecthietkeServiceImpl extends BaseService implements CongviecthietkeService
{
    /** @var CongviecthietkeRepository */
    protected $baseRepo;

    public function __construct(CongviecthietkeRepository $baseRepo) {
        parent::__construct($baseRepo);
    }
    public function getPaginate(FilterCongviecthietke $filter, string $page): array {
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
    public function getList(FilterCongviecthietke $filter) {
        if($filter->IsDeleted === null) {
            $filter->IsDeleted = false;
        }
        $result = $this->baseRepo->findAllWithFilter($filter);
        return $result;
    }

    public function store(array $data): DM_CONG_VIEC_THIET_KE {
        if(data_get($data, "id", 0) != 0) {
            /** @var DM_CONG_VIEC_THIET_KE $congviecthietke */
            $congviecthietke = $this->baseRepo->get($data["id"]);
            if($congviecthietke) {
                $congviecthietke->update($data);
                return $congviecthietke;
            }
        }
        $this->isExistCongViecThietKe($data["MaCongViec"]);
        /** @var DM_CONG_VIEC_THIET_KE $congviecthietke */
        $congviecthietke = $this->baseRepo->create($data);
        if(!$congviecthietke){
            throw new Exception("Có lỗi xảy ra, vui lòng thử lại");
        }
        return $congviecthietke;
    }

    private function isExistCongViecThietKe(string $maCongViec) {
        $congviecthietke = $this->baseRepo->findOne(["MaCongViec" => $maCongViec]);
        if($congviecthietke){
            throw new Exception("Mã công việc thiết kế đã tồn tại");
        }
    }


    public function delete(int $id): bool {
        $congviecthietke = $this->baseRepo->get($id);
        if(!$congviecthietke){
            throw new Exception("Lớp không tồn tại");
        }
        $congviecthietke->IsDeleted = true;
        return $congviecthietke->save();
    }
}
