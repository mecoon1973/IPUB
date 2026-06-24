<?php
namespace Modules\System\Service\Impl;

use Core\Object\Paginate;
use Illuminate\Support\Facades\Auth;

use Modules\System\Service\MangsachCXBService;
use Modules\System\Repository\MangsachCXBRepository;
use Core\Repository\CountersOlmRepository as CounterRepository;

use Core\Service\BaseService;
use Exception;
use Modules\System\Model\DM_MANGSACH_CXB;
use Modules\System\Object\FilterMangsachCXB;

class MangsachCXBServiceImpl extends BaseService implements MangsachCXBService
{
    /** @var MangsachCXBRepository */
    protected $baseRepo;

    public function __construct(MangsachCXBRepository $baseRepo) {
        parent::__construct($baseRepo);
    }
    public function getPaginate(FilterMangsachCXB $filter, string $page = 'page-1') : array {
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
    public function getList(FilterMangsachCXB $filter) {
        if($filter->IsDeleted === null) {
            $filter->IsDeleted = false;
        }
        $result = $this->baseRepo->findAllWithFilter($filter);
        return $result;
    }
    public function store(array $data) : DM_MANGSACH_CXB {
        if(data_get($data, "id", 0) != 0) {
            /** @var DM_MANGSACH_CXB $mangsachCXB */
            $mangsachCXB = $this->baseRepo->get($data["id"]);
            if($mangsachCXB){
                $mangsachCXB->update($data);
                return $mangsachCXB;
            }
        }
        $mangsachCXB = $this->baseRepo->create($data);
        if(!$mangsachCXB){
            throw new Exception("Có lỗi xảy ra, vui lòng thử lại");
        }
        return $mangsachCXB;
    }
    public function delete(int $id) : bool {
        $mangsachCXB = $this->baseRepo->get($id);
        if(!$mangsachCXB){
            throw new Exception("Mã sách không tồn tại");
        }
        $mangsachCXB->IsDeleted = true;
        if(!$mangsachCXB->save()){
            throw new Exception("Có lỗi xảy ra, vui lòng thử lại");
        }
        return true;
    }
}
