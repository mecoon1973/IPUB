<?php
namespace Modules\System\Service;

use Core\Service\IBaseService;
use Modules\System\Model\DM_TUSACH;
use Modules\System\Object\FilterTusach;

/**
 * @extends IBaseService<DM_TUSACH>
 */
interface TusachService extends IBaseService {
    public function getPaginate(FilterTusach $filter, string $page = 'page-1') : array;
    public function getList(FilterTusach $filter);
    public function store(array $data) : DM_TUSACH;
    public function delete(int $id) : bool;
}
