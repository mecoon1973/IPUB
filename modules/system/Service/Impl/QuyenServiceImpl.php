<?php
namespace Modules\System\Service\Impl;

use Core\Service\BaseService;
use Core\Service\BaseConvertTool;
use Exception;
use Modules\System\Model\DM_QUYEN;
use Modules\System\Object\FilterQuyen;
use Modules\System\Repository\QuyenRepository;
use Modules\System\Service\FunctionQuyenService;
use Modules\System\Service\QuyenService;

class QuyenServiceImpl extends BaseService implements QuyenService
{
    use BaseConvertTool;

    public function __construct(QuyenRepository $baseRepo) {
        parent::__construct($baseRepo);
    }

    public function getAllQuyen(FilterQuyen $filter) {
        if($filter->IsDeleted === null) {
            $filter->IsDeleted = false;
        }
        $result = $this->baseRepo->findAllWithFilter($filter);
        return $result;
    }

    public function store(array $data): DM_QUYEN {
        if(data_get($data, "id", 0) != 0) {
            /** @var DM_QUYEN $quyen */
            $quyen = $this->baseRepo->get($data["id"]);
            if($quyen) {
                $quyen->update($data);
                return $quyen;
            }
        }

        $this->isExistQuyen($data["MaQuyen"]);

        /** @var DM_QUYEN $quyen */
        $quyen = $this->counterRepo->retry("ipub_dm_quyen", function($id) use ($data) {
            $data["id"] = $id;
            return $this->baseRepo->create($data);
        });

        if(!$quyen){
            throw new Exception("Có lỗi xảy ra, vui lòng thử lại");
        }
        // dd($quyen);
        return $quyen;
    }

    private function isExistQuyen(string $maQuyen) {
        $quyen = $this->baseRepo->findOne(["MaQuyen" => $maQuyen]);
        if($quyen){
            throw new Exception("Mã quyền đã tồn tại");
        }
    }

    public function delete(int $id): bool {
        $quyen = $this->baseRepo->get($id);
        if(!$quyen){
            throw new Exception("Quyền không tồn tại");
        }
        $quyen->IsDeleted = true;
        if(!$quyen->save()){
            throw new Exception("Có lỗi xảy ra, vui lòng thử lại");
        }
        return true;
    }

    public function convertDataFunctionQuyen(): void {
        dump("convertDataFunctionQuyen: START");
        $success = 0;
        $failed = 0;
        $this->baseConvert("convertDataFunctionQuyen", $this->baseRepo, [], function($quyen) use (&$success, &$failed) {
            /** @var FunctionQuyenService $functionQuyenService */
            $functionQuyenService = app(FunctionQuyenService::class);
            $listFunctionQuyen = $functionQuyenService->findAll(["QuyenID" => $quyen->_id, "IsDeleted" => false]);
            $listIdFunctions = [];
            foreach ($listFunctionQuyen as $functionQuyen) {
                $listIdFunctions[] = $functionQuyen->FunctionID;
            }
            $quyen->listIdFunctions = $listIdFunctions;
            $result = false;
            if(count($listIdFunctions) > 0){
                $result = $quyen->save();
            }
            if($result){
                $success++;
            } else {
                $failed++;
            }
        });
        dump("convertDataFunctionQuyen: FINISH");
        dump("Success: {$success}");
        dump("Failed: {$failed}");
        dump("Total: " . ($success + $failed));
    }

}
