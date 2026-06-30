<?php
namespace Modules\Topic\Service\Impl;

use Core\Object\Paginate;
use Illuminate\Support\Facades\Auth;

use Modules\Topic\Service\CT_Detai_CongDoanService;
use Modules\Topic\Repository\CT_Detai_CongDoanRepository;
use Core\Repository\CountersOlmRepository as CounterRepository;

use Core\Service\BaseService;
use Exception;
use Modules\Topic\Model\CT_Detai_Congdoan;
use Modules\Topic\Object\FilterCT_Detai_Congdoan;

class CT_Detai_CongDoanServiceImpl extends BaseService implements CT_Detai_CongDoanService
{
    /** @var CT_Detai_CongDoanRepository */
    protected $baseRepo;

    public function __construct(CT_Detai_CongDoanRepository $baseRepo) {
        parent::__construct($baseRepo);
    }
    public function getPaginate(FilterCT_Detai_Congdoan $filter, string $page = 'page-1'): array {
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

    public function getList(FilterCT_Detai_Congdoan $filter) {
        return $this->baseRepo->findAllWithFilter($filter);
    }

    public function store(array $data): CT_Detai_Congdoan {
        if(data_get($data, "id", 0) != 0) {
            /** @var CT_Detai_Congdoan $phieuDkDetai */
            $ct_detai_congdoan = $this->baseRepo->get($data["id"]);
            if($ct_detai_congdoan) {
                $ct_detai_congdoan->update($data);
                return $ct_detai_congdoan;
            }
        }

        /** @var CT_Detai_Congdoan $ct_detai_congdoan */
        $ct_detai_congdoan = $this->baseRepo->create($data);
        if(!$ct_detai_congdoan){
            throw new Exception("Có lỗi xảy ra, vui lòng thử lại");
        }
        return $ct_detai_congdoan;
    }

    public function delete(int $id): bool {
        $ct_detai_congdoan = $this->baseRepo->get($id);
        if(!$ct_detai_congdoan){
            throw new Exception("CT_Detai_Congdoan không tồn tại");
        }
        $ct_detai_congdoan->IsDeleted = true;
        return $ct_detai_congdoan->save();
    }
}
