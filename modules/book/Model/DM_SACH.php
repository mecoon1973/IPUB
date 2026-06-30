<?php

namespace Modules\Book\Model;

use Core\Model\Model;
use DateTime;
use Modules\System\Model\DM_DONVI;
use Modules\System\Model\DM_MANGSACH;

/**
 * @property int $_id
 * @property bool $BanQuyen
 * @property DateTime $BanQuyenTuNgay
 * @property DateTime $BanQuyenDenNgay
 * @property string $BienTapBien
 * @property bool $CanhBao
 * @property bool $CoMSISBN
 * @property int $CreateBy
 * @property DateTime $CreateOn
 * @property bool $DaGui
 * @property string $Dai
 * @property string $DeCuong
 * @property string $DeTaiTuongTu
 * @property string $DiaChiCungCap
 * @property string $DiaChiTacGia
 * @property string $DiaChiTieuThu
 * @property string $DichGia
 * @property string $DinhDangTep
 * @property string $DungLuongTep
 * @property int $EditedBy
 * @property DateTime $EditedOn
 * @property string|null $FMAVACH
 * @property string|null $GhiChu
 * @property int $GiaBia
 * @property bool $HTXB
 * @property bool $HoanThanh
 * @property int $ID_BoSach
 * @property int $ID_Cap
 * @property int $ID_DVLK
 * @property int $ID_DeTai
 * @property int $ID_DonVi
 * @property int $ID_LoaiXBP
 * @property int $ID_Lop
 * @property int $ID_MangSach
 * @property int $ID_MangSach_CXB
 * @property int $ID_MonHoc
 * @property int $ID_TuSach
 * @property string $ISBNCode
 * @property bool $InUsed
 * @property bool $IsCancel
 * @property bool|null $IsDaDangKyLai
 * @property bool $IsDeleted
 * @property bool $IsSachDienTu
 * @property string $KhoaGuiNhan
 * @property int $KieuBanQuyen
 * @property bool|null $LaDeTaiCKH
 * @property bool|null $LaSachDich
 * @property int $LanTaiBan
 * @property int|null $LoaiChinhSua
 * @property string $LuaTuoi
 * @property string|null $LyDoThayDoiSoLuong
 * @property string $MaSo
 * @property string $MaSoCXB
 * @property string|null $MaSoGoc
 * @property string|null $MaSoQTG
 * @property string|null $MaVach
 * @property int $MauInBia
 * @property int $MauInRuot
 * @property string $NamTaiBan
 * @property string $NamXuatBan
 * @property DateTime $NgayCapPhep
 * @property DateTime $NgayDK
 * @property DateTime|null $NgayCapQTG
 * @property DateTime|null $NgayDocDuyet
 * @property string|null $NoiDung
 * @property bool|null $PTXB
 * @property string $Rong
 * @property string $SoGPXB
 * @property string $SoHuuBanQuyen
 * @property int $SoLuong
 * @property int $SoLuongConLai
 * @property int|null $SoLuongTruocTamDung
 * @property int $SoTrang
 * @property string $TacGia
 * @property string $TenNguyenBan
 * @property string $TenSach
 * @property string $ThoiDiemCoDuBT
 * @property string $ThoiDiemRaSach
 * @property string $ThongTinBanQuyen
 * @property string|null $ThongTinLienQuan
 * @property string|null $ThongTinSachDich
 * @property string|null $TrangThaiDocBanThao
 * @property int|null $TypeLuaTuoi
 * @property int|null $VongThau
 * @property bool|null $XetDuyetBanThao
 * @property string|null $YKienDocBanThao
 *
 * @property DM_DONVI $don_vi
 * @property DM_MANGSACH $mang_sach
 *
 */
class DM_SACH extends Model {
    protected $connection = "olm";

    protected $table = "ipub_dm_sach";
	public $timestamps = false;
    protected $primaryKey = "_id";

    public $incrementing = true;
	public $timestamps2 = true;

