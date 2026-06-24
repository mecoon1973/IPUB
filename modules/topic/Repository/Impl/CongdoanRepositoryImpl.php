<?php

namespace Modules\Topic\Repository\Impl;

use Core\Repository\BaseRepository;
use Modules\Topic\Repository\CongdoanRepository;
use Modules\Topic\Model\DM_CONGDOAN;


class CongdoanRepositoryImpl extends BaseRepository implements CongdoanRepository {
    public function getModel() {
        return DM_CONGDOAN::class;
    }

}
