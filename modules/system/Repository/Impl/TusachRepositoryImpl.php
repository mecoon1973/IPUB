<?php

namespace Modules\System\Repository\Impl;

use Core\Repository\BaseRepository;
use Modules\System\Repository\TusachRepository;
use Modules\System\Model\DM_TUSACH;


class TusachRepositoryImpl extends BaseRepository implements TusachRepository {
    public function getModel() {
        return DM_TUSACH::class;
    }

}
