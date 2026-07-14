<?php

namespace Modules\Topic\Model;

use Core\Model\Model;
use DateTime;
use Modules\Topic\Object\PhieuDkDetaiTrangThai;
use Modules\System\Model\DM_BOSACH;
use Modules\System\Model\DM_DONVI;
use Modules\System\Model\DM_LOP;
use Modules\System\Model\DM_MONHOC;
use Modules\System\Model\DM_CAP;
use Modules\System\Model\DM_DOITUONG;
use Modules\System\Model\DM_LOAI_XBP;
use Modules\System\Model\DM_MANGSACH;
use Modules\System\Model\DM_MANGSACH_CXB;

/**
 * @property int $_id
 * @property DateTime $NgayDk
 * @property string $MaSo
 * @property string $MaSoCXB
 * @property string $SoGPXB
 * @property DateTime $NgayCapPhep
 * @property string $TenDeTai
 * @property bool $LaDeTaiDich
 * @property string $TenNguyenBan
 * @property string $NguDuocDich
 * @property string $NguXuatBan
 * @property string $NamXuatBan
 * @property string $ThongTinSachDich
 * @property string $TacGia
 * @property string $DichGia
 * @property string $BienTapVien
 * @property string $DeTaiTuongTu
 * @property string $DC_TieuThu
 * @property string $DeCuong
 * @property int $ID_MangSach_CXB
 * @property int $ID_LoaiXBP
 * @property int $ID_BoSach
 * @property int $ID_TuSach
 * @property int $ID_DonVi
 * @property int $ID_DonViLK
 * @property int $ID_MonHoc
 * @property int $ID_MangSach
 * @property int $ID_Lop
 * @property int $ID_Cap
 * @property int $ID_DeTaiTB
 * @property bool $HTXB
 * @property bool $PTXB
 * @property string $ThoiDiemCoDuBT
 * @property string $ThoiDiemRaSach
 * @property string $NamTaiBan
 * @property string $DiaChi
 * @property int $SoTrangDK
 * @property string $Dai
 * @property string $Rong
 * @property int $GiaBia
 * @property int $LanTaiBan
 * @property int $SoLuongDK
 * @property int $MauInRuot
 * @property int $MauInBia
 * @property string $NoiDung
 * @property int $TrangThai
 * @property string $LiDo
 * @property string $GhiChu
 * @property string $MaVach
 * @property int $CreatedBy
 * @property DateTime $CreatedOn
 * @property int $EditedBy
 * @property DateTime $EditedOn
 * @property boolean $InUsed
 * @property boolean $IsDeleted
 * @property boolean $DaGui
 * @property string $KhoaGuiNhan
 * @property string $ISBNCode
 * @property string $MaSoQTG
 * @property DateTime $NgayCapQTG
 * @property int $VongThau
 * @property boolean $LaDeTaiCKH
 * @property string $ThongTinLienQuan
 * @property string $FMAVACH
 * @property string $YKHDDD
 * @property boolean $BanQuyen
 * @property boolean $CoMSISBN
 * @property boolean $IsXetDuyet
 * @property boolean $IsSachDienTu
 * @property string $DinhDangTep
 * @property string $DungLuongTep
 * @property string $DiaChiCungCap
 * @property int $ID_DetaiDKL
 * @property boolean $IsCancel
 * @property int $KieuBanQuyen
 * @property DateTime $BanQuyenTuNgay
 * @property DateTime $BanQuyenDenNgay
 * @property string $ThongTinBanQuyen
 * @property boolean $YeuCauDocKiemDinh
 * @property boolean $IsDangKyLai
 * @property boolean $IsDaDangKyLai
 * @property int $LoaiChinhSua
 * @property int $Id_DetaiCKH
 * @property DateTime $CreatedOnCKH
 * @property int $CreatedByCKH
 * @property int $ID_DV_INPH
 * @property string $SoHuuBanQuyen
 * @property string $LuaTuoi
 * @property int $TypeLuaTuoi
 * @property boolean $CanhBao
 * @property boolean $IsHDBS
 * @property boolean $isMa12KiTu
 * @property string $SoHDBS
 * @property DateTime $NgayKyHDBS
 * @property int $KieuHDBS
 * @property DateTime $TuNgayHDBS
 * @property DateTime $DenNgayHDBS
 * @property string $CapLopKhac
 * @property string $tenrutgon
 * @property string $TenPhieu
 * @property string $MoTa
 *
 * field mới thêm
 * @property array<int> $idListBTV chứa mảng id user là biên tập viên
 * @property DM_DONVI $DonVi chứa đơn vị của đề tài
 * @property DM_DONVI $DonViLK chứa đơn vị liên kết của đề tài
 * @property DM_BOSACH $BoSach chứa sách của đề tài
 * @property DM_MONHOC $MonHoc chứa môn học của đề tài
 * @property DM_LOP $Lop chứa lớp của đề tài
 * @property DM_CAP $Cap chứa cấp của đề tài
 * @property DM_MANGSACH $MangSach chứa mạng sách của đề tài
 * @property DM_MANGSACH_CXB $MangSachCXB chứa mạng sách của đề tài cấp xưởng
 * @property DM_LOAI_XBP $LoaiXBP chứa loại xưởng của đề tài
 * @property DM_DOITUONG $DoiTuong chứa đối tượng của đề tài
 *
 *
 *
 *
 */
