<?php

namespace Modules\System\Repository\Impl;

use Core\Repository\BaseRepository;
use Modules\System\Repository\LoaiXBPRepository;
use Modules\System\Model\DM_LOAI_XBP;


class LoaiXBPRepositoryImpl extends BaseRepository implements LoaiXBPRepository {
    public function getModel() {
        return DM_LOAI_XBP::class;
    }

}
