<?php
namespace Modules\System\Service;

use Core\Service\IBaseService;
use Modules\System\Model\DM_TRANG_THAI;
use Modules\System\Object\FilterTrangThai;

/**
 * @extends IBaseService<DM_TRANG_THAI>
 */
interface TrangThaiService extends IBaseService {
    public function getPaginate(FilterTrangThai $filter, string $page = 'page-1') : array;
    public function getList(FilterTrangThai $filter);
    /**
     * Lấy danh sách trạng thái từ ipub_dm_trangthai, sắp xếp theo Order.
     *
     * @return DM_TRANG_THAI[]
     */
    public function getListOrdered(?FilterTrangThai $filter = null);
    /**
     * Map MaTrangThai => TenTrangThai từ database.
     */
    public function getMapTrangThai(?FilterTrangThai $filter = null): array;
    public function store(array $data) : DM_TRANG_THAI;
    public function delete(int $id) : bool;
}
