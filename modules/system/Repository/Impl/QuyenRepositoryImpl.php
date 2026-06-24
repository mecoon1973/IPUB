<?php

namespace Modules\System\Repository\Impl;

use Core\Repository\BaseRepository;
use Modules\System\Model\DM_QUYEN;
use Modules\System\Repository\QuyenRepository;

class QuyenRepositoryImpl extends BaseRepository implements QuyenRepository {
    public function getModel() {
        return DM_QUYEN::class;
    }

}
