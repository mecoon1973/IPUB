<?php
namespace Modules\System\Service;

use Core\Service\IBaseService;
use Illuminate\Http\UploadedFile;
use Modules\System\Model\DM_TEMPLATE_EXPORT;
use Modules\System\Object\FilterTemplateExport;

/**
 * @extends IBaseService<DM_TEMPLATE_EXPORT>
 */
interface TemplateExportService extends IBaseService {
    public function getPaginate(FilterTemplateExport $filter, string $page): array;
    public function getList(FilterTemplateExport $filter);
    public function store(array $data) : DM_TEMPLATE_EXPORT;
    public function uploadTemplate(UploadedFile $file, string $key): string;
    public function delete(int $id): bool;
    public function getTemplateFileAbsolutePath(string $templatePathOrUrl): string;
}
