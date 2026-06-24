<?php

namespace Modules\Topic\Model;

use Core\Model\Model;
use DateTime;

/**
 * Danh mục sách
 * @property int $_id
 * @property int $ID_DeTai
 * @property string $MaSoGoc
 * @property string $MaSo
 * @property string $MaSoCXB
 * @property DateTime $NgayDK
 * @property DateTime $NgayCapPhep
 * @property string $TenSach
 * @property string $BienTapVien
 * @property bool $LaSachDich
 * @property string $TenNguyenBan
 * @property string $NguDuocDich
 * @property string $NguXuatBan
 * @property string $ThongTinSachDich
 * @property string $TacGia
 * @property string $DichGia
 * @property string $DiaChiTacGia
 * @property string $DeTaiTuongTu
 * @property string $DiaChiTieuThu
 * @property string $DeCuong
 * @property int $ID_MangSach_CXB
 * @property int $ID_LoaiXBP
 * @property int $ID_TuSach
 * @property int $ID_DonVi
 * @property int $ID_DVLK
 * @property int $ID_MonHoc
 * @property int $ID_MangSach
 * @property int $ID_Lop
 * @property int $ID_Cap
 * @property bool $HTXB
 * @property bool $PTXB
 * @property string $ThoiDiemCoDuBT
 * @property string $ThoiDiemRaSach
 * @property int $SoTrang
 * @property string $Dai
 * @property string $Rong
 * @property bool $GiaBia
 * @property string $NamXuatBan
 * @property string $NamTaiBan
 * @property int $LanTaiBan
 * @property int $SoLuong
 * @property int $MauInRuot
 * @property int $MauBia
 * @property string $NoiDung
 * @property string $GhiChu
 * @property bool $HoanThanh
 * @property string $MaVach
 * @property int $CreatedBy
 * @property DateTime $CreatedOn
 * @property int $EditedBy
 * @property Datetime $EditedOn
 * @property bool $IsDeleted
 * @property bool $IsUsed
 * @property bool $DaGui
 * @property string $KhoaGuiNhan
 * @property string $ISBNCode
 * @property string $MaSoQTG
 * @property int $VongThau
 * @property bool $LaDeTaiCKH
 * @property string $ThongTinLienQuan
 * @property string $FMAVACH
 * @property bool $BanQuyen
 * @property bool $CoMSISBN
 * @property int $SoLuongConLai
 * @property string $DinhDangTep
 * @property string $DungLuongTep
 * @property string $DiaChiCungCap
 * @property bool $IsSachDienTu
 * @property int $KieuBanQuyen
 * @property DateTime $BanQuyenTuNgay
 * @property DateTime $BanQuyenDenNgay
 * @property string $ThongTinBanQuyen
 * @property bool $IsCancel
 * @property bool $IsDaDangKyLai
 * @property int $LoaiChinhSua
 * @property bool $isTamDungCapPhep
 * @property string $LyDoThayDoiSoLuong
 * @property int $SoLuongTruocTamDung
 * @property string $SoHuuBanQuyen
 * @property string $LuaTuoi
 * @property int $TypeLuaTuoi
 * @property bool $CanhBao
 * @property int $TrangThaiDocBanThao
 * @property string $YKienDocBanThao
 * @property bool $XetDuyetBanThao
 * @property string $NguoiDocDuyet
 * @property DateTime $NgayDocDuyet
 * @property string $tenrutgon
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
        "EditedBy" => 0,
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
        "TrangThaiDocBanThao" => 0,
        "YKienDocBanThao" => "",
        "XetDuyetBanThao" => false,
        "NguoiDocDuyet" => "",
        "NgayDocDuyet" => null,
        "tenrutgon" => "",
    ];

    protected $casts = [
        "CreatedOn" => "datetime",
        "EditedOn" => "datetime",
    ];

    protected $customCasts = [];
}
