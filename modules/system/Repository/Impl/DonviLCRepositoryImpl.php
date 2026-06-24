<?php

namespace Modules\System\Repository\Impl;

use Core\Repository\BaseRepository;
use Modules\System\Repository\DonviLCRepository;
use Modules\System\Model\DM_DONVILC;


class DonviLCRepositoryImpl extends BaseRepository implements DonviLCRepository {
    public function getModel() {
        return DM_DONVILC::class;
    }

}
