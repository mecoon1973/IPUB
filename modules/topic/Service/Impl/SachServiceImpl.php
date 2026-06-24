<?php
namespace Modules\Topic\Service\Impl;

use Core\Object\Paginate;
use Illuminate\Support\Facades\Auth;

use Modules\Topic\Service\SachService;
use Modules\Topic\Repository\SachRepository;
use Core\Repository\CountersOlmRepository as CounterRepository;

use Core\Service\BaseService;
use Exception;
use Modules\Topic\Model\DM_SACH;
use Modules\Topic\Object\FilterSach;

class SachServiceImpl extends BaseService implements SachService
{
    /** @var SachRepository */
    protected $baseRepo;

    public function __construct(SachRepository $baseRepo) {
        parent::__construct($baseRepo);
    }
    public function getPaginate(FilterSach $filter, string $page = 'page-1'): array {
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

    public function getList(FilterSach $filter) {
        return $this->baseRepo->findAllWithFilter($filter);
    }

    public function store(array $data): DM_SACH {
        if(data_get($data, "id", 0) != 0) {
            /** @var DM_SACH $sach */
            $sach = $this->baseRepo->get($data["id"]);
            if($sach) {
                $sach->update($data);
                return $sach;
            }
        }

        /** @var DM_SACH $sach */
        $sach = $this->baseRepo->create($data);
        if(!$sach){
            throw new Exception("Có lỗi xảy ra, vui lòng thử lại");
        }
        return $sach;
    }

    public function delete(int $id): bool {
        $sach = $this->baseRepo->get($id);
        if(!$sach){
            throw new Exception("Sách không tồn tại");
        }
        $sach->IsDeleted = true;
        return $sach->save();
    }
}
