<?php

namespace Modules\System\Repository\Impl;

use Core\Repository\BaseRepository;
use Modules\System\Repository\TrangThaiRepository;
use Modules\System\Model\DM_TRANG_THAI;


class TrangThaiRepositoryImpl extends BaseRepository implements TrangThaiRepository {
    public function getModel() {
        return DM_TRANG_THAI::class;
    }

}
