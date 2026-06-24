<?php
namespace Modules\Topic\Service;

use Core\Service\IBaseService;
use Modules\Topic\Model\CT_Detai_Congdoan;
use Modules\Topic\Object\FilterCT_Detai_Congdoan;

/**
 * @extends IBaseService<CT_Detai_Congdoan>
 */
interface CT_Detai_CongDoanService extends IBaseService {

    public function getPaginate(FilterCT_Detai_Congdoan $filter, string $page = 'page-1'): array;

    public function getList(FilterCT_Detai_Congdoan $filter);

    public function store(array $data): CT_Detai_Congdoan;

    public function delete(int $id): bool;
}
