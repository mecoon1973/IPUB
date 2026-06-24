<?php
namespace Modules\Topic\Service;

use Core\Service\IBaseService;
use Modules\Topic\Model\DM_CONGDOAN;
use Modules\Topic\Object\FilterCongdoan;

/**
 * @extends IBaseService<DM_CONGDOAN>
 */
interface CongdoanService extends IBaseService {
    public function getPaginate(FilterCongdoan $filter, string $page): array;
    public function getList(FilterCongdoan $filter);
    public function store(array $data) : DM_CONGDOAN;
    public function delete(int $id): bool;
}
