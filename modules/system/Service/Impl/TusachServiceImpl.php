<?php
namespace Modules\System\Service\Impl;

use Core\Object\Paginate;
use Illuminate\Support\Facades\Auth;

use Modules\System\Service\TusachService;
use Modules\System\Repository\TusachRepository;
use Core\Repository\CountersOlmRepository as CounterRepository;

use Core\Service\BaseService;
use Exception;
use Modules\System\Model\DM_TUSACH;
use Modules\System\Object\FilterTusach;

class TusachServiceImpl extends BaseService implements TusachService
{
    /** @var TusachRepository */
    protected $baseRepo;

    public function __construct(TusachRepository $baseRepo) {
        parent::__construct($baseRepo);
    }

    public function getPaginate(FilterTusach $filter, string $page = 'page-1') : array {
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

    public function getList(FilterTusach $filter) {
        $result = $this->baseRepo->findAllWithFilter($filter);
        return $result;
    }

    public function store(array $data) : DM_TUSACH {
        if(data_get($data, "id", 0) != 0) {
            /** @var DM_TUSACH $tusach */
            $tusach = $this->baseRepo->get($data["id"]);
            if($tusach) {
                $tusach->update($data);
                return $tusach;
            }
        }
        $this->isExistTusach($data["MaTuSach"]);
        $tusach = $this->baseRepo->create($data);
        if(!$tusach){
            throw new Exception("Có lỗi xảy ra, vui lòng thử lại");
        }
        return $tusach;
    }

    public function delete(int $id) : bool {
        $tusach = $this->baseRepo->get($id);
        if(!$tusach){
            throw new Exception("Quyền không tồn tại");
        }
        $tusach->IsDeleted = true;
        if(!$tusach->save()){
            throw new Exception("Có lỗi xảy ra, vui lòng thử lại");
        }
        return true;
    }

    private function isExistTusach(string $maTuSach) {
        $tusach = $this->baseRepo->findOne(["MaTuSach" => $maTuSach]);
        if($tusach){
            throw new Exception("Mã tủ sách đã tồn tại");
        }
    }
}
