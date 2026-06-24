<?php
namespace Modules\Topic\Service\Impl;

use Illuminate\Support\Facades\Auth;
use Core\Object\Paginate;
use Modules\Topic\Service\QDInService;
use Modules\Topic\Repository\QDInRepository;
use Core\Repository\CountersOlmRepository as CounterRepository;

use Core\Service\BaseService;
use Exception;
use Modules\Topic\Model\QDIn;
use Modules\Topic\Object\FilterQDIn;


class QDInServiceImpl extends BaseService implements QDInService
{
    /** @var QDInRepository */
    protected $baseRepo;

    public function __construct(QDInRepository $baseRepo) {
        parent::__construct($baseRepo);
    }
    public function getPaginate(FilterQDIn $filter, string $page = 'page-1'): array {
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

    public function getList(FilterQDIn $filter) {
        return $this->baseRepo->findAllWithFilter($filter);
    }

    public function store(array $data): QDIn {
        if(data_get($data, "id", 0) != 0) {
            /** @var QDIn $qdIn */
            $qdIn = $this->baseRepo->get($data["id"]);
            if($qdIn) {
                $qdIn->update($data);
                return $qdIn;
            }
        }

        /** @var QDIn $qdIn */
        $qdIn = $this->baseRepo->create($data);
        if(!$qdIn){
            throw new Exception("Có lỗi xảy ra, vui lòng thử lại");
        }
        return $qdIn;
    }

    public function delete(int $id): bool {
        $qdIn = $this->baseRepo->get($id);
        if(!$qdIn){
            throw new Exception("Quyết định in không tồn tại");
        }
        $qdIn->IsDeleted = true;
        return $qdIn->save();
    }

}
