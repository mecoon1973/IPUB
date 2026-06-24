<?php

namespace Modules\System\Repository\Impl;

use Core\Repository\BaseRepository;
use Modules\System\Repository\ChuyenmonRepository;
use Modules\System\Model\DM_CHUYENMON;


class ChuyenmonRepositoryImpl extends BaseRepository implements ChuyenmonRepository {
    public function getModel() {
        return DM_CHUYENMON::class;
    }

}
