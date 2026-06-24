<?php
namespace Modules\System\Service\Impl;

use Illuminate\Support\Facades\Auth;
use Core\Object\Paginate;
use Modules\System\Service\ChucvuService;
use Modules\System\Repository\ChucvuRepository;
use Core\Repository\CountersOlmRepository as CounterRepository;

use Core\Service\BaseService;
use Exception;
use Modules\System\Object\FilterChucvu;
use Modules\System\Model\DM_CHUCVU;


class ChucvuServiceImpl extends BaseService implements ChucvuService
{
    /** @var ChucvuRepository */
    protected $baseRepo;

    public function __construct(ChucvuRepository $baseRepo) {
        parent::__construct($baseRepo);
    }

    public function getPaginate(FilterChucvu $filter, string $page = 'page-1'): array {
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

    public function getList(FilterChucvu $filter) {
        $result = $this->baseRepo->findAllWithFilter($filter);
        return $result;
    }
    public function store(array $data): DM_CHUCVU {
        if(data_get($data, "id", 0) != 0) {
            /** @var DM_CHUCVU $chucvu */
            $chucvu = $this->baseRepo->get($data["id"]);
            if($chucvu) {
                $chucvu->update($data);
                return $chucvu;
            }
        }
        $this->isExistChucvu($data["MaChucVu"]);
        /** @var DM_CHUCVU $chucvu */
        $chucvu = $this->baseRepo->create($data);
        if(!$chucvu){
            throw new Exception("Có lỗi xảy ra, vui lòng thử lại");
        }
        return $chucvu;
    }

    public function delete(int $id): bool {
        $chucvu = $this->baseRepo->get($id);
        if(!$chucvu){
            throw new Exception("Chức vụ không tồn tại");
        }
        $chucvu->IsDeleted = true;
        return $chucvu->save();
    }

    private function isExistChucvu(string $maChucVu) {
        $chucvu = $this->baseRepo->findOne(["MaChucVu" => $maChucVu]);
        if($chucvu){
            throw new Exception("Mã chức vụ đã tồn tại");
        }
    }
}
