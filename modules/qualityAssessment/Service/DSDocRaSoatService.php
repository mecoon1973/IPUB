<?php
namespace Modules\QualityAssessment\Service;

use Core\Service\IBaseService;
use Modules\QualityAssessment\Model\DM_DSDocRaSoat;
use Modules\QualityAssessment\Object\FilterDSDocRaSoat;

/**
 * @extends IBaseService<DM_DSDocRaSoat>
 */
interface DSDocRaSoatService extends IBaseService {
    public function getPaginate(FilterDSDocRaSoat $filter, string $page = 'page-1'): array;
    public function getList(FilterDSDocRaSoat $filter);
    public function store(array $data): DM_DSDocRaSoat;
    public function delete(int $id): bool;
}
