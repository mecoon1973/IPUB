<?php
namespace Modules\Book\Service;

use Core\Service\IBaseService;
use Modules\Book\Model\DM_SACH;
use Modules\Book\Object\FilterSach;

/**
 * @extends IBaseService<DM_SACH>
 */
interface SachService extends IBaseService {
    public function getPaginate(FilterSach $filter, string $page = 'page-1') : array;

    public function paginateWithConditions(array $conditions, string $page = 'page-1', int $limit = 100): array;

    public function getList(FilterSach $filter);
    public function store(array $data) : DM_SACH;
    public function delete(int $id) : bool;
}
