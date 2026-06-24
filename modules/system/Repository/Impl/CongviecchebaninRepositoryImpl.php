<?php

namespace Modules\System\Repository\Impl;

use Core\Repository\BaseRepository;
use Modules\System\Repository\CongviecchebaninRepository;
use Modules\System\Model\DM_CONG_VIEC_CHE_BAN_IN;


class CongviecchebaninRepositoryImpl extends BaseRepository implements CongviecchebaninRepository {
    public function getModel() {
        return DM_CONG_VIEC_CHE_BAN_IN::class;
    }

}
