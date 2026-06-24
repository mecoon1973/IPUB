<?php
namespace Modules\System\Service;

use Core\Service\IBaseService;
use Modules\System\Model\Merge_nhom_canbo;

/**
 * @extends IBaseService<Merge_nhom_canbo>
 */
interface NhomCanboService extends IBaseService {

    /**
     * gộp bảng ipub_nhom_canbo -> canbo
     * tìm các cán bộ trong nhóm và gộp các id nhóm đó chuyển thành 1 field là nhom_ids là array trong Canbo
     */
    public function convertDataNhomCanboToNewSystem();
}
