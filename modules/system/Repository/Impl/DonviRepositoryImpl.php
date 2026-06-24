<?php

namespace Modules\System\Repository\Impl;

use Core\Repository\BaseRepository;
use Modules\System\Model\DM_DONVI;
use Modules\System\Repository\DonviRepository;

class DonviRepositoryImpl extends BaseRepository implements DonviRepository {
    public function getModel() {
        return DM_DONVI::class;
    }

}