class PHIEU_DK_DETAI extends Model {
    protected $connection = "olm";

    protected $table = "ipub_phieu_dk_detai";
	public $timestamps = true;
    protected $primaryKey = "_id";
    protected $keyType = "int";

    public $incrementing = true;
	public $timestamps2 = true;

    protected $fillable = [
        "_id",
        "NgayDk",
        "MaSo",
        "MaSoCXB",
        "SoGPXB",
        "NgayCapPhep",
        "TenDeTai",
        "LaDeTaiDich",
        "TenNguyenBan",
        "NguDuocDich",
        "NguXuatBan",
        "NamXuatBan",
        "ThongTinSachDich",
        "TacGia",
        "DichGia",
        "BienTapVien",
        "DeTaiTuongTu",
        "DC_TieuThu",
        "DeCuong",
        "ID_MangSach_CXB",
        "ID_LoaiXBP",
        "ID_BoSach",
        "ID_TuSach",
        "ID_DonVi",
        "ID_DonViLK",
        "ID_MonHoc",
        "ID_MangSach",
        "ID_Lop",
        "ID_Cap",
        "ID_DeTaiTB",
        "HTXB",
        "PTXB",
        "ThoiDiemCoDuBT",
        "ThoiDiemRaSach",
        "NamTaiBan",
        "DiaChi",
        "SoTrangDK",
        "Dai",
        "Rong",
        "GiaBia",
        "LanTaiBan",
        "SoLuongDK",
        "MauInRuot",
        "MauInBia",
        "NoiDung",
        "TrangThai",
        "LiDo",
        "GhiChu",
        "MaVach",
        "CreatedBy",
        "CreatedOn",
        "EditedBy",
        "EditedOn",
        "InUsed",
        "IsDeleted",
        "DaGui",
        "KhoaGuiNhan",
        "ISBNCode",
        "MaSoQTG",
        "NgayCapQTG",
        "VongThau",
        "LaDeTaiCKH",
        "ThongTinLienQuan",
        "FMAVACH",
        "YKHDDD",
        "BanQuyen",
        "CoMSISBN",
        "IsXetDuyet",
        "IsSachDienTu",
        "DinhDangTep",
        "DungLuongTep",
        "DiaChiCungCap",
        "ID_DetaiDKL",
        "IsCancel",
        "KieuBanQuyen",
        "BanQuyenTuNgay",
        "BanQuyenDenNgay",
        "ThongTinBanQuyen",
        "YeuCauDocKiemDinh",
        "IsDangKyLai",
        "IsDaDangKyLai",
        "LoaiChinhSua",
        "Id_DetaiCKH",
        "CreatedOnCKH",
        "CreatedByCKH",
        "ID_DV_INPH",
        "SoHuuBanQuyen",
        "LuaTuoi",
        "TypeLuaTuoi",
        "CanhBao",
        "IsHDBS",
        "isMa12KiTu",
        "SoHDBS",
        "NgayKyHDBS",
        "KieuHDBS",
        "TuNgayHDBS",
        "DenNgayHDBS",
        "CapLopKhac",
        "tenrutgon",
    ];

