<?php

namespace Modules\System\Repository\Impl;

use Core\Repository\BaseRepository;
use Modules\System\Repository\CongviecthietkeRepository;
use Modules\System\Model\DM_CONG_VIEC_THIET_KE;


class CongviecthietkeRepositoryImpl extends BaseRepository implements CongviecthietkeRepository {
    public function getModel() {
        return DM_CONG_VIEC_THIET_KE::class;
    }

}
