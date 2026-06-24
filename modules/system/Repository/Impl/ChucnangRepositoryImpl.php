<?php

namespace Modules\System\Repository\Impl;

use Core\Repository\BaseRepository;
use Modules\System\Repository\ChucnangRepository;
use Modules\System\Model\DM_CHUCNANG;


class ChucnangRepositoryImpl extends BaseRepository implements ChucnangRepository {
    public function getModel() {
        return DM_CHUCNANG::class;
    }

}
