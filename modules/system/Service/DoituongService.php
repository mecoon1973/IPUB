<?php
namespace Modules\System\Service;

use Core\Service\IBaseService;
use Modules\System\Model\DM_DOITUONG;
use Modules\System\Object\FilterDoituong;

/**
 * @extends IBaseService<DM_DOITUONG>
 */
interface DoituongService extends IBaseService {
    public function getPaginate(FilterDoituong $filter, string $page = 'page-1'): array;
    public function getList(FilterDoituong $filter);
    public function store(array $data): DM_DOITUONG;
    public function delete(int $id): bool;
}
