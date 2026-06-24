<?php
namespace Modules\Topic\Service;

use Core\Service\IBaseService;
use Modules\Topic\Model\CT_QD_In;
use Modules\Topic\Object\FilterCT_QD_In;

/**
 * @extends IBaseService<CT_QD_In>
 */
interface CT_QD_InService extends IBaseService {
    public function getPaginate(FilterCT_QD_In $filter, string $page = 'page-1'): array;

    public function getList(FilterCT_QD_In $filter);

    public function store(array $data): CT_QD_In;

    public function delete(int $id): bool;
}
