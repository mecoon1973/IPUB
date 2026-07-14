<?php
namespace Modules\Book\Service\Impl;

use Illuminate\Support\Facades\Auth;
use Core\Object\Paginate;
use Exception;
use Modules\Book\Model\DM_SACH;
use Modules\Book\Object\FilterSach;
use Modules\Book\Service\SachService;
use Modules\Book\Repository\SachRepository;
use Core\Repository\CountersOlmRepository as CounterRepository;

use Core\Service\BaseService;


class SachServiceImpl extends BaseService implements SachService
{
    /** @var SachRepository */
    protected $baseRepo;

    public function __construct(SachRepository $baseRepo) {
        parent::__construct($baseRepo);
    }

    public function getPaginate(FilterSach $filter, string $page = 'page-1') : array {
        $conditions = $filter->buildConditions();
        $paginate = new Paginate([
            "conditions" => $conditions,
            "limit" => 15,
            "page" => $page,
            "loadRelations" => $filter->relations,
        ]);
        $result = $this->pagination($paginate);
        return [
            "listResult" => $result->list,
            "pagiInfo" => $result->pagi_info
        ];
    }

    public function paginateWithConditions(array $conditions, string $page = 'page-1', int $limit = 100): array {
        $paginate = new Paginate([
            "conditions" => $conditions,
            "limit" => $limit,
            "page" => $page,
        ]);
        $result = $this->pagination($paginate);
        return [
            "listResult" => $result->list,
            "pagiInfo" => $result->pagi_info
        ];
    }

    public function getList(FilterSach $filter) {
        $result = $this->baseRepo->findAllWithFilter($filter);
        return $result;
    }

    public function store(array $data) : DM_SACH {
        if(data_get($data, "id", 0) != 0) {
            /** @var DM_SACH $sach */
            $sach = $this->baseRepo->get($data["id"]);
            if($sach) {
                $sach->update($data);
                return $sach;
            }
        }
        $sach = $this->baseRepo->create($data);
        if(!$sach){
            throw new Exception("Có lỗi xảy ra, vui lòng thử lại");
        }
        return $sach;
    }

    public function delete(int $id) : bool {
        $sach = $this->baseRepo->get($id);
        if(!$sach){
            throw new Exception("Sách không tồn tại");
        }
        $sach->IsDeleted = true;
        if(!$sach->save()){
            throw new Exception("Có lỗi xảy ra, vui lòng thử lại");
        }
        return true;
    }

}
