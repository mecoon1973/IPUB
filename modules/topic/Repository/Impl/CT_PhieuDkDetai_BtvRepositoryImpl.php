<?php

namespace Modules\Topic\Repository\Impl;

use Core\Repository\BaseRepository;
use Modules\Topic\Repository\CT_PhieuDkDetai_BtvRepository;
use Modules\Topic\Model\CT_PhieuDkDetai_BTV;


class CT_PhieuDkDetai_BtvRepositoryImpl extends BaseRepository implements CT_PhieuDkDetai_BtvRepository {
    public function getModel() {
        return CT_PhieuDkDetai_BTV::class;
    }

}
