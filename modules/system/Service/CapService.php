<?php
namespace Modules\System\Service;

use Core\Service\IBaseService;
use Modules\System\Model\DM_CAP;
use Modules\System\Object\FilterCap;

/**
 * @extends IBaseService<DM_CAP>
 */
interface CapService extends IBaseService {
    public function getPaginate(FilterCap $filter, string $page = 'page-1') : array;
    public function getList(FilterCap $filter);
    public function store(array $data) : DM_CAP;
    public function delete(int $id) : bool;
}
