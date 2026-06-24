<?php

namespace Modules\Topic\Repository\Impl;

use Core\Repository\BaseRepository;
use Modules\Topic\Repository\PhieuDkDetaiRepository;
use Modules\Topic\Model\PHIEU_DK_DETAI;


class PhieuDkDetaiRepositoryImpl extends BaseRepository implements PhieuDkDetaiRepository {
    public function getModel() {
        return PHIEU_DK_DETAI::class;
    }

}
