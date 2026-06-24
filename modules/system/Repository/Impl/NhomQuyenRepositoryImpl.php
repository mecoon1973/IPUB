<?php

namespace Modules\System\Repository\Impl;

use Core\Repository\BaseRepository;
use Modules\System\Repository\NhomQuyenRepository;
use Modules\System\Model\CT_NHOM_QUYEN;


class NhomQuyenRepositoryImpl extends BaseRepository implements NhomQuyenRepository {
    public function getModel() {
        return CT_NHOM_QUYEN::class;
    }

}
