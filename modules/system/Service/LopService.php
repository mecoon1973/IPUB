<?php
namespace Modules\System\Service;

use Core\Service\IBaseService;
use Modules\System\Model\DM_LOP;
use Modules\System\Object\FilterLop;

/**
 * @extends IBaseService<DM_LOP>
 */
interface LopService extends IBaseService {
    public function getPaginateLop(FilterLop $filter, string $page): array;
    public function getListLop(FilterLop $filter);
    public function store(array $data) : DM_LOP;
    public function delete(int $id): bool;
}
