<?php

namespace Modules\Topic\Repository\Impl;

use Core\Repository\BaseRepository;
use Modules\Topic\Repository\CT_QD_InRepository;
use Modules\Topic\Model\CT_QD_In;


class CT_QD_InRepositoryImpl extends BaseRepository implements CT_QD_InRepository {
    public function getModel() {
        return CT_QD_In::class;
    }

}
