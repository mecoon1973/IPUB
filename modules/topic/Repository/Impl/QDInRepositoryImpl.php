<?php

namespace Modules\Topic\Repository\Impl;

use Core\Repository\BaseRepository;
use Modules\Topic\Repository\QDInRepository;
use Modules\Topic\Model\QDIn;


class QDInRepositoryImpl extends BaseRepository implements QDInRepository {
    public function getModel() {
        return QDIn::class;
    }

}
