<?php

namespace Modules\Topic\Repository\Impl;

use Core\Repository\BaseRepository;
use Modules\Topic\Repository\CT_Detai_CongDoanRepository;
use Modules\Topic\Model\CT_Detai_Congdoan;


class CT_Detai_CongDoanRepositoryImpl extends BaseRepository implements CT_Detai_CongDoanRepository {
    public function getModel() {
        return CT_Detai_Congdoan::class;
    }

}
