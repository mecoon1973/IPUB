<?php

namespace Modules\System\Repository\Impl;

use Core\Repository\BaseRepository;
use Modules\System\Repository\DoituongSNVRepository;
use Modules\System\Model\DM_DOITUONG_SNV;


class DoituongSNVRepositoryImpl extends BaseRepository implements DoituongSNVRepository {
    public function getModel() {
        return DM_DOITUONG_SNV::class;
    }

}
