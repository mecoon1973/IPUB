<?php
namespace Modules\System\Service\Impl;

use Core\Object\Paginate;
use Illuminate\Support\Facades\Auth;

use Modules\System\Service\ChuyenmonService;
use Modules\System\Repository\ChuyenmonRepository;
use Core\Repository\CountersOlmRepository as CounterRepository;

use Core\Service\BaseService;
use Exception;
use Modules\System\Model\DM_CHUYENMON;
use Modules\System\Object\FilterChuyenmon;

class ChuyenmonServiceImpl extends BaseService implements ChuyenmonService
{
    /** @var ChuyenmonRepository */
    protected $baseRepo;

    public function __construct(ChuyenmonRepository $baseRepo) {
        parent::__construct($baseRepo);
    }

    public function getPaginate(FilterChuyenmon $filter, string $page = 'page-1') : array {
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

    public function getList(FilterChuyenmon $filter) {
        $result = $this->baseRepo->findAllWithFilter($filter);
        return $result;
    }

    public function store(array $data) : DM_CHUYENMON {
        if(data_get($data, "id", 0) != 0) {
            /** @var DM_CHUYENMON $chuyenmon */
            $chuyenmon = $this->baseRepo->get($data["id"]);
            if($chuyenmon) {
                $chuyenmon->update($data);
                return $chuyenmon;
            }
        }
        $chuyenmon = $this->baseRepo->create($data);
        if(!$chuyenmon){
            throw new Exception("Có lỗi xảy ra, vui lòng thử lại");
        }
        return $chuyenmon;
    }

    public function delete(int $id) : bool {
        $chuyenmon = $this->baseRepo->get($id);
        if(!$chuyenmon){
            throw new Exception("Quyền không tồn tại");
        }
        $chuyenmon->IsDeleted = true;
        if(!$chuyenmon->save()){
            throw new Exception("Có lỗi xảy ra, vui lòng thử lại");
        }
        return true;
    }

}
