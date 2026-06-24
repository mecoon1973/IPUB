<?php

namespace Modules\LegalDeposit\Repository\Impl;

use Core\Repository\BaseRepository;
use Modules\LegalDeposit\Repository\ToKhaiLuuChuyenRepository;
use Modules\LegalDeposit\Model\DM_ToKhaiLuuChuyen;


class ToKhaiLuuChuyenRepositoryImpl extends BaseRepository implements ToKhaiLuuChuyenRepository {
    public function getModel() {
        return DM_ToKhaiLuuChuyen::class;
    }

}
