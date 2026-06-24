<?php

namespace Modules\System\Repository\Impl;

use Core\Repository\BaseRepository;
use Modules\System\Repository\BienMoiTruongDonViRepository;
use Modules\System\Model\DM_BIEN_MOI_TRUONG_DON_VI;


class BienMoiTruongDonViRepositoryImpl extends BaseRepository implements BienMoiTruongDonViRepository {
    public function getModel() {
        return DM_BIEN_MOI_TRUONG_DON_VI::class;
    }

}
