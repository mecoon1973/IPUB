<?php

namespace Modules\System\Repository\Impl;

use Core\Repository\BaseRepository;
use Modules\System\Repository\LoaiSnvRepository;
use Modules\System\Model\DM_LOAI_SNV;


class LoaiSnvRepositoryImpl extends BaseRepository implements LoaiSnvRepository {
    public function getModel() {
        return DM_LOAI_SNV::class;
    }

}
