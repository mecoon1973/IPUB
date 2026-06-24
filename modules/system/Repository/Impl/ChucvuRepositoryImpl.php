<?php

namespace Modules\System\Repository\Impl;

use Core\Repository\BaseRepository;
use Modules\System\Repository\ChucvuRepository;
use Modules\System\Model\DM_CHUCVU;


class ChucvuRepositoryImpl extends BaseRepository implements ChucvuRepository {
    public function getModel() {
        return DM_CHUCVU::class;
    }

}
