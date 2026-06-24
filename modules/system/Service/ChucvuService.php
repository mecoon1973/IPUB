<?php
namespace Modules\System\Service;

use Core\Service\IBaseService;
use Modules\System\Model\DM_CHUCVU;
use Modules\System\Object\FilterChucvu;

/**
 * @extends IBaseService<DM_CHUCVU>
 */
interface ChucvuService extends IBaseService {

    public function getPaginate(FilterChucvu $filter, string $page = 'page-1'): array;
    public function getList(FilterChucvu $filter);
    public function store(array $data): DM_CHUCVU;
    public function delete(int $id): bool;
}
