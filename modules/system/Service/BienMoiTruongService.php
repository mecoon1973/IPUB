<?php
namespace Modules\System\Service;

use Core\Service\IBaseService;
use Modules\System\Model\DM_BIEN_MOI_TRUONG;
use Modules\System\Object\FilterBienMoiTruong;

/**
 * @extends IBaseService<DM_BIEN_MOI_TRUONG>
 */
interface BienMoiTruongService extends IBaseService {
    public function getPaginate(FilterBienMoiTruong $filter, string $page = 'page-1') : array;
    public function getList(FilterBienMoiTruong $filter);
    public function store(array $data) : DM_BIEN_MOI_TRUONG;
    public function delete(int $id) : bool;
}
