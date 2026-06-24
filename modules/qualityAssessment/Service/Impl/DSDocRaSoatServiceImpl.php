<?php
namespace Modules\QualityAssessment\Service\Impl;

use Core\Object\Paginate;
use Illuminate\Support\Facades\Auth;

use Modules\QualityAssessment\Service\DSDocRaSoatService;
use Modules\QualityAssessment\Repository\DSDocRaSoatRepository;
use Core\Repository\CountersOlmRepository as CounterRepository;

use Core\Service\BaseService;
use Exception;
use Modules\QualityAssessment\Model\DM_DSDocRaSoat;
use Modules\QualityAssessment\Object\FilterDSDocRaSoat;

class DSDocRaSoatServiceImpl extends BaseService implements DSDocRaSoatService
{
    /** @var DSDocRaSoatRepository */
    protected $baseRepo;

    public function __construct(DSDocRaSoatRepository $baseRepo) {
        parent::__construct($baseRepo);
    }

    public function getPaginate(FilterDSDocRaSoat $filter, string $page = 'page-1'): array {
        $conditions = $filter->buildConditions();
        $paginate = new Paginate([
            "conditions" => $conditions,
            "limit" => 15,
            "page" => $page,
            "loadRelations" => $filter->relations
        ]);
        $result = $this->pagination($paginate);
        return [
            "listResult" => $result->list,
            "pagiInfo" => $result->pagi_info
        ];
    }

    public function getList(FilterDSDocRaSoat $filter) {
        $result = $this->baseRepo->findAllWithFilter($filter);
        return $result;
    }

    public function store(array $data): DM_DSDocRaSoat {
        if(data_get($data, "id", 0) != 0) {
            /** @var DM_DSDocRaSoat $dsDocRaSoat */
            $dsDocRaSoat = $this->baseRepo->get($data["id"]);
            if($dsDocRaSoat) {
                $dsDocRaSoat->update($data);
                return $dsDocRaSoat;
            }
        }
        /** @var DM_DSDocRaSoat $dsDocRaSoat */
        $dsDocRaSoat = $this->baseRepo->create($data);
        if(!$dsDocRaSoat){
            throw new Exception("DS đề xuất ra soát không tạo được");
        }
        return $dsDocRaSoat;
    }

    public function delete(int $id): bool {
        $dsDocRaSoat = $this->baseRepo->get($id);
        if(!$dsDocRaSoat){
            throw new Exception("DS đề xuất ra soát không tồn tại");
        }
        $dsDocRaSoat->IsDeleted = true;
        if(!$dsDocRaSoat->save()){
            throw new Exception("Có lỗi xảy ra, vui lòng thử lại");
        }
        return true;
    }

}
