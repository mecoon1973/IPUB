<?php
namespace Modules\System\Service;

use Core\Service\IBaseService;
use Modules\System\Model\DM_CHUYENMON;
use Modules\System\Object\FilterChuyenmon;

/**
 * @extends IBaseService<DM_CHUYENMON>
 */
interface ChuyenmonService extends IBaseService {
    public function getPaginate(FilterChuyenmon $filter, string $page = 'page-1') : array;
    public function getList(FilterChuyenmon $filter);
    public function store(array $data) : DM_CHUYENMON;
    public function delete(int $id) : bool;
}
