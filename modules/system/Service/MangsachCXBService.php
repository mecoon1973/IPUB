<?php
namespace Modules\System\Service;

use Core\Service\IBaseService;
use Modules\System\Model\DM_MANGSACH_CXB;
use Modules\System\Object\FilterMangsachCXB;

/**
 * @extends IBaseService<DM_MANGSACH_CXB>
 */
interface MangsachCXBService extends IBaseService {
    public function getPaginate(FilterMangsachCXB $filter, string $page = 'page-1') : array;
    public function getList(FilterMangsachCXB $filter);
    public function store(array $data) : DM_MANGSACH_CXB;
    public function delete(int $id) : bool;
}
