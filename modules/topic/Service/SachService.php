<?php
namespace Modules\Topic\Service;

use Core\Service\IBaseService;
use Modules\Topic\Model\DM_SACH;
use Modules\Topic\Object\FilterSach;

/**
 * @extends IBaseService<DM_SACH>
 */
interface SachService extends IBaseService {
    public function getPaginate(FilterSach $filter, string $page = 'page-1'): array;

    public function getList(FilterSach $filter);

    public function store(array $data): DM_SACH;

    public function delete(int $id): bool;
}
