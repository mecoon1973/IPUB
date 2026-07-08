<?php
namespace Modules\Topic\Service;

use Core\Service\IBaseService;
use Modules\Topic\Model\DM_PHIEU_CHUYEN_BAN_THAO;
use Modules\Topic\Object\FilterPhieuChuyenBanThao;

/**
 * @extends IBaseService<DM_PHIEU_CHUYEN_BAN_THAO>
 */
interface PhieuChuyenBanThaoService extends IBaseService {
    public function getPaginate(FilterPhieuChuyenBanThao $filter, string $page): array;
    public function getList(FilterPhieuChuyenBanThao $filter);
    public function store(array $data) : DM_PHIEU_CHUYEN_BAN_THAO;
    public function delete(int $id): bool;
}
