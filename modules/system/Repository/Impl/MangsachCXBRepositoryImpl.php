<?php

namespace Modules\System\Repository\Impl;

use Core\Repository\BaseRepository;
use Modules\System\Repository\MangsachCXBRepository;
use Modules\System\Model\DM_MANGSACH_CXB;


class MangsachCXBRepositoryImpl extends BaseRepository implements MangsachCXBRepository {
    public function getModel() {
        return DM_MANGSACH_CXB::class;
    }

}
