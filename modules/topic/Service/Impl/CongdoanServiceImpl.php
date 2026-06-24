<?php
namespace Modules\Topic\Service\Impl;

use Core\Object\Paginate;

use Modules\Topic\Service\CongdoanService;
use Modules\Topic\Repository\CongdoanRepository;

use Core\Service\BaseService;
use Exception;
use Modules\Topic\Model\DM_CONGDOAN;
use Modules\Topic\Object\FilterCongdoan;

class CongdoanServiceImpl extends BaseService implements CongdoanService
{
    /** @var CongdoanRepository */
    protected $baseRepo;

    public function __construct(CongdoanRepository $baseRepo) {
        parent::__construct($baseRepo);
    }
    public function getPaginate(FilterCongdoan $filter, string $page): array {
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
    public function getList(FilterCongdoan $filter) {
        $result = $this->baseRepo->findAllWithFilter($filter);
        return $result;
    }

    public function store(array $data): DM_CONGDOAN {
        if(data_get($data, "id", 0) != 0) {
            /** @var DM_CONGDOAN $congdoan */
            $congdoan = $this->baseRepo->get($data["id"]);
            if($congdoan) {
                $congdoan->update($data);
                return $congdoan;
            }
        }
        $this->isExistCongViecCheBanIn($data["MaCongViec"]);
        /** @var DM_CONGDOAN $congdoan */
        $congdoan = $this->baseRepo->create($data);
        if(!$congdoan){
            throw new Exception("Có lỗi xảy ra, vui lòng thử lại");
        }
        return $congdoan;
    }

    private function isExistCongViecCheBanIn(string $maCongViec) {
        $congdoan = $this->baseRepo->findOne(["MaCongViec" => $maCongViec]);
        if($congdoan){
            throw new Exception("Mã công việc che ban in đã tồn tại");
        }
    }

    public function delete(int $id): bool {
        $congviecchebanin = $this->baseRepo->get($id);
        if(!$congviecchebanin){
            throw new Exception("Lớp không tồn tại");
        }
        $congviecchebanin->IsDeleted = true;
        return $congviecchebanin->save();
    }
}
