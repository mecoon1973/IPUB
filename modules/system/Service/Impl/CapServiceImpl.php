<?php
namespace Modules\System\Service\Impl;

use Illuminate\Support\Facades\Auth;

use Modules\System\Service\CapService;
use Modules\System\Repository\CapRepository;
use Core\Repository\CountersOlmRepository as CounterRepository;
use Modules\System\Model\DM_CAP;
use Modules\System\Object\FilterCap;
use Core\Object\Paginate;
use Core\Service\BaseService;
use Exception;

class CapServiceImpl extends BaseService implements CapService
{
    /** @var CapRepository */
    protected $baseRepo;

    public function __construct(CapRepository $baseRepo) {
        parent::__construct($baseRepo);
    }

    public function getPaginate(FilterCap $filter, string $page = 'page-1') : array {
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

    public function getList(FilterCap $filter) {
        $result = $this->baseRepo->findAllWithFilter($filter);
        return $result;
    }

    public function store(array $data) : DM_CAP {
        if(data_get($data, "id", 0) != 0) {
            /** @var DM_CAP $cap */
            $cap = $this->baseRepo->get($data["id"]);
            if($cap) {
                $cap->update($data);
                return $cap;
            }
        }


        /** @var DM_CAP $cap */
        $cap = $this->baseRepo->create($data);
        if(!$cap){
            throw new Exception("Cap không tồn tại");
        }
        return $cap;
    }

    public function delete(int $id) : bool {
        $cap = $this->baseRepo->get($id);
        if(!$cap){
            throw new Exception("Cap không tồn tại");
        }
        $cap->IsDeleted = true;
        return $cap->save();
    }

}
