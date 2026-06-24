<?php

namespace Modules\System\Repository\Impl;

use Core\Repository\BaseRepository;
use Modules\System\Repository\CT_DonviLC_LoaiXBPLCRepository;
use Modules\System\Model\CT_DONVILC_LOAIXBPLC;


class CT_DonviLC_LoaiXBPLCRepositoryImpl extends BaseRepository implements CT_DonviLC_LoaiXBPLCRepository {
    public function getModel() {
        return CT_DONVILC_LOAIXBPLC::class;
    }

}
