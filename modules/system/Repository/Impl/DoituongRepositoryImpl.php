<?php

namespace Modules\System\Repository\Impl;

use Core\Repository\BaseRepository;
use Modules\System\Repository\DoituongRepository;
use Modules\System\Model\DM_DOITUONG;


class DoituongRepositoryImpl extends BaseRepository implements DoituongRepository {
    public function getModel() {
        return DM_DOITUONG::class;
    }

}
