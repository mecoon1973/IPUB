<?php

namespace Modules\System\Repository\Impl;

use Core\Repository\BaseRepository;
use Modules\System\Repository\BienMoiTruongRepository;
use Modules\System\Model\DM_BIEN_MOI_TRUONG;


class BienMoiTruongRepositoryImpl extends BaseRepository implements BienMoiTruongRepository {
    public function getModel() {
        return DM_BIEN_MOI_TRUONG::class;
    }

}