    /**
     * Giá trị mặc định khi khởi tạo model mới (khớp kiểu @property và mặc định form phía client).
     * Cột datetime có thể null sẽ để null; app/controller gán khi tạo bản ghi.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        "_id" => 0,
        "NgayDk" => null,
        "MaSo" => "",
        "MaSoCXB" => "",
        "SoGPXB" => "",
        "NgayCapPhep" => null,
        "TenDeTai" => "",
        "LaDeTaiDich" => false,
        "TenNguyenBan" => "",
        "NguDuocDich" => "",
        "NguXuatBan" => "Tiếng Việt",
        "NamXuatBan" => "",
        "ThongTinSachDich" => "",
        "TacGia" => "",
        "DichGia" => "",
        "BienTapVien" => "",
        "DeTaiTuongTu" => "",
        "DC_TieuThu" => "",
        "DeCuong" => "",
        "ID_MangSach_CXB" => 0,
        "ID_LoaiXBP" => 0,
        "ID_BoSach" => 0,
        "ID_TuSach" => 0,
        "ID_DonVi" => 0,
        "ID_DonViLK" => 0,
        "ID_MonHoc" => 0,
        "ID_MangSach" => 0,
        "ID_Lop" => 0,
        "ID_Cap" => 0,
        "ID_DeTaiTB" => 0,
        "HTXB" => false,
        "PTXB" => false,
        "ThoiDiemCoDuBT" => "",
        "ThoiDiemRaSach" => "",
        "NamTaiBan" => "",
        "DiaChi" => "",
        "SoTrangDK" => 0,
        "Dai" => "",
        "Rong" => "",
        "GiaBia" => 0,
        "LanTaiBan" => 0,
        "SoLuongDK" => 0,
        "MauInRuot" => 0,
        "MauInBia" => 0,
        "NoiDung" => "",
        "TrangThai" => 0,
        "LiDo" => "",
        "GhiChu" => "",
        "MaVach" => "",
        "CreatedBy" => 0,
        "CreatedOn" => null,
        "EditedBy" => 0,
        "EditedOn" => null,
        "InUsed" => true,
        "IsDeleted" => false,
        "DaGui" => false,
        "KhoaGuiNhan" => "",
        "ISBNCode" => "",
        "MaSoQTG" => "",
        "NgayCapQTG" => null,
        "VongThau" => 0,
        "LaDeTaiCKH" => false,
        "ThongTinLienQuan" => "",
        "FMAVACH" => "",
        "YKHDDD" => "",
        "BanQuyen" => false,
        "CoMSISBN" => false,
        "IsXetDuyet" => false,
        "IsSachDienTu" => false,
        "DinhDangTep" => "",
        "DungLuongTep" => "",
        "DiaChiCungCap" => "",
        "ID_DetaiDKL" => 0,
        "IsCancel" => false,
        "KieuBanQuyen" => 1,
        "BanQuyenTuNgay" => null,
        "BanQuyenDenNgay" => null,
        "ThongTinBanQuyen" => "",
        "YeuCauDocKiemDinh" => false,
        "IsDangKyLai" => false,
        "IsDaDangKyLai" => false,
        "LoaiChinhSua" => 0,
        "Id_DetaiCKH" => 0,
        "CreatedOnCKH" => null,
        "CreatedByCKH" => 0,
        "ID_DV_INPH" => 0,
        "SoHuuBanQuyen" => "",
        "LuaTuoi" => "",
        "TypeLuaTuoi" => 0,
        "CanhBao" => false,
        "IsHDBS" => false,
        "isMa12KiTu" => false,
        "SoHDBS" => "",
        "NgayKyHDBS" => null,
        "KieuHDBS" => 1,
        "TuNgayHDBS" => null,
        "DenNgayHDBS" => null,
        "CapLopKhac" => "",
        "tenrutgon" => "",
        "TenPhieu" => "",
        "MoTa" => "",
    ];

    protected $casts = [
        "NgayDk" => "datetime",
        "CreatedOn" => "datetime",
        "EditedOn" => "datetime",
    ];

    protected $customCasts = [];

    public function DonVi() {
        return $this->belongsTo(DM_DONVI::class, "ID_DonVi", "_id");
    }

    public function BoSach() {
        return $this->belongsTo(DM_BOSACH::class, "ID_BoSach", "_id");
    }

    public function MonHoc() {
        return $this->belongsTo(DM_MONHOC::class, "ID_MonHoc", "_id");
    }

    public function Lop() {
        return $this->belongsTo(DM_LOP::class, "ID_Lop", "_id");
    }

    public function Cap() {
        return $this->belongsTo(DM_CAP::class, "ID_Cap", "_id");
    }

    public function MangSach() {
        return $this->belongsTo(DM_MANGSACH::class, "ID_MangSach", "_id");
    }

    public function MangSachCXB() {
        return $this->belongsTo(DM_MANGSACH_CXB::class, "ID_MangSach_CXB", "_id");
    }

    public function DonViLK() {
        return $this->belongsTo(DM_DONVI::class, "ID_DonViLK", "_id");
    }

    public function LoaiXBP() {
        return $this->belongsTo(DM_LOAI_XBP::class, "ID_LoaiXBP", "_id");
    }

    public function DoiTuong() {
        return $this->belongsTo(DM_DOITUONG::class, "TypeLuaTuoi", "_id");
    }
}
