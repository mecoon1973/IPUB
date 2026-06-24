<?php

namespace Modules\System\Repository\Impl;

use Core\Repository\BaseRepository;
use Modules\System\Repository\FunctionQuyenRepository;
use Modules\System\Model\CT_FUNCTION_QUYEN;


class FunctionQuyenRepositoryImpl extends BaseRepository implements FunctionQuyenRepository {
    public function getModel() {
        return CT_FUNCTION_QUYEN::class;
    }

}
