<?php
namespace Modules\System\Service;

use Core\Service\IBaseService;
use Modules\System\Object\FilterQuyen;
use Modules\System\Model\DM_QUYEN;

interface QuyenService extends IBaseService {
    /** api lấy danh sách đơn vị
    * @return array<DM_QUYEN>
    */
    public function getAllQuyen(FilterQuyen $filter);

    /** api thêm mới hoặc cập nhật đơn vị
    * @param array $data
    * @return DM_QUYEN|null
    */
    public function store(array $data): ?DM_QUYEN;

    /** api xóa quyền
    * @param int $id
    * @return bool
    */
    public function delete(int $id): bool;
    public function convertDataFunctionQuyen(): void;
}
