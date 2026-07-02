<?php
namespace Modules\Book\Service\Impl;

use Core\Object\Paginate;
use Illuminate\Support\Facades\Auth;

use Modules\Book\Service\PhieuChuyenBanThaoService;
use Modules\Book\Repository\PhieuChuyenBanThaoRepository;
use Core\Repository\CountersOlmRepository as CounterRepository;

use Core\Service\BaseService;
use Exception;
use Modules\Book\Model\DM_PHIEU_CHUYEN_BAN_THAO;
use Modules\Book\Object\FilterPhieuChuyenBanThao;

class PhieuChuyenBanThaoServiceImpl extends BaseService implements PhieuChuyenBanThaoService
{
    /** @var PhieuChuyenBanThaoRepository */
    protected $baseRepo;

    public function __construct(PhieuChuyenBanThaoRepository $baseRepo) {
        parent::__construct($baseRepo);
    }

    public function getPaginate(FilterPhieuChuyenBanThao $filter, string $page = 'page-1') : array {
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

    public function getList(FilterPhieuChuyenBanThao $filter) {
        $result = $this->baseRepo->findAllWithFilter($filter);
        return $result;
    }

    public function store(array $data) : DM_PHIEU_CHUYEN_BAN_THAO {
        if(data_get($data, "id", 0) != 0) {
            /** @var DM_PHIEU_CHUYEN_BAN_THAO $phieuChuyenBanThao */
            $phieuChuyenBanThao = $this->baseRepo->get($data["id"]);
            if($phieuChuyenBanThao) {
                $phieuChuyenBanThao->update($data);
                return $phieuChuyenBanThao;
            }
        }
        $phieuChuyenBanThao = $this->baseRepo->create($data);
        if(!$phieuChuyenBanThao){
            throw new Exception("Có lỗi xảy ra, vui lòng thử lại");
        }
        return $phieuChuyenBanThao;
    }

    public function delete(int $id) : bool {
        $phieuChuyenBanThao = $this->baseRepo->get($id);
        if(!$phieuChuyenBanThao){
            throw new Exception("Phiếu chuyển bản thảo không tồn tại");
        }
        $phieuChuyenBanThao->IsDeleted = true;
        if(!$phieuChuyenBanThao->save()){
            throw new Exception("Có lỗi xảy ra, vui lòng thử lại");
        }
        return true;
    }

}
