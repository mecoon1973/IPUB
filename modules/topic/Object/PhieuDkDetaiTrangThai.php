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
    public const CXB_TRA_LAI = 7;
    public const CXB_DANG_XET = 8;
    public const CXB_PHE_DUYET = 9;
    public const KET_CHUYEN_THANH_SACH = 10;
    public const DA_PHE_DUYET_DI_IN = 18;

    /** @return int[] */
    public static function cxbXetDuyetValues(): array
    {
        return [
            self::CXB_TRA_LAI,
            self::CXB_DANG_XET,
            self::CXB_PHE_DUYET,
        ];
    }

    private function __construct() {}
}
