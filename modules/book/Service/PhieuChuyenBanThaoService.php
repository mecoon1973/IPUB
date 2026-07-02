<?php
namespace Modules\Book\Service;

use Core\Service\IBaseService;
use Modules\Book\Model\DM_PHIEU_CHUYEN_BAN_THAO;
use Modules\Book\Object\FilterPhieuChuyenBanThao;

/**
 * @extends IBaseService<DM_PHIEU_CHUYEN_BAN_THAO>
 */
interface PhieuChuyenBanThaoService extends IBaseService {

    public function getPaginate(FilterPhieuChuyenBanThao $filter, string $page = 'page-1') : array;
    public function getList(FilterPhieuChuyenBanThao $filter);
    public function store(array $data) : DM_PHIEU_CHUYEN_BAN_THAO;
    public function delete(int $id) : bool;
}
