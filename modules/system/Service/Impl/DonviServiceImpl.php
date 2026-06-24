<?php
namespace Modules\System\Service\Impl;

use Core\Service\BaseService;
use Exception;
use Modules\System\Model\DM_DONVI;
use Modules\System\Object\FilterDonvi;
use Modules\System\Repository\DonviRepository;
use Modules\System\Service\DonviService;

class DonviServiceImpl extends BaseService implements DonviService
{

    public function __construct(DonviRepository $baseRepo) {
        parent::__construct($baseRepo);
    }

    public function getAllDonvi(FilterDonvi $filter) {
        if($filter->IsDeleted === null) {
            $filter->IsDeleted = false;
        }
        $donvi = $this->baseRepo->findAllWithFilter($filter);
        return $donvi;
    }

    public function store(array $data): DM_DONVI {
        if(data_get($data, "id", 0) != 0) {
            /** @var DM_DONVI $donvi */
            $donvi = $this->baseRepo->get($data["id"]);
            if($donvi) {
                $donvi->update($data);
                return $donvi;
            }
        }

        $this->isExistDonVi($data["MaDonVi"]);

        /** @var DM_DONVI $donvi */
        $donvi = $this->counterRepo->retry("ipub_dm_donvi", function($id) use ($data) {
            $data["id"] = $id;
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

    public function delete(int $id) {
        $donvi = $this->baseRepo->get($id);
        if(!$donvi){
            throw new Exception("Đơn vị không tồn tại");
        }
        $donvi->IsDeleted = true;
        return $donvi->save();
    }

}
