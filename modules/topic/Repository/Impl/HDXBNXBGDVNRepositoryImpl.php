<?php

namespace Modules\Topic\Repository\Impl;

use Core\Repository\BaseRepository;
use Modules\Topic\Model\PHIEU_DK_DETAI;
use Modules\Topic\Repository\HDXBNXBGDVNRepository;

class HDXBNXBGDVNRepositoryImpl extends BaseRepository implements HDXBNXBGDVNRepository {
    public function getModel() {
        return PHIEU_DK_DETAI::class;
    }
}
