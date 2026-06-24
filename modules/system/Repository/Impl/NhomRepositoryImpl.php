<?php

namespace Modules\System\Repository\Impl;

use Core\Repository\BaseRepository;
use Modules\System\Repository\NhomRepository;
use Modules\System\Model\DM_NHOM;

class NhomRepositoryImpl extends BaseRepository implements NhomRepository {
    public function getModel() {
        return DM_NHOM::class;
    }

}
