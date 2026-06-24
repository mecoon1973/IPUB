<?php
namespace Modules\System\Service\Impl;

use Core\Object\Paginate;
use Illuminate\Support\Facades\Auth;

use Modules\System\Service\NgoaiNguService;
use Modules\System\Repository\NgoaiNguRepository;
use Core\Repository\CountersOlmRepository as CounterRepository;

use Core\Service\BaseService;
use Exception;
use Modules\System\Model\DM_NGOAI_NGU;
use Modules\System\Object\FilterNgoaingu;

class NgoaiNguServiceImpl extends BaseService implements NgoaiNguService
{
    /** @var NgoaiNguRepository */
    protected $baseRepo;

    public function __construct(NgoaiNguRepository $baseRepo) {
        parent::__construct($baseRepo);
    }
    public function getPaginate(FilterNgoaingu $filter, string $page = 'page-1'): array {
        $conditions = $filter->buildConditions();
        $paginate = new Paginate([
            "conditions" => $conditions,
            "limit" => 15,
            "page" => $page,
            "sorted" => $filter->buildSort()
        ]);
        $result = $this->pagination($paginate);
        return [
            "listResult" => $result->list,
            "pagiInfo" => $result->pagi_info
        ];
    }
    public function getList(FilterNgoaingu $filter) {
        if($filter->IsDeleted === null) {
            $filter->IsDeleted = false;
        }
        $result = $this->baseRepo->findAllWithFilter($filter, $filter->buildSort());
        return $result;
    }
    public function store(array $data): DM_NGOAI_NGU {
        if(data_get($data, "id", 0) != 0) {
            /** @var DM_NGOAI_NGU $ngoaingu */
            $ngoaingu = $this->baseRepo->get($data["id"]);
            if($ngoaingu) {
                $ngoaingu->update($data);
                return $ngoaingu;
            }
        }
        $this->isExistNgoaingu($data["MaNgoaingu"]);
        /** @var DM_NGOAI_NGU $ngoaingu */
        $ngoaingu = $this->baseRepo->create($data);
        if(!$ngoaingu) {
            throw new Exception("Có lỗi xảy ra, vui lòng thử lại");
        }
        return $ngoaingu;
    }
    private function isExistNgoaingu(string $maNgoaingu) {
        $ngoaingu = $this->baseRepo->findOne(["MaNgoaingu" => $maNgoaingu]);
        if($ngoaingu) {
            throw new Exception("Mã ngôn ngữ đã tồn tại");
        }
    }
    public function delete(int $id): bool {
        $ngoaingu = $this->baseRepo->get($id);
        if(!$ngoaingu) {
            throw new Exception("Ngôn ngữ không tồn tại");
        }
        $ngoaingu->IsDeleted = true;
        return $ngoaingu->save();
    }
}
