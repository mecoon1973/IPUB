<?php

namespace Modules\System\Repository\Impl;

use Core\Repository\BaseRepository;
use Modules\System\Repository\LopRepository;
use Modules\System\Model\DM_LOP;


class LopRepositoryImpl extends BaseRepository implements LopRepository {
    public function getModel() {
        return DM_LOP::class;
    }

}
