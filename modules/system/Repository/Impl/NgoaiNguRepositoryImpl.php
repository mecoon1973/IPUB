<?php

namespace Modules\System\Repository\Impl;

use Core\Repository\BaseRepository;
use Modules\System\Repository\NgoaiNguRepository;
use Modules\System\Model\DM_NGOAI_NGU;


class NgoaiNguRepositoryImpl extends BaseRepository implements NgoaiNguRepository {
    public function getModel() {
        return DM_NGOAI_NGU::class;
    }

}
