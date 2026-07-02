<?php

namespace Modules\Book\Model;

use Core\Model\Model;
use DateTime;

/**
 * @property int $_id
 * @property string $BienTapVien
 * @property bool $CheBanCan
 * @property bool $CoAoBoc
 * @property int $CreatedBy
 * @property DateTime $CreatedOn
 * @property bool $DaGui
 * @property int $Dai
 * @property string $DiaChiCungCap
 * @property string $DinhDangTep
 * @property string $DungLuongTep
 * @property int $EditedBy
 * @property DateTime $EditedOn
 * @property string $GhiChu
 * @property int|null $ID_BTVNhan
 * @property int|null $ID_DV
 * @property int|null $ID_DeTai
 * @property int|null $ID_LanhDaoKiBenGui
 * @property string $ID_ListBienTapVien
 * @property int|null $ID_MangSach
 * @property int|null $ID_NguoiKy
 * @property int|null $ID_PhieuChuyenGoc
 * @property int|null $ID_Sach
 * @property bool $InUsed
 * @property bool $IsDeleted
 * @property bool $IsSachDienTu
 * @property bool $IsSubject
 * @property string $KhoSach
 * @property string $KhoaGuiNhan
 * @property int $LanIn
 * @property bool $LoaiBia
 * @property bool $LoaiPhieu
 * @property bool $Locked
 * @property string $MaDVIN
 * @property int $MauInBia
 * @property int $MauInRout
 * @property DateTime $NgayGiao
 * @property DateTime $NgayNhan
 * @property string $NguoiGiao
 * @property string $NguoiNhan
 * @property int $Rong
 * @property int $SoBo
 * @property int $SoBoBanThao
 * @property int $SoBoBiaMau
 * @property int $SoBoPhimBia
 * @property int|null $SoMauInBia
 * @property int $SoTrang
 * @property int $SoTrangPhuBan
 * @property int $SoTrangRuotSach
 * @property string $TacGia
 */
class DM_PHIEU_CHUYEN_BAN_THAO extends Model {
    protected $connection = "olm";

    protected $table = "ipub_dm_phieu_chuyen_ban_thao";
	public $timestamps = false;
    protected $primaryKey = "_id";

    public $incrementing = true;
	public $timestamps2 = true;

    protected $fillable = [
        "BienTapVien",
        "CheBanCan",
        "CoAoBoc",
        "CreatedBy",
        "CreatedOn",
        "DaGui",
        "Dai",
        "DiaChiCungCap",
        "DinhDangTep",
        "DungLuongTep",
        "EditedBy",
        "EditedOn",
        "GhiChu",
        "ID_BTVNhan",
        "ID_DV",
        "ID_DeTai",
        "ID_LanhDaoKiBenGui",
        "ID_ListBienTapVien",
        "ID_MangSach",
        "ID_NguoiKy",
        "ID_PhieuChuyenGoc",
        "ID_Sach",
        "InUsed",
        "IsDeleted",
        "IsSachDienTu",
        "IsSubject",
        "KhoSach",
        "KhoaGuiNhan",
        "LanIn",
        "LoaiBia",
        "LoaiPhieu",
        "Locked",
        "MaDVIN",
        "MauInBia",
        "MauInRout",
        "NgayGiao",
        "NgayNhan",
        "NguoiGiao",
        "NguoiNhan",
        "Rong",
        "SoBo",
        "SoBoBanThao",
        "SoBoBiaMau",
        "SoBoPhimBia",
        "SoMauInBia",
        "SoTrang",
        "SoTrangPhuBan",
        "SoTrangRuotSach",
        "TacGia",
    ];

    protected $attributes = [
    ];

    protected $casts = [
        "CheBanCan" => "boolean",
        "CoAoBoc" => "boolean",
        "CreatedBy" => "integer",
        "CreatedOn" => "datetime",
        "DaGui" => "boolean",
        "Dai" => "integer",
        "EditedBy" => "integer",
        "EditedOn" => "datetime",
        "ID_BTVNhan" => "integer",
        "ID_DV" => "integer",
        "ID_DeTai" => "integer",
        "ID_LanhDaoKiBenGui" => "integer",
        "ID_MangSach" => "integer",
        "ID_NguoiKy" => "integer",
        "ID_PhieuChuyenGoc" => "integer",
        "ID_Sach" => "integer",
        "InUsed" => "boolean",
        "IsDeleted" => "boolean",
        "IsSachDienTu" => "boolean",
        "IsSubject" => "boolean",
        "LanIn" => "integer",
        "LoaiBia" => "boolean",
        "LoaiPhieu" => "boolean",
        "Locked" => "boolean",
        "MauInBia" => "integer",
        "MauInRout" => "integer",
        "NgayGiao" => "datetime",
        "NgayNhan" => "datetime",
        "Rong" => "integer",
        "SoBo" => "integer",
        "SoBoBanThao" => "integer",
        "SoBoBiaMau" => "integer",
        "SoBoPhimBia" => "integer",
        "SoMauInBia" => "integer",
        "SoTrang" => "integer",
        "SoTrangPhuBan" => "integer",
        "SoTrangRuotSach" => "integer",
    ];

    protected $customCasts = [];
}
