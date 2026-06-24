<?php
namespace Modules\legalDeposit\Service\Impl;

use Core\Object\Paginate;
use Illuminate\Support\Facades\Auth;

use Modules\legalDeposit\Service\PhieuNhapLCService;
use Modules\legalDeposit\Repository\PhieuNhapLCRepository;
use Core\Repository\CountersOlmRepository as CounterRepository;

use Core\Service\BaseService;
use Exception;
use Modules\legalDeposit\Model\DM_PhieuNhapLC;
use Modules\legalDeposit\Object\FilterPhieuNhapLC;

class PhieuNhapLCServiceImpl extends BaseService implements PhieuNhapLCService
{
    /** @var PhieuNhapLCRepository */
    protected $baseRepo;

    public function __construct(PhieuNhapLCRepository $baseRepo) {
        parent::__construct($baseRepo);
    }

    public function getPaginate(FilterPhieuNhapLC $filter, string $page = 'page-1') : array {
        $conditions = $filter->buildConditions();

        $paginate = new Paginate ([
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

    public function getList(FilterPhieuNhapLC $filter) {
        $result = $this->baseRepo->findAllWithFilter($filter);
        return $result;
    }

    public function store(array $data) : DM_PhieuNhapLC {
        if(data_get($data, "id", 0) != 0) {
            /** @var DM_PhieuNhapLC $phieuNhapLC */
            $phieuNhapLC = $this->baseRepo->get($data["id"]);
            if($phieuNhapLC) {
                $phieuNhapLC->update($data);
                return $phieuNhapLC;
            }
        }
        $phieuNhapLC = $this->baseRepo->create($data);
        if(!$phieuNhapLC){
            throw new Exception("Có lỗi xảy ra, vui lòng thử lại");
        }
        return $phieuNhapLC;
    }

    public function delete(int $id) : bool {
        $phieuNhapLC = $this->baseRepo->get($id);
        if(!$phieuNhapLC){
            throw new Exception("Phiếu nhập lưu chỉnh sách không tồn tại");
        }
        $phieuNhapLC->IsDeleted = true;
        if(!$phieuNhapLC->save()){
            throw new Exception("Có lỗi xảy ra, vui lòng thử lại");
        }
        return true;
    }



}
