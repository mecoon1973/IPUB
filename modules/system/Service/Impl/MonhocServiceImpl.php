<?php
namespace Modules\System\Service\Impl;

use Core\Object\Paginate;
use Illuminate\Support\Facades\Auth;

use Modules\System\Service\MonhocService;
use Modules\System\Repository\MonhocRepository;
use Core\Repository\CountersOlmRepository as CounterRepository;

use Core\Service\BaseService;
use Exception;
use Modules\System\Model\DM_MONHOC;
use Modules\System\Object\FilterMonhoc;

class MonhocServiceImpl extends BaseService implements MonhocService
{
    /** @var MonhocRepository */
    protected $baseRepo;

    public function __construct(MonhocRepository $baseRepo) {
        parent::__construct($baseRepo);
    }

    public function getPaginateMonhoc(FilterMonhoc $filter, string $page = 'page-1'): array {
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
    public function getListMonhoc(FilterMonhoc $filter) {
        if($filter->IsDeleted === null) {
            $filter->IsDeleted = false;
        }
        $result = $this->baseRepo->findAllWithFilter($filter, $filter->buildSort());
        return $result;
    }
    public function store(array $data): DM_MONHOC {
        if(data_get($data, "id", 0) != 0) {
            /** @var DM_MONHOC $monhoc */
            $monhoc = $this->baseRepo->get($data["id"]);
            if($monhoc) {
                $monhoc->update($data);
                return $monhoc;
            }
        }
        $this->isExistMonhoc($data["MaMonHoc"]);
        /** @var DM_MONHOC $monhoc */
        $monhoc = $this->baseRepo->create($data);
        if(!$monhoc) {
            throw new Exception("Có lỗi xảy ra, vui lòng thử lại");
        }
        return $monhoc;
    }
    private function isExistMonhoc(string $maMonhoc) {
        $monhoc = $this->baseRepo->findOne(["MaMonHoc" => $maMonhoc]);
        if($monhoc) {
            throw new Exception("Mã môn học đã tồn tại");
        }
    }
    public function delete(int $id): bool {
        $monhoc = $this->baseRepo->get($id);
        if(!$monhoc) {
            throw new Exception("Môn học không tồn tại");
        }
        $monhoc->IsDeleted = true;
        return $monhoc->save();
    }
}
