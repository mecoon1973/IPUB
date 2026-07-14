<?php

namespace Modules\System\Repository\Impl;

use Core\Repository\BaseRepository;
use Modules\System\Repository\CapRepository;
use Modules\System\Model\DM_CAP;


class CapRepositoryImpl extends BaseRepository implements CapRepository {
    public function getModel() {
        return DM_CAP::class;
    }

}
