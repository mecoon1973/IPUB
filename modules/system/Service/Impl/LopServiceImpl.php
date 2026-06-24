<?php
namespace Modules\System\Service\Impl;

use Core\Object\Paginate;
use Illuminate\Support\Facades\Auth;

use Modules\System\Service\LopService;
use Modules\System\Repository\LopRepository;
use Core\Repository\CountersOlmRepository as CounterRepository;

use Core\Service\BaseService;
use Exception;
use Modules\System\Model\DM_LOP;
use Modules\System\Object\FilterLop;

class LopServiceImpl extends BaseService implements LopService
{
    /** @var LopRepository */
    protected $baseRepo;

    public function __construct(LopRepository $baseRepo) {
        parent::__construct($baseRepo);
    }

    public function getPaginateLop(FilterLop $filter, string $page): array {
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
    public function getListLop(FilterLop $filter) {
        if($filter->IsDeleted === null) {
            $filter->IsDeleted = false;
        }
        $result = $this->baseRepo->findAllWithFilter($filter);
        return $result;
    }

    public function store(array $data): DM_LOP {
        if(data_get($data, "id", 0) != 0) {
            /** @var DM_LOP $lop */
            $lop = $this->baseRepo->get($data["id"]);
            if($lop) {
                $lop->update($data);
                return $lop;
            }
        }
        $this->isExistLop($data["MaLop"]);
        /** @var DM_LOP $lop */
        $lop = $this->baseRepo->create($data);
        if(!$lop){
            throw new Exception("Có lỗi xảy ra, vui lòng thử lại");
        }
        return $lop;
    }

    private function isExistLop(string $maLop) {
        $lop = $this->baseRepo->findOne(["MaLop" => $maLop]);
        if($lop){
            throw new Exception("Mã lớp đã tồn tại");
        }
    }

    public function delete(int $id): bool {
        $lop = $this->baseRepo->get($id);
        if(!$lop){
            throw new Exception("Lớp không tồn tại");
        }
        $lop->IsDeleted = true;
        return $lop->save();
    }

}
