<?php
namespace Modules\System\Service\Impl;

use Core\Object\Paginate;
use Illuminate\Support\Facades\Auth;

use Modules\System\Service\LoaiXBPService;
use Modules\System\Repository\LoaiXBPRepository;
use Core\Repository\CountersOlmRepository as CounterRepository;

use Core\Service\BaseService;
use Exception;
use Modules\System\Model\DM_LOAI_XBP;
use Modules\System\Object\FilterLoaiXBP;

class LoaiXBPServiceImpl extends BaseService implements LoaiXBPService
{
    /** @var LoaiXBPRepository */
    protected $baseRepo;

    public function __construct(LoaiXBPRepository $baseRepo) {
        parent::__construct($baseRepo);
    }

    public function getPaginate(FilterLoaiXBP $filter, string $page): array {
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
    public function getList(FilterLoaiXBP $filter) {
        if($filter->IsDeleted === null) {
            $filter->IsDeleted = false;
        }
        $result = $this->baseRepo->findAllWithFilter($filter);
        return $result;
    }

    public function store(array $data): DM_LOAI_XBP {
        if(data_get($data, "id", 0) != 0) {
            /** @var DM_LOAI_XBP $loaiXBP */
            $loaiXBP = $this->baseRepo->get($data["id"]);
            if($loaiXBP) {
                $loaiXBP->update($data);
                return $loaiXBP;
            }
        }
        $this->isExistLoaiXBP($data["MaLoai"]);
        /** @var DM_LOAI_XBP $loaiXBP */
        $loaiXBP = $this->baseRepo->create($data);
        if(!$loaiXBP){
            throw new Exception("Có lỗi xảy ra, vui lòng thử lại");
        }
        return $loaiXBP;
    }

    private function isExistLoaiXBP(string $maLoai) {
        $loaiXBP = $this->baseRepo->findOne(["MaLoai" => $maLoai]);
        if($loaiXBP){
            throw new Exception("Mã loại xuất bản đã tồn tại");
        }
    }

    public function delete(int $id): bool {
        $loaiXBP = $this->baseRepo->get($id);
        if(!$loaiXBP){
            throw new Exception("Lớp không tồn tại");
        }
        $loaiXBP->IsDeleted = true;
        return $loaiXBP->save();
    }
}
