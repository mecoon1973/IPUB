<?php

namespace Modules\System\Repository\Impl;

use Core\Repository\BaseRepository;
use Modules\System\Repository\NhomCanboRepository;
use Modules\System\Model\Merge_nhom_canbo;


class NhomCanboRepositoryImpl extends BaseRepository implements NhomCanboRepository {
    public function getModel() {
        return Merge_nhom_canbo::class;
    }

}
