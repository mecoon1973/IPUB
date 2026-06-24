<?php
namespace Modules\System\Service\Impl;

use Core\Object\Paginate;
use Illuminate\Support\Facades\Auth;

use Modules\System\Service\MangsachService;
use Modules\System\Repository\MangsachRepository;
use Core\Repository\CountersOlmRepository as CounterRepository;

use Core\Service\BaseService;
use Exception;
use Modules\System\Model\DM_MANGSACH;
use Modules\System\Object\FilterMangsach;

class MangsachServiceImpl extends BaseService implements MangsachService
{
    /** @var MangsachRepository */
    protected $baseRepo;

    public function __construct(MangsachRepository $baseRepo) {
        parent::__construct($baseRepo);
    }

    public function getPaginate(FilterMangsach $filter, string $page = 'page-1') : array {
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
    public function getList(FilterMangsach $filter) {
        if($filter->IsDeleted === null) {
            $filter->IsDeleted = false;
        }
        $result = $this->baseRepo->findAllWithFilter($filter);
        return $result;
    }
    public function store(array $data) : DM_MANGSACH {
        if(data_get($data, "id", 0) != 0) {
            /** @var DM_MANGSACH $mangsach */
            $mangsach = $this->baseRepo->get($data["id"]);
            if($mangsach){
                $mangsach->update($data);
                return $mangsach;
            }
        }
        $mangsach = $this->baseRepo->create($data);
        if(!$mangsach){
            throw new Exception("Có lỗi xảy ra, vui lòng thử lại");
        }
        return $mangsach;
    }
    public function delete(int $id) : bool {
        $mangsach = $this->baseRepo->get($id);
        if(!$mangsach){
            throw new Exception("Mã sách không tồn tại");
        }
        $mangsach->IsDeleted = true;
        if(!$mangsach->save()){
            throw new Exception("Có lỗi xảy ra, vui lòng thử lại");
        }
        return true;
    }
}
