<?php
namespace Modules\System\Service\Impl;

use Illuminate\Support\Facades\Auth;

use Modules\System\Service\ChucnangService;
use Modules\System\Repository\ChucnangRepository;
use Core\Repository\CountersOlmRepository as CounterRepository;
use Core\Service\BaseConvertTool;
use Core\Service\BaseService;
use Exception;
use Modules\System\Model\DM_CHUCNANG;
use Modules\System\Object\FilterChucnang;
use Throwable;

class ChucnangServiceImpl extends BaseService implements ChucnangService
{
    use BaseConvertTool;
    /** @var ChunangRepository */
    protected $baseRepo;

    public function __construct(ChucnangRepository $baseRepo) {
        parent::__construct($baseRepo);
    }

    public function getAllChucnang(FilterChucnang $filter) {
        if($filter->Deleted === null) {
            $filter->Deleted = false;
        }
        $result = $this->baseRepo->findAllWithFilter($filter);
        return $result;
    }
    public function store(array $data): DM_CHUCNANG {
        if(data_get($data, "id", 0) != 0) {
            /** @var DM_CHUCNANG $chucnang */
            $chucnang = $this->baseRepo->get($data["id"]);
            if(data_get($data, "ParentID", 0) != 0) {
                $data["Crumb"] = $this->renderCrumb(data_get($data, "ParentID", 0), data_get($data, "Title", ""));
            }
            if($chucnang) {
                $chucnang->update($data);
                return $chucnang;
            }
        }

        $this->isExistChucnang("Code", $data["Code"]);

        if(data_get($data, "ParentID", 0) != 0) {
            $data["Crumb"] = $this->renderCrumb(data_get($data, "ParentID", 0), data_get($data, "Title", ""));
        }
        /** @var DM_CHUCNANG $chucnang */
        $chucnang = $this->baseRepo->create($data);
        if(!$chucnang) {
            throw new Exception("Có lỗi xảy ra, vui lòng thử lại");
        }
        return $chucnang;
    }

    public function delete(int $id): bool {
        /** @var DM_CHUCNANG $chucnang */
        $chucnang = $this->baseRepo->get($id);
        if($chucnang) {
            $chucnang->delete();
            return true;
        }
        return false;
    }

    private function isExistChucnang(string $type, string $value) {
        $chucnang = $this->baseRepo->findOne([$type => $value]);
        if($chucnang) {
            throw new Exception("{$type} đã tồn tại");
        }
    }

    public function convertDataChucnang(): void {
        $this->baseConvert("convertDataChucnang", $this->baseRepo, [
        ], function($chucnang) {
            $chucnang->ThuTu = $chucnang->Order;
            $chucnang->save();
        });
    }

    public function renderCrumb(int $parentId, string $crumb = ""): string {
        $parent = $this->baseRepo->get($parentId);
        if(!$parent) {
            return "";
        }
        while($parent) {
            $crumb = $parent->Title . " > " . $crumb;
            $parent = $parent->Parent;
        }
        return $crumb;
    }

    public function getDataTreeHearder() {
        $listChucnang = $this->baseRepo->findAll(['OnMenu' => true, 'Deleted' => false], [], ['_id', 'Title', 'ParentID', 'Href', "Crumb", "ThuTu"])->toArray();
        $listChucnang = convert_array_to_hashtable($listChucnang, 'ParentID', [], true);
        return $listChucnang;
    }

}
