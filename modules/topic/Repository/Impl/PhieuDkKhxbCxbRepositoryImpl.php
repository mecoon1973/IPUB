<?php

namespace Modules\Topic\Repository\Impl;

use Core\Repository\BaseRepository;
use Modules\Topic\Model\PHIEU_DK_KHXB_CXB;
use Modules\Topic\Repository\PhieuDkKhxbCxbRepository;

class PhieuDkKhxbCxbRepositoryImpl extends BaseRepository implements PhieuDkKhxbCxbRepository
{
    public function getModel()
    {
        return PHIEU_DK_KHXB_CXB::class;
    }
}
