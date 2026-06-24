<?php

namespace Modules\legalDeposit\Repository\Impl;

use Core\Repository\BaseRepository;
use Modules\legalDeposit\Repository\PhieuNhapLCRepository;
use Modules\legalDeposit\Model\DM_PhieuNhapLC;


class PhieuNhapLCRepositoryImpl extends BaseRepository implements PhieuNhapLCRepository {
    public function getModel() {
        return DM_PhieuNhapLC::class;
    }

}
