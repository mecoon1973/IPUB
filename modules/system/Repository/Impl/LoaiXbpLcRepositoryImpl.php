<?php

namespace Modules\System\Repository\Impl;

use Core\Repository\BaseRepository;
use Modules\System\Repository\LoaiXbpLcRepository;
use Modules\System\Model\DM_LOAI_XBP_LC;


class LoaiXbpLcRepositoryImpl extends BaseRepository implements LoaiXbpLcRepository {
    public function getModel() {
        return DM_LOAI_XBP_LC::class;
    }

}
