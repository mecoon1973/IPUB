<?php
namespace Modules\System\Service\Impl;

use Core\Object\Paginate;
use Illuminate\Support\Facades\Auth;

use Modules\System\Service\CongviecchebaninService;
use Modules\System\Repository\CongviecchebaninRepository;
use Core\Repository\CountersOlmRepository as CounterRepository;

use Core\Service\BaseService;
use Exception;
use Modules\System\Model\DM_CONG_VIEC_CHE_BAN_IN;
use Modules\System\Object\FilterCongviecchebanin;

class CongviecchebaninServiceImpl extends BaseService implements CongviecchebaninService
{
    /** @var CongviecchebaninRepository */
    protected $baseRepo;

    public function __construct(CongviecchebaninRepository $baseRepo) {
        parent::__construct($baseRepo);
    }
    public function getPaginate(FilterCongviecchebanin $filter, string $page): array {
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
    public function getList(FilterCongviecchebanin $filter) {
        if($filter->IsDeleted === null) {
            $filter->IsDeleted = false;
        }
        $result = $this->baseRepo->findAllWithFilter($filter);
        return $result;
    }

    public function store(array $data): DM_CONG_VIEC_CHE_BAN_IN {
        if(data_get($data, "id", 0) != 0) {
            /** @var DM_CONG_VIEC_CHE_BAN_IN $congviecchebanin */
            $congviecchebanin = $this->baseRepo->get($data["id"]);
            if($congviecchebanin) {
                $congviecchebanin->update($data);
                return $congviecchebanin;
            }
        }
        $this->isExistCongViecCheBanIn($data["MaCongViec"]);
        /** @var DM_CONG_VIEC_CHE_BAN_IN $congviecchebanin */
        $congviecchebanin = $this->baseRepo->create($data);
        if(!$congviecchebanin){
            throw new Exception("Có lỗi xảy ra, vui lòng thử lại");
        }
        return $congviecchebanin;
    }

    private function isExistCongViecCheBanIn(string $maCongViec) {
        $congviecchebanin = $this->baseRepo->findOne(["MaCongViec" => $maCongViec]);
        if($congviecchebanin){
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
