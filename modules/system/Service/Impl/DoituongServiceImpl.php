<?php
namespace Modules\System\Service\Impl;

use Core\Object\Paginate;
use Illuminate\Support\Facades\Auth;

use Modules\System\Service\DoituongService;
use Modules\System\Repository\DoituongRepository;
use Core\Repository\CountersOlmRepository as CounterRepository;

use Core\Service\BaseService;
use Exception;
use Modules\System\Model\DM_DOITUONG;
use Modules\System\Object\FilterDoituong;

class DoituongServiceImpl extends BaseService implements DoituongService
{
    /** @var DoituongRepository */
    protected $baseRepo;

    public function __construct(DoituongRepository $baseRepo) {
        parent::__construct($baseRepo);
    }

    public function getPaginate(FilterDoituong $filter, string $page = 'page-1'): array {
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

    public function getList(FilterDoituong $filter) {
        $result = $this->baseRepo->findAllWithFilter($filter);
        return $result;
    }

    public function store(array $data): DM_DOITUONG {
        if(data_get($data, "id", 0) != 0) {
            /** @var DM_DOITUONG $doituong */
            $doituong = $this->baseRepo->get($data["id"]);
            if($doituong) {
                $doituong->update($data);
                return $doituong;
            }
        }
        /** @var DM_DOITUONG $doituong */
        $doituong = $this->baseRepo->create($data);
        if(!$doituong){
            throw new Exception("Có lỗi xảy ra, vui lòng thử lại");
        }
        return $doituong;
    }

    public function delete(int $id): bool {
        $doituong = $this->baseRepo->get($id);
        if(!$doituong){
            throw new Exception("Đối tượng không tồn tại");
        }
        $doituong->IsDeleted = true;
        return $doituong->save();
    }
}
