<?php

namespace Modules\QualityAssessment\Repository\Impl;

use Core\Repository\BaseRepository;
use Modules\QualityAssessment\Repository\DSDocRaSoatRepository;
use Modules\QualityAssessment\Model\DM_DSDocRaSoat;


class DSDocRaSoatRepositoryImpl extends BaseRepository implements DSDocRaSoatRepository {
    public function getModel() {
        return DM_DSDocRaSoat::class;
    }

}
