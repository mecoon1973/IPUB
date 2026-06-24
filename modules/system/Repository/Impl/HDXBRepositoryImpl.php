<?php

namespace Modules\System\Repository\Impl;

use Core\Repository\BaseRepository;
use Modules\System\Model\DM_HDXB;
use Modules\System\Repository\HDXBRepository;

class HDXBRepositoryImpl extends BaseRepository implements HDXBRepository {
    public function getModel() {
        return DM_HDXB::class;
    }

}
