<?php
namespace Modules\Topic\Service\Impl;

use Core\Object\Paginate;
use Illuminate\Support\Facades\Auth;

use Modules\Topic\Service\CT_QD_InService;
use Modules\Topic\Repository\CT_QD_InRepository;
use Core\Repository\CountersOlmRepository as CounterRepository;

use Core\Service\BaseService;
use Exception;
use Modules\Topic\Model\CT_QD_In;
use Modules\Topic\Object\FilterCT_QD_In;


class CT_QD_InServiceImpl extends BaseService implements CT_QD_InService
{
    /** @var CT_QD_InRepository */
    protected $baseRepo;

    public function __construct(CT_QD_InRepository $baseRepo) {
        parent::__construct($baseRepo);
    }

    public function getPaginate(FilterCT_QD_In $filter, string $page = 'page-1'): array {
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

    public function getList(FilterCT_QD_In $filter) {
        return $this->baseRepo->findAllWithFilter($filter);
    }

    public function store(array $data): CT_QD_In {
        if(data_get($data, "id", 0) != 0) {
            /** @var CT_QD_In $ct_qd_in */
            $ct_qd_in = $this->baseRepo->get($data["id"]);
            if($ct_qd_in) {
                $ct_qd_in->update($data);
                return $ct_qd_in;
            }
        }

        /** @var CT_QD_In $ct_qd_in */
        $ct_qd_in = $this->baseRepo->create($data);
        if(!$ct_qd_in){
            throw new Exception("Có lỗi xảy ra, vui lòng thử lại");
        }
        return $ct_qd_in;
    }

    public function delete(int $id): bool {
        $ct_qd_in = $this->baseRepo->get($id);
        if(!$ct_qd_in){
            throw new Exception("Chi tiết quyết định in không tồn tại");
        }
        $ct_qd_in->IsDeleted = true;
        return $ct_qd_in->save();
    }
}
