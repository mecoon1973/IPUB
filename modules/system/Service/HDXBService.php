<?php
namespace Modules\System\Service;

use Core\Service\IBaseService;
use Modules\System\Object\FilterDonvi;
use Modules\System\Model\DM_DONVI;
use Modules\System\Model\DM_HDXB;
use Modules\System\Object\FilterHDXB;

interface HDXBService extends IBaseService {
    /** api lấy danh sách đơn vị
    * @return array<DM_HDXB>
    */
    public function getAllHDXB(FilterHDXB $filter);

    /** api thêm mới hoặc cập nhật đơn vị
    * @param array $data
    * @return DM_HDXB
    */
    public function store(array $data);
}
