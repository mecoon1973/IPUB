<?php
namespace Modules\System\Service;

use Core\Service\IBaseService;
use Modules\System\Model\DM_MANGSACH;
use Modules\System\Object\FilterMangsach;

/**
 * @extends IBaseService<DM_MANGSACH>
 */
interface MangsachService extends IBaseService {
    public function getPaginate(FilterMangsach $filter, string $page = 'page-1') : array;
    public function getList(FilterMangsach $filter);
    public function store(array $data) : DM_MANGSACH;
    public function delete(int $id) : bool;
}
