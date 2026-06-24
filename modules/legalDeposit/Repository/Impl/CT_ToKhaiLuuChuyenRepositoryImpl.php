<?php

namespace Modules\LegalDeposit\Repository\Impl;

use Core\Repository\BaseRepository;
use Modules\LegalDeposit\Repository\CT_ToKhaiLuuChuyenRepository;
use Modules\LegalDeposit\Model\CT_ToKhaiLuuChuyen;


class CT_ToKhaiLuuChuyenRepositoryImpl extends BaseRepository implements CT_ToKhaiLuuChuyenRepository {
    public function getModel() {
        return CT_ToKhaiLuuChuyen::class;
    }

}
