<?php

namespace Modules\System\Repository\Impl;

use Core\Repository\BaseRepository;
use Modules\System\Repository\SystemLogRepository;
use Modules\System\Model\SYSTEMLOG;


class SystemLogRepositoryImpl extends BaseRepository implements SystemLogRepository {
    public function getModel() {
        return SYSTEMLOG::class;
    }

}