    protected $fillable = [
        "_id",
        "ID_DeTai",
        "MaSoGoc",
        "MaSo",
        "MaSoCXB",
        "NgayDK",
        "NgayCapPhep",
        "TenSach",
        "BienTapVien",
        "LaSachDich",
        "TenNguyenBan",
        "NguDuocDich",
        "NguXuatBan",
        "ThongTinSachDich",
        "TacGia",
        "DichGia",
        "DiaChiTacGia",
        "DeTaiTuongTu",
        "DiaChiTieuThu",
        "DeCuong",
        "ID_MangSach_CXB",
        "ID_LoaiXBP",
        "ID_TuSach",
        "ID_DonVi",
        "ID_DVLK",
        "ID_MonHoc",
        "ID_MangSach",
        "ID_Lop",
        "ID_Cap",
        "HTXB",
        "PTXB",
        "ThoiDiemCoDuBT",
        "ThoiDiemRaSach",
        "SoTrang",
        "Dai",
        "Rong",
        "GiaBia",
        "NamXuatBan",
        "NamTaiBan",
        "LanTaiBan",
        "SoLuong",
        "MauInRuot",
        "MauBia",
        "NoiDung",
        "GhiChu",
        "HoanThanh",
        "MaVach",
        "CreatedBy",
        "CreatedOn",
        "EditedBy",
        "EditedOn",
        "IsDeleted",
        "IsUsed",
        "DaGui",
        "KhoaGuiNhan",
        "ISBNCode",
        "MaSoQTG",
        "VongThau",
        "LaDeTaiCKH",
        "ThongTinLienQuan",
        "FMAVACH",
        "BanQuyen",
        "CoMSISBN",
        "SoLuongConLai",
        "DungLuongTep",
        "DiaChiCungCap",
        "IsSachDienTu",
        "KieuBanQuyen",
        "BanQuyenTuNgay",
        "BanQuyenDenNgay",
        "ThongTinBanQuyen",
        "IsCancel",
        "IsDaDangKyLai",
        "LoaiChinhSua",
        "isTamDungCapPhep",
        "LyDoThayDoiSoLuong",
        "SoLuongTruocTamDung",
        "SoHuuBanQuyen",
        "LuaTuoi",
        "TypeLuaTuoi",
        "CanhBao",
        "TrangThaiDocBanThao",
        "YKienDocBanThao",
        "XetDuyetBanThao",
        "NguoiDocDuyet",
        "NgayDocDuyet",
        "tenrutgon",
    ];

    protected $attributes = [
        "_id" => 0,
        "ID_DeTai" => 0,
        "MaSoGoc" => "",
        "MaSo" => "",
        "MaSoCXB" => "",
        "NgayDK" => null,
        "NgayCapPhep" => null,
        "TenSach" => "",
        "BienTapVien" => "",
        "LaSachDich" => false,
        "TenNguyenBan" => "",
        "NguDuocDich" => "",
        "NguXuatBan" => "",
        "ThongTinSachDich" => "",
        "TacGia" => "",
        "DichGia" => "",
        "DiaChiTacGia" => "",
        "DeTaiTuongTu" => "",
        "DiaChiTieuThu" => "",
        "DeCuong" => "",
        "ID_MangSach_CXB" => 0,
        "ID_LoaiXBP" => 0,
        "ID_TuSach" => 0,
        "ID_DonVi" => 0,
        "ID_DVLK" => 0,
        "ID_MonHoc" => 0,
        "ID_MangSach" => 0,
        "ID_Lop" => 0,
        "ID_Cap" => 0,
        "HTXB" => false,
        "PTXB" => false,
        "ThoiDiemCoDuBT" => "",
        "ThoiDiemRaSach" => "",
        "SoTrang" => 0,
        "Dai" => "",
        "Rong" => "",
        "GiaBia" => 0,
        "NamXuatBan" => "",
        "NamTaiBan" => "",
        "LanTaiBan" => 0,
        "SoLuong" => 0,
        "MauInRuot" => 0,
        "MauBia" => 0,
        "NoiDung" => "",
        "GhiChu" => "",
        "HoanThanh" => false,
        "MaVach" => "",
        "CreatedBy" => 0,
        "CreatedOn" => null,
        "EditedBy" => 0,
        "EditedOn" => null,
        "IsDeleted" => false,
        "IsUsed" => false,
        "DaGui" => false,
        "KhoaGuiNhan" => "",
        "ISBNCode" => "",
        "MaSoQTG" => "",
        "VongThau" => 0,
        "LaDeTaiCKH" => false,
        "ThongTinLienQuan" => "",
        "FMAVACH" => "",
        "BanQuyen" => false,
        "CoMSISBN" => false,
        "SoLuongConLai" => 0,
        "DungLuongTep" => "",
        "DiaChiCungCap" => "",
        "IsSachDienTu" => false,
        "KieuBanQuyen" => 0,
        "BanQuyenTuNgay" => null,
        "BanQuyenDenNgay" => null,
        "ThongTinBanQuyen" => "",
        "IsCancel" => false,
        "IsDaDangKyLai" => false,
        "LoaiChinhSua" => 0,
        "isTamDungCapPhep" => false,
        "LyDoThayDoiSoLuong" => "",
        "SoLuongTruocTamDung" => 0,
        "SoHuuBanQuyen" => "",
        "LuaTuoi" => "",
        "TypeLuaTuoi" => 0,
        "CanhBao" => false,
    ];

    protected $casts = [
        "CreatedOn" => "datetime",
        "EditedOn" => "datetime",
    ];

    protected $customCasts = [];

    public function don_vi() {
        return $this->belongsTo(DM_DONVI::class, "ID_DonVi", "id");
    }

    public function mang_sach() {
        return $this->belongsTo(DM_MANGSACH::class, "ID_MangSach", "id");
    }
}
