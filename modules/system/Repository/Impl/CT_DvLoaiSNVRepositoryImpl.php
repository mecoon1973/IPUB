<?php

namespace Modules\System\Repository\Impl;

use Core\Repository\BaseRepository;
use Modules\System\Repository\CT_DvLoaiSNVRepository;
use Modules\System\Model\CT_DV_LOAI_SNV;


class CT_DvLoaiSNVRepositoryImpl extends BaseRepository implements CT_DvLoaiSNVRepository {
    public function getModel() {
        return CT_DV_LOAI_SNV::class;
    }

}
