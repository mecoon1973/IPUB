<?php

namespace Modules\System\Repository\Impl;

use Core\Repository\BaseRepository;
use Modules\System\Repository\BosachRepository;
use Modules\System\Model\DM_BOSACH;


class BosachRepositoryImpl extends BaseRepository implements BosachRepository {
    public function getModel() {
        return DM_BOSACH::class;
    }

}
