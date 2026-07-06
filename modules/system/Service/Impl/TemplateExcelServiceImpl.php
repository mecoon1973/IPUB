<?php
namespace Modules\System\Service\Impl;

use Core\Object\Paginate;

use Modules\System\Service\TemplateExcelService;
use Modules\System\Repository\TemplateExcelRepository;

use Core\Service\BaseService;
use Exception;
use Modules\System\Model\DM_TEMPLATE_EXCEL;
use Modules\System\Object\FilterTemplateExcel;

class TemplateExcelServiceImpl extends BaseService implements TemplateExcelService
{
    /** @var TemplateExcelRepository */
    protected $baseRepo;

    public function __construct(TemplateExcelRepository $baseRepo) {
        parent::__construct($baseRepo);
    }

    public function getPaginate(FilterTemplateExcel $filter, string $page): array {
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
    public function getList(FilterTemplateExcel $filter) {
        $result = $this->baseRepo->findAllWithFilter($filter);
        return $result;
    }

    public function store(array $data): DM_TEMPLATE_EXCEL {
        if(data_get($data, "id", 0) != 0) {
            /** @var DM_TEMPLATE_EXCEL $templateExcel */
            $templateExcel = $this->baseRepo->get($data["id"]);
            if($templateExcel) {
                $templateExcel->update($data);
                return $templateExcel;
            }
        }
        $this->isExistTemplateExcel($data["key"]);
        /** @var DM_TEMPLATE_EXCEL $templateExcel */
        $templateExcel = $this->baseRepo->create($data);
        if(!$templateExcel){
            throw new Exception("Có lỗi xảy ra, vui lòng thử lại");
        }
        return $templateExcel;
    }

    private function isExistTemplateExcel(string $key) {
        $templateExcel = $this->baseRepo->findOne(["key" => $key]);
        if($templateExcel){
            throw new Exception("Mã template excel đã tồn tại");
        }
    }

    public function delete(int $id): bool {
        $templateExcel = $this->baseRepo->get($id);
        if(!$templateExcel){
            throw new Exception("Template excel không tồn tại");
        }
        $templateExcel->IsDeleted = true;
        return $templateExcel->save();
    }

}
