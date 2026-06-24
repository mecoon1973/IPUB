<?php

namespace Modules\System\Repository\Impl;

use Core\Repository\BaseRepository;
use Modules\System\Repository\PhanheRepository;
use Modules\System\Model\DM_PHANHE;


class PhanheRepositoryImpl extends BaseRepository implements PhanheRepository {
    public function getModel() {
        return DM_PHANHE::class;
    }

}
