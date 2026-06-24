<?php

namespace Modules\Topic\Model;

use Core\Model\Model;
use DateTime;

/**
 * @property int $_id
 * @property int $ID_QD_IN
 * @property int $ID_Sach
 * @property int $ID_QDXB
 * @property string $THBS
 * @property int $SoLuongSauDieuChinh
 * @property int $SoLuongIn
 * @property DateTime $ThoiHanNhapKho
 * @property int $ID_DV_IN
 * @property string $TenDonViIn
 * @property string $GhiChu
 * @property int $CreatedBy
 * @property DateTime $CreatedOn
 * @property int $EditedBy
 * @property DateTime $EditedOn
 * @property boolean $IsDeleted
 * @property boolean $IsUsed
 * @property boolean $DaGui
 * @property string $KhoaGuiNhan
 * @property string $GiayInRout
 * @property string $GiayInBia
 * @property bool $HDXB
 * @property int $LanTaiBan
 * @property int $SoTrang
 * @property string $KhoSach
 * @property int $GiayBia
 * @property string $MauInRuot
 * @property string $MauInBia
 * @property string $BienTapVien
 * @property string $MaSoSach
 * @property string $TenSach
 * @property string $MaSoCXB
 * @property string $MaDonViIn
 * @property string $TacGia
 * @property string $IDCT_VMS
 * @property bool $TinhTrangXuatBan
 * @property string $DiaChiDonViIn
 * @property bool $IsQDXB
 * @property string $MaSachVMS
 * @property string $TenCoSoIn
 * @property string $MaCoSoIn
 * @property string $IdCoSoIn
 * @property bool $IsSachDienTu
 * @property string $DinhDangTep
 * @property string $DungLuongTep
 * @property string $DiaChiCungCap
 * @property string $LyDoDieuChinhSoLuong
 * @property bool $IsNoiBan
 * @property int $LanNoiBan
 *
 */
class CT_QD_In extends Model {
    protected $connection = "olm";

    protected $table = "ipub_ct_qd_in";
	public $timestamps = false;
    protected $primaryKey = "_id";

    public $incrementing = true;
	public $timestamps2 = true;

    protected $fillable = [
        "_id",
        "ID_QD_IN",
        "ID_Sach",
        "ID_QDXB",
        "THBS",
        "SoLuongSauDieuChinh",
        "SoLuongIn",
        "ThoiHanNhapKho",
        "ID_DV_IN",
        "TenDonViIn",
        "GhiChu",
        "CreatedBy",
        "CreatedOn",
        "EditedBy",
        "EditedOn",
        "IsDeleted",
        "IsUsed",
        "DaGui",
        "KhoaGuiNhan",
        "GiayInRout",
        "GiayInBia",
        "HDXB",
        "LanTaiBan",
        "SoTrang",
        "KhoSach",
        "GiayBia",
        "MauInRuot",
        "MauInBia",
        "BienTapVien",
        "MaSoSach",
        "TenSach",
        "MaSoCXB",
        "MaDonViIn",
        "TacGia",
        "IDCT_VMS",
        "TinhTrangXuatBan",
        "DiaChiDonViIn",
        "IsQDXB",
        "MaSachVMS",
        "TenCoSoIn",
        "MaCoSoIn",
        "IdCoSoIn",
        "IsSachDienTu",
        "DinhDangTep",
        "DungLuongTep",
        "DiaChiCungCap",
        "LyDoDieuChinhSoLuong",
        "IsNoiBan",
        "LanNoiBan",
    ];

    protected $attributes = [
        "_id" => 0,
        "ID_QD_IN" => 0,
        "ID_Sach" => 0,
        "ID_QDXB" => 0,
        "THBS" => "",
        "SoLuongSauDieuChinh" => 0,
        "SoLuongIn" => 0,
        "ThoiHanNhapKho" => null,
        "ID_DV_IN" => 0,
        "TenDonViIn" => "",
        "GhiChu" => "",
        "CreatedBy" => 0,
        "EditedBy" => 0,
        "IsDeleted" => false,
        "IsUsed" => false,
        "DaGui" => false,
        "KhoaGuiNhan" => "",
        "GiayInRout" => "",
        "GiayInBia" => "",
        "HDXB" => false,
        "LanTaiBan" => 0,
        "SoTrang" => 0,
        "KhoSach" => "",
        "GiayBia" => "",
        "MauInRuot" => "",
        "MauInBia" => "",
        "BienTapVien" => "",
        "MaSoSach" => "",
        "TenSach" => "",
        "MaSoCXB" => "",
        "MaDonViIn" => "",
        "TacGia" => "",
        "IDCT_VMS" => "",
        "TinhTrangXuatBan" => false,
        "DiaChiDonViIn" => "",
        "IsQDXB" => false,
        "MaSachVMS" => "",
        "TenCoSoIn" => "",
        "MaCoSoIn" => "",
        "IdCoSoIn" => "",
        "IsSachDienTu" => false,
        "DinhDangTep" => "",
        "DungLuongTep" => "",
        "DiaChiCungCap" => "",
        "LyDoDieuChinhSoLuong" => "",
        "IsNoiBan" => false,
        "LanNoiBan" => 0,
    ];

    protected $casts = [
        "CreatedOn" => "datetime",
        "EditedOn" => "datetime",
    ];

    protected $customCasts = [];
}
