<?php
namespace Modules\System\Service\Impl;

use Core\Object\Paginate;
use Illuminate\Support\Facades\Auth;

use Modules\System\Service\BienMoiTruongService;
use Modules\System\Repository\BienMoiTruongRepository;
use Core\Repository\CountersOlmRepository as CounterRepository;

use Core\Service\BaseService;
use Exception;
use Modules\System\Model\DM_BIEN_MOI_TRUONG;
use Modules\System\Object\FilterBienMoiTruong;

class BienMoiTruongServiceImpl extends BaseService implements BienMoiTruongService
{
    /** @var BienMoiTruongRepository */
    protected $baseRepo;

    public function __construct(BienMoiTruongRepository $baseRepo) {
        parent::__construct($baseRepo);
    }

    public function getPaginate(FilterBienMoiTruong $filter, string $page = 'page-1') : array {
        $conditions = $filter->buildConditions();
        $paginate = new Paginate ([
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

    public function getList(FilterBienMoiTruong $filter) {
        $result = $this->baseRepo->findAllWithFilter($filter);
        return $result;
    }

    public function store(array $data) : DM_BIEN_MOI_TRUONG {
        if(data_get($data, "id", 0) != 0) {
            /** @var DM_BIEN_MOI_TRUONG $bienMoiTruong */
            $bienMoiTruong = $this->baseRepo->get($data["id"]);
            if($bienMoiTruong) {
                $bienMoiTruong->update($data);
                return $bienMoiTruong;
            }
        }
        $this->isExistBienMoiTruong($data["ConfigName"]);
        $bienMoiTruong = $this->baseRepo->create($data);
        if(!$bienMoiTruong){
            throw new Exception("Có lỗi xảy ra, vui lòng thử lại");
        }
        return $bienMoiTruong;
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

    private function isExistBienMoiTruong(string $configName) {
        $bienMoiTruong = $this->baseRepo->findOne(["ConfigName" => $configName]);
        if($bienMoiTruong){
            throw new Exception("Mã biến mới trường đã tồn tại");
        }
    }
}
