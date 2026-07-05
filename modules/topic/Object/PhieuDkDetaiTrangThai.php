<?php

namespace Modules\Topic\Object;

/**
 * Mã trạng thái đề tài (MaTrangThai) — đồng bộ với ipub_dm_trangthai.
 */
final class PhieuDkDetaiTrangThai
{
    public const CHUA_XET_DUYET = 0;
    public const HDXB_DON_VI_TRA_LAI = 1;
    public const HDXB_DON_VI_DANG_XET = 2;
    public const HDXB_DON_VI_PHE_DUYET = 3;
    public const HDXB_NXBGDVN_TRA_LAI = 4;
    public const HDXB_NXBGDVN_DANG_XET = 5;
    public const HDXB_NXBGDVN_PHE_DUYET = 6;
    public const HDXB_NXBGDVN_CHUA_XET = 16;

    private function __construct() {}
}
