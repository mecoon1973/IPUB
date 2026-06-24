<?php

namespace Modules\Book\Repository\Impl;

use Core\Repository\BaseRepository;
use Modules\Book\Repository\SachRepository;
use Modules\Book\Model\DM_SACH;


class SachRepositoryImpl extends BaseRepository implements SachRepository {
    public function getModel() {
        return DM_SACH::class;
    }

}
