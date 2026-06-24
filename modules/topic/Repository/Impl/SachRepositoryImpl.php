<?php

namespace Modules\Topic\Repository\Impl;

use Core\Repository\BaseRepository;
use Modules\Topic\Repository\SachRepository;
use Modules\Topic\Model\DM_SACH;


class SachRepositoryImpl extends BaseRepository implements SachRepository {
    public function getModel() {
        return DM_SACH::class;
    }

}
