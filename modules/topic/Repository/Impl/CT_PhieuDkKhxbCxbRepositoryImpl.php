<?php

namespace Modules\Topic\Repository\Impl;

use Core\Repository\BaseRepository;
use Modules\Topic\Model\CT_PHIEU_DK_KHXB_CXB;
use Modules\Topic\Repository\CT_PhieuDkKhxbCxbRepository;

class CT_PhieuDkKhxbCxbRepositoryImpl extends BaseRepository implements CT_PhieuDkKhxbCxbRepository
{
    public function getModel()
    {
        return CT_PHIEU_DK_KHXB_CXB::class;
    }
}
