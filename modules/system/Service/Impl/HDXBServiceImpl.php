<?php
namespace Modules\System\Service\Impl;

use Core\Service\BaseService;
use Exception;
use Modules\System\Model\DM_HDXB;
use Modules\System\Object\FilterHDXB;
use Modules\System\Repository\HDXBRepository;
use Modules\System\Service\HDXBService;

class HDXBServiceImpl extends BaseService implements HDXBService
{

    public function __construct(HDXBRepository $baseRepo) {
        parent::__construct($baseRepo);
    }

    public function getAllHDXB(FilterHDXB $filter) {
        if($filter->IsDeleted === null) {
            $filter->IsDeleted = false;
        }
        $donvi = $this->baseRepo->findAllWithFilter($filter);
        return $donvi;
    }

    public function store(array $data): DM_HDXB {
        // dump($data);
        if(data_get($data, "id", 0) != 0) {
            /** @var DM_HDXB $donvi */
            $donvi = $this->baseRepo->get($data["id"]);
            if($donvi) {
                $donvi->update($data);
                return $donvi;
            }
        }

        $this->isExistDonVi($data["MaDonVi"]);

        /** @var DM_HDXB $donvi */
        $donvi = $this->counterRepo->retry("ipub_dm_donvi", function($id) use ($data) {
            $data["_id"] = $id;
            return $this->baseRepo->create($data);
        });

        if(!$donvi){
            throw new Exception("Có lỗi xảy ra, vui lòng thử lại");
        }
        // dd($donvi);
        return $donvi;
    }

    private function isExistDonVi(string $maDonVi) {
        $donvi = $this->baseRepo->findOne(["MaDonVi" => $maDonVi]);
        if($donvi){
            throw new Exception("Mã đơn vị đã tồn tại");
        }
    }

}
