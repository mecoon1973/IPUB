<?php
namespace Modules\System\Service;

use Core\Service\IBaseService;
use Modules\System\Model\DM_CONG_VIEC_THIET_KE;
use Modules\System\Object\FilterCongviecthietke;

/**
 * @extends IBaseService<DM_CONG_VIEC_THIET_KE>
 */
interface CongviecthietkeService extends IBaseService {
    public function getPaginate(FilterCongviecthietke $filter, string $page): array;
    public function getList(FilterCongviecthietke $filter);
    public function store(array $data) : DM_CONG_VIEC_THIET_KE;
    public function delete(int $id): bool;
}
