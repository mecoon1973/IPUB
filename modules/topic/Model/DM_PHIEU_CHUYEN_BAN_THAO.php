<?php

namespace Modules\Topic\Model;

use Core\Model\Model;
use DateTime;
use Modules\Book\Model\DM_SACH;
use Modules\System\Model\DM_DONVI;
use Modules\User\Model\User;

/**
 * Model DM_PHIEU_CHUYEN_BAN_THAO — Phiếu chuyển bản thảo
 *
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
 * @property DateTime|null $NgayGiao
 * @property DateTime|null $NgayNhan
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
 *
 *
 * @property DM_SACH|null $sach
 * @property User|null $nguoiKy
 * @property DM_DONVI|null $donvi
 *
 */
class DM_PHIEU_CHUYEN_BAN_THAO extends Model {
    protected $connection = "olm";

    protected $table = "ipub_phieu_chuyenbanthao_sx";
    public $timestamps = false;
    protected $primaryKey = "_id";

    public $incrementing = true;
    public $timestamps2 = true;

    protected $fillable = [
        "_id",
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
        "_id" => 0,
        "BienTapVien" => "",
        "CheBanCan" => false,
        "CoAoBoc" => false,
        "CreatedBy" => 0,
        "CreatedOn" => null,
        "DaGui" => false,
        "Dai" => 0,
        "DiaChiCungCap" => "",
        "DinhDangTep" => "",
        "DungLuongTep" => "",
        "EditedBy" => 0,
        "EditedOn" => null,
        "GhiChu" => "",
        "ID_BTVNhan" => null,
        "ID_DV" => null,
        "ID_DeTai" => null,
        "ID_LanhDaoKiBenGui" => null,
        "ID_ListBienTapVien" => "",
        "ID_MangSach" => null,
        "ID_NguoiKy" => null,
        "ID_PhieuChuyenGoc" => null,
        "ID_Sach" => null,
        "InUsed" => true,
        "IsDeleted" => false,
        "IsSachDienTu" => false,
        "IsSubject" => false,
        "KhoSach" => "",
        "KhoaGuiNhan" => "",
        "LanIn" => 1,
        "LoaiBia" => false,
        "LoaiPhieu" => false,
        "Locked" => false,
        "MaDVIN" => "",
        "MauInBia" => 0,
        "MauInRout" => 0,
        "NgayGiao" => null,
        "NgayNhan" => null,
        "NguoiGiao" => "",
        "NguoiNhan" => "",
        "Rong" => 0,
        "SoBo" => 0,
        "SoBoBanThao" => 0,
        "SoBoBiaMau" => 0,
        "SoBoPhimBia" => 0,
        "SoMauInBia" => null,
        "SoTrang" => 0,
        "SoTrangPhuBan" => 0,
        "SoTrangRuotSach" => 0,
        "TacGia" => "",
    ];

    protected $casts = [
        "CreatedOn" => "datetime",
        "EditedOn" => "datetime",
        "NgayGiao" => "datetime",
        "NgayNhan" => "datetime",
    ];

    protected $customCasts = [];

    public function sach() {
        return $this->belongsTo(DM_SACH::class, "ID_Sach");
    }
    public function nguoiKy() {
        return $this->belongsTo(User::class, "ID_NguoiKy");
    }
    public function donvi() {
        return $this->belongsTo(DM_DONVI::class, "ID_DV");
    }
}
