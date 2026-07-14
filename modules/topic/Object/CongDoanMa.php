<?php

namespace Modules\Topic\Object;

use Modules\Topic\Object\PhieuDkDetaiTrangThai as TT;

/**
 * Mã công đoạn trong ipub_dm_congdoan.macd
 */
final class CongDoanMa
{
    public const TAO_PHIEU_DK = 'M01';
    public const DANG_KY_LAI = 'M02';
    public const HUY_DE_TAI = 'M03';
    public const THEM_PHIEU_HD_XB_MIEN = 'M04';
    public const HDXB_MIEN_XET_DUYET = 'M05';
    public const PHAN_CONG_DOC_DUYET = 'M06';
    public const KET_LUAN_DOC_DUYET_DONG_Y = 'M06.1';
    public const KET_LUAN_DOC_DUYET_KHONG_DONG_Y = 'M06.2';
    public const KET_LUAN_DOC_DUYET_XIN_Y_KIEN = 'M06.3';
    public const HDXB_NXBGDVN_XET_DUYET = 'M07';
    public const TAO_DON_DK_XUAT_BAN = 'M08';
    public const KET_LUAN_CXB_DANG_XET = 'M08.1';
    public const KET_LUAN_CXB_PHE_DUYET = 'M08.2';
    public const KET_LUAN_CXB_TRA_LAI = 'M08.3';
    public const CAP_MA_SO_CXB = 'M09';
    public const CAP_MA_ISBN = 'M10';
    public const KET_CHUYEN_SACH = 'M11';
    public const TAO_PHIEU_CHUYEN_BT = 'M12';
    public const XOA_PHIEU_CHUYEN_BT = 'M13';

    /**
     * Map MaTrangThai đề tài → macd danh mục công đoạn.
     *
     * @return array<int, string>
     */
    public static function trangThaiMap(): array
    {
        return [
            TT::HDXB_DON_VI_DANG_XET => self::HDXB_MIEN_XET_DUYET,
            TT::HDXB_DON_VI_PHE_DUYET => self::HDXB_MIEN_XET_DUYET,
            TT::HDXB_NXBGDVN_TRA_LAI => self::HDXB_NXBGDVN_XET_DUYET,
            TT::HDXB_NXBGDVN_DANG_XET => self::HDXB_NXBGDVN_XET_DUYET,
            TT::HDXB_NXBGDVN_PHE_DUYET => self::HDXB_NXBGDVN_XET_DUYET,
            TT::HDXB_NXBGDVN_CHUA_XET => self::HDXB_NXBGDVN_XET_DUYET,
            TT::CXB_TRA_LAI => self::KET_LUAN_CXB_TRA_LAI,
            TT::CXB_DANG_XET => self::KET_LUAN_CXB_DANG_XET,
            TT::CXB_PHE_DUYET => self::KET_LUAN_CXB_PHE_DUYET,
            TT::KET_CHUYEN_THANH_SACH => self::KET_CHUYEN_SACH,
            17 => self::KET_CHUYEN_SACH,
        ];
    }

    /**
     * Map kết luận đọc duyệt (ipub_nx_canbo_detai.Duyet) → macd.
     *
     * @return array<int, string>
     */
    public static function ketLuanDocDuyetMap(): array
    {
        return [
            NxCanBoDetaiDuyet::DUYET => self::KET_LUAN_DOC_DUYET_DONG_Y,
            NxCanBoDetaiDuyet::TRA_LAI => self::KET_LUAN_DOC_DUYET_KHONG_DONG_Y,
        ];
    }

    private function __construct() {}
}
