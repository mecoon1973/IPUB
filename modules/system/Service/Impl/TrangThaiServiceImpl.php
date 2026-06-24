<?php
namespace Modules\System\Service\Impl;

use Core\Object\Paginate;
use Illuminate\Support\Facades\Auth;

use Modules\System\Service\TrangThaiService;
use Modules\System\Repository\TrangThaiRepository;
use Core\Repository\CountersOlmRepository as CounterRepository;

use Core\Service\BaseService;
use Exception;
use Modules\System\Model\DM_TRANG_THAI;
use Modules\System\Object\FilterTrangThai;

class TrangThaiServiceImpl extends BaseService implements TrangThaiService
{
    /** @var TrangThaiRepository */
    protected $baseRepo;

    public function __construct(TrangThaiRepository $baseRepo) {
        parent::__construct($baseRepo);
    }

    public function getPaginate(FilterTrangThai $filter, string $page = 'page-1') : array {
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

    public function getList(FilterTrangThai $filter) {
        $result = $this->baseRepo->findAllWithFilter($filter);
        return $result;
    }

    public function store(array $data) : DM_TRANG_THAI {
        if(data_get($data, "id", 0) != 0) {
            /** @var DM_TRANG_THAI $trangThai */
            $trangThai = $this->baseRepo->get($data["id"]);
            if($trangThai) {
                $trangThai->update($data);
                return $trangThai;
            }
        }
        $this->isExistTrangThai($data["MaTrangThai"]);
        $trangThai = $this->baseRepo->create($data);
        if(!$trangThai){
            throw new Exception("Có lỗi xảy ra, vui lòng thử lại");
        }
        return $trangThai;
    }

    public function delete(int $id) : bool {
        // $trangThai = $this->baseRepo->get($id);
        // if(!$trangThai){
        //     throw new Exception("Quyền không tồn tại");
        // }
        // $trangThai->IsDeleted = true;
        // if(!$trangThai->save()){
        //     throw new Exception("Có lỗi xảy ra, vui lòng thử lại");
        // }
        return true;
    }

    private function isExistTrangThai(string $maTrangThai) {
        $trangThai = $this->baseRepo->findOne(["MaTrangThai" => $maTrangThai]);
        if($trangThai){
            throw new Exception("Mã trạng thái đã tồn tại");
        }
    }

}
