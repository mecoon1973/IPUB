<?php
namespace Modules\System\Service;

use Core\Service\IBaseService;
use Modules\System\Model\DM_CHUCNANG;
use Modules\System\Object\FilterChucnang;

/**
 * @extends IBaseService<DM_CHUCNANG>
 */
interface ChucnangService extends IBaseService {

    /** api lấy danh sách chức năng
    * @param FilterChucnang $filter
    * @return array<DM_CHUCNANG>
    */
    public function getAllChucnang(FilterChucnang $filter);

    /** api thêm mới hoặc cập nhật chức năng
    * @param array $data
    * @return DM_CHUCNANG
    */
    public function store(array $data): DM_CHUCNANG;

    /** api xóa chức năng
    * @param int $id
    * @return bool
    */
    public function delete(int $id): bool;

    public function convertDataChucnang(): void;

    /**
     * lấy dữ liệu ở trên menu
     * */
    public function getDataTreeHearder();
}
