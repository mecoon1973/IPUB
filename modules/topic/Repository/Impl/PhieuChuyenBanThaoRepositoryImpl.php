<?php

namespace Modules\Topic\Repository\Impl;

use Core\Repository\BaseRepository;
use Modules\Topic\Model\DM_PHIEU_CHUYEN_BAN_THAO;
use Modules\Topic\Repository\PhieuChuyenBanThaoRepository;

class PhieuChuyenBanThaoRepositoryImpl extends BaseRepository implements PhieuChuyenBanThaoRepository {
    public function getModel() {
        return DM_PHIEU_CHUYEN_BAN_THAO::class;
    }

}
