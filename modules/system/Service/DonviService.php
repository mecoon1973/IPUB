<?php
namespace Modules\System\Service;

use Core\Service\IBaseService;
use Modules\System\Object\FilterDonvi;
use Modules\System\Model\DM_DONVI;

interface DonviService extends IBaseService {
    /** api lấy danh sách đơn vị
    * @return array<DM_DONVI>
    */
    public function getAllDonvi(FilterDonvi $filter);

    /** api thêm mới hoặc cập nhật đơn vị
    * @param DM_DONVI $donvi
    * @return DM_DONVI
    */
    public function store(array $data);

    /** api xóa đơn vị
    * @param int $id
    * @return void
    */
    public function delete(int $id);
}
