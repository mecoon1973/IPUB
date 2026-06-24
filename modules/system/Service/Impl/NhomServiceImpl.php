<?php
namespace Modules\System\Service\Impl;


use Modules\System\Service\NhomService;
use Modules\System\Repository\NhomRepository;

use Core\Service\BaseService;
use Core\Service\BaseConvertTool;

use Core\Object\Paginate;
use Exception;
use Modules\System\Model\DM_NHOM;
use Modules\System\Object\FilterNhom;
use Modules\System\Service\NhomQuyenService;
use Modules\User\Repository\Impl\UserRepositoryImpl;
use Modules\User\Service\UserService;

class NhomServiceImpl extends BaseService implements NhomService
{
    use BaseConvertTool;
    /** @var NhomRepository */
    protected $baseRepo;

    public function __construct(NhomRepository $baseRepo) {
        parent::__construct($baseRepo);
    }

    public function getAllNhom(FilterNhom $filter) {
        if($filter->IsDeleted === null) {
            $filter->IsDeleted = false;
        }
        $nhom = $this->baseRepo->findAllWithFilter($filter);
        return $nhom;
    }

    public function getListNhom(FilterNhom $filter, string $page) {
        $conditions = $filter->buildConditions();
        $paginate = new Paginate([
            "conditions" => $conditions,
            "limit" => 15,
            "page" => $page
        ]);
        $result = $this->pagination($paginate);
        /** @var UserService $userService */
        $userService = app(UserService::class);
        $mapCountCanboInNhom = $userService->getCountCanboInNhom($result->list);
        foreach($result->list as $item){
            $item->countCanbo = $mapCountCanboInNhom[$item->_id] ?? 0;
        }
        return [
            "listResult" => $result->list,
            "pagiInfo" => $result->pagi_info
        ];
    }

    public function store(array $data): DM_NHOM {
        if(data_get($data, "id", 0) != 0) {
            /** @var DM_NHOM $nhom */
            $nhom = $this->baseRepo->get($data["id"]);
            if($nhom) {
                $nhom->update($data);
                return $nhom;
            }
        }
        $this->isExistNhom("MaNhomNSD", $data["MaNhomNSD"]);
        $nhom = $this->baseRepo->create($data);
        if(!$nhom){
            throw new Exception("Có lỗi xảy ra, vui lòng thử lại");
        }
        return $nhom;
    }

    public function addCanboToNhom(int $id, array $listIdUser) {
        $nhom = $this->baseRepo->get($id);
        if(!$nhom){
            throw new Exception("Nhóm không tồn tại");
        }
        if (empty($listIdUser)) {
            return true;
        }

        // Thêm 1 lần cho nhiều user: nhom_ids = nhom_ids U { $id } (không trùng).
        // Dùng $addToSet + $each để chống trùng và thao tác batch.
        /** @var UserRepositoryImpl $userRepo */
        $userRepo = app(UserRepositoryImpl::class);
        $userRepo->addToSet(
            ['_id' => ['$in' => $listIdUser]],
            'nhom_ids',
            $id
        );
        return true;
    }

    public function delete(int $id) {
        $nhom = $this->baseRepo->get($id);
        if(!$nhom){
            throw new Exception("Nhóm không tồn tại");
        }
        $nhom->IsDeleted = true;
        $nhom->save();
        return true;
    }

    private function isExistNhom(string $type, string $value) {
        $nhom = $this->baseRepo->findOne([$type => $value]);
        if($nhom){
            throw new Exception("Mã nhóm đã tồn tại");
        }
    }

    public function convertDataNhomQuyen(): void {
        dump("convertDataNhomQuyen: START");
        $success = 0;
        $failed = 0;

        $this->baseConvert("convertDataNhomQuyen", $this->baseRepo, [], function($nhom) use(&$success, &$failed) {
            /** @var NhomQuyenService $nhomQuyenService */
            $nhomQuyenService = app(NhomQuyenService::class);
            $listNhomQuyen = $nhomQuyenService->findAll(["ID_NHOM" => $nhom->_id, "IsDeleted" => false]);
            $listIdQuyen = [];
            foreach($listNhomQuyen as $nhomQuyen){
                $listIdQuyen[] = $nhomQuyen->ID_QUYEN;
            }
            $nhom->listIdQuyen = $listIdQuyen;
            $result = $nhom->save();
            if($result){
                $success++;
            } else {
                $failed++;
            }
        });
        dump("convertDataNhomQuyen: FINISH");
        dump("Success: {$success}");
        dump("Failed: {$failed}");
        dump("Total: " . ($success + $failed));
    }
}
