<?php

namespace Modules\System\Repository\Impl;

use Core\Repository\BaseRepository;
use Modules\System\Repository\MonhocRepository;
use Modules\System\Model\DM_MONHOC;


class MonhocRepositoryImpl extends BaseRepository implements MonhocRepository {
    public function getModel() {
        return DM_MONHOC::class;
    }

}
