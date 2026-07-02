<?php

namespace Modules\Book\Repository\Impl;

use Core\Repository\BaseRepository;
use Modules\Book\Repository\PhieuChuyenBanThaoRepository;
use Modules\Book\Model\DM_PHIEU_CHUYEN_BAN_THAO;


class PhieuChuyenBanThaoRepositoryImpl extends BaseRepository implements PhieuChuyenBanThaoRepository {
    public function getModel() {
        return DM_PHIEU_CHUYEN_BAN_THAO::class;
    }

}
