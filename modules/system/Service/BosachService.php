<?php
namespace Modules\System\Service;

use Core\Service\IBaseService;
use Modules\System\Model\DM_BOSACH;
use Modules\System\Object\FilterBosach;

/**
 * @extends IBaseService<DM_BOSACH>
 */
interface BosachService extends IBaseService {
    public function getPaginate(FilterBosach $filter, string $page = 'page-1') : array;
    public function getList(FilterBosach $filter);
    public function store(array $data) : DM_BOSACH;
    public function delete(int $id) : bool;
}
