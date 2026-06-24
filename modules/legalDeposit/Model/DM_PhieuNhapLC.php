<?php

namespace Modules\legalDeposit\Model;

use Core\Model\Model;
use DateTime;
use Modules\Topic\Model\DM_SACH;

/**
 * @property int $_id
 * @property DateTime $NgayNhap
 * @property int $SoPhieu
 * @property int $SoChungTu
 * @property int $ID_Sach
 * @property string $TenSach
 * @property int $ID_LoaiSachLC
 * @property int $SoLuongIn
 * @property int $SoLuong
 * @property int $ID_DV_In
 * @property int $DonViIn
 * @property bool $LaInNoiBan
 * @property string $GhiChu
 * @property int $CreatedBy
 * @property DateTime $CreatedOn
 * @property int $EditedBy
 * @property DateTime $EditedOn
 * @property boolean $InUsed
 * @property boolean $IsDeleted
 * @property boolean $DaGui
 * @property string $KhoaGuiNhan
 * @property string $TacGia
 * @property int $SoTrang
 * @property string $KhoSach
 * @property string $GiaBia
 * @property string $HTXB
 * @property int $LanTaiBan
 * @property int $BienTapVien
 * @property DateTime $NgayCXBXacNhan
 * @property string $BienDich
 * @property string $NgonNguDichSach
 * @property string $NguXuatBanSach
 * @property string $TheLoaiSach
 * @property string $DiaChiInSach
 * @property int $SoTap
 * @property int $ID_QDXB
 * @property int $SoQuyetDXB
 * @property DateTime $NgayQD
 * @property bool $LoaiSach
 * @property string $DiaChiWebSachDienTu
 * @property string $TenDonViLK
 * @property string $DiaChiDonViLK
 * @property int $SoVB
 * @property bool $DaCapQDPH
 * @property string $TenCoSoIn
 * @property string $DungLuongTep
 * @property string $DinhDangTep
 *
 * @property DM_SACH $sach
 */
class DM_PhieuNhapLC extends Model {
    protected $connection = "olm";

    protected $table = "ipub_phieu_nhap_lc";
	public $timestamps = false;
    protected $primaryKey = "_id";

    public $incrementing = true;
	public $timestamps2 = true;

    protected $fillable = [
        "_id",
        "NgayNhap",
        "SoPhieu",
        "SoChungTu",
        "ID_Sach",
        "TenSach",
        "ID_LoaiSachLC",
        "SoLuongIn",
        "SoLuong",
        "ID_DV_In",
        "DonViIn",
        "LaInNoiBan",
        "GhiChu",
        "CreatedBy",
        "CreatedOn",
        "EditedBy",
        "EditedOn",
        "InUsed",
        "IsDeleted",
        "DaGui",
        "KhoaGuiNhan",
        "TacGia",
        "SoTrang",
        "KhoSach",
        "GiaBia",
        "HTXB",
        "LanTaiBan",
        "BienTapVien",
        "NgayCXBXacNhan",
        "BienDich",
        "NgonNguDichSach",
        "NguXuatBanSach",
        "TheLoaiSach",
        "DiaChiInSach",
        "SoTap",
        "ID_QDXB",
        "SoQuyetDXB",
        "NgayQD",
        "LoaiSach",
        "DiaChiWebSachDienTu",
        "TenDonViLK",
        "DiaChiDonViLK",
        "SoVB",
        "DaCapQDPH",
        "TenCoSoIn",
        "DungLuongTep",
        "DinhDangTep",
    ];

    protected $attributes = [
        "_id" => 0,
        "NgayNhap" => null,
        "SoPhieu" => 0,
        "SoChungTu" => 0,
        "ID_Sach" => 0,
        "TenSach" => "",
        "ID_LoaiSachLC" => 0,
        "SoLuongIn" => 0,
        "SoLuong" => 0,
        "ID_DV_In" => 0,
        "DonViIn" => "",
        "LaInNoiBan" => false,
        "GhiChu" => "",
        "CreatedBy" => 0,
        "EditedBy" => 0,
        "InUsed" => false,
        "IsDeleted" => false,
        "DaGui" => false,
        "KhoaGuiNhan" => "",
        "TacGia" => "",
        "SoTrang" => 0,
        "KhoSach" => "",
        "GiaBia" => 0,
        "HTXB" => false,
        "LanTaiBan" => 0,
        "BienTapVien" => "",
        "NgayCXBXacNhan" => null,
        "BienDich" => "",
        "NgonNguDichSach" => "",
        "NguXuatBanSach" => "",
        "TheLoaiSach" => "",
        "DiaChiInSach" => "",
        "SoTap" => 0,
        "ID_QDXB" => 0,
        "SoQuyetDXB" => 0,
        "NgayQD" => null,
        "LoaiSach" => false,
        "DiaChiWebSachDienTu" => "",
        "TenDonViLK" => "",
        "DiaChiDonViLK" => "",
        "SoVB" => 0,
        "DaCapQDPH" => false,
        "TenCoSoIn" => "",
        "DungLuongTep" => "",
        "DinhDangTep" => "",
    ];

    protected $casts = [
        "CreatedOn" => "datetime",
        "EditedOn" => "datetime",
    ];

    protected $customCasts = [];

    public function sach() {
        return $this->belongsTo(DM_SACH::class, "ID_Sach", "_id")->select("TenSach", "TacGia", "MaSo");
    }
}
