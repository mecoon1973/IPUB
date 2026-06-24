<?php

namespace Modules\System\Repository\Impl;

use Core\Repository\BaseRepository;
use Modules\System\Repository\MangsachRepository;
use Modules\System\Model\DM_MANGSACH;


class MangsachRepositoryImpl extends BaseRepository implements MangsachRepository {
    public function getModel() {
        return DM_MANGSACH::class;
    }

}
