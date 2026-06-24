<?php

namespace Modules\System\Repository\Impl;

use Core\Repository\BaseRepository;
use Modules\System\Repository\CanboQuyenRepository;
use Modules\System\Model\CT_CANBO_QUYEN;


class CanboQuyenRepositoryImpl extends BaseRepository implements CanboQuyenRepository {
    public function getModel() {
        return CT_CANBO_QUYEN::class;
    }

}
