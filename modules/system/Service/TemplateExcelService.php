<?php
namespace Modules\System\Service;

use Core\Service\IBaseService;
use Modules\System\Model\DM_TEMPLATE_EXCEL;
use Modules\System\Object\FilterTemplateExcel;

/**
 * @extends IBaseService<DM_TEMPLATE_EXCEL>
 */
interface TemplateExcelService extends IBaseService {
    public function getPaginate(FilterTemplateExcel $filter, string $page): array;
    public function getList(FilterTemplateExcel $filter);
    public function store(array $data) : DM_TEMPLATE_EXCEL;
    public function delete(int $id): bool;
}
