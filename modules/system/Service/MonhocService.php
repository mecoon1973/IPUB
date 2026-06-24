<?php
namespace Modules\System\Service;

use Core\Service\IBaseService;
use Modules\System\Model\DM_MONHOC;
use Modules\System\Object\FilterMonhoc;

/**
 * @extends IBaseService<DM_MONHOC>
 */
interface MonhocService extends IBaseService {

    public function getPaginateMonhoc(FilterMonhoc $filter, string $page = 'page-1') : array;
    public function getListMonhoc(FilterMonhoc $filter);
    public function store(array $data) : DM_MONHOC;
    public function delete(int $id) : bool;
}
