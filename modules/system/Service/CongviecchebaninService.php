<?php
namespace Modules\System\Service;

use Core\Service\IBaseService;
use Modules\System\Model\DM_CONG_VIEC_CHE_BAN_IN;
use Modules\System\Object\FilterCongviecchebanin;

/**
 * @extends IBaseService<DM_CONG_VIEC_CHE_BAN_IN>
 */
interface CongviecchebaninService extends IBaseService {
    public function getPaginate(FilterCongviecchebanin $filter, string $page): array;
    public function getList(FilterCongviecchebanin $filter);
    public function store(array $data) : DM_CONG_VIEC_CHE_BAN_IN;
    public function delete(int $id): bool;
}
