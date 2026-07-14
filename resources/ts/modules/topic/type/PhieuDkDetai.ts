import type { Relationships } from "../../page/type";
import type { User } from "../../user/type";

export enum KieuBanQuyen {
    CO_THOI_HAN = 1,
    VO_THOI_HAN = 2
}

export interface PhieuDkDetai {
    id: number;
    NgayDK: Date;
    MaSo: string;
    MaSoCXB: string;
    SoGPXB: string;
    NgayCapPhep: Date;
    TenDeTai: string;
    LaDeTaiDich: boolean;
    TenNguyenBan: string;
    NguDuocDich: string;
    NguXuatBan: string;
    NamXuatBan: string;
    ThongTinSachDich: string;
    TacGia: string;
    DichGia: string;
    BienTapVien: string;
    idListBTV: number[];
    DeTaiTuongTu: string;
    DC_TieuThu: string;
    DeCuong: string;
    ID_MangSach_CXB: number;
    ID_LoaiXBP: number;
    ID_BoSach: number;
    ID_TuSach: number;
    ID_DonVi: number;
    ID_DonViLK: number;
    ID_MonHoc: number;
    ID_MangSach: number;
    ID_Lop: number;
    ID_Cap: number;
    ID_DeTaiTB: number;
    HTXB: boolean;
    PTXB: boolean;
    ThoiDiemCoDuBT: string;
    ThoiDiemRaSach: string;
    NamTaiBan: string;
    DiaChi: string;
    SoTrangDK: number;
    Dai: string;
    Rong: string;
    GiaBia: number;
    LanTaiBan: number;
    SoLuongDK: number;
    MauInRuot: number;
    MauInBia: number;
    NoiDung: string;
    TrangThai: number;
    LiDo: string;
    GhiChu: string;
    MaVach: string;
    CreatedBy: number;
    CreatedOn: Date;
    EditedBy: number;
    EditedOn: Date;
    InUsed: boolean;
    IsDeleted: boolean;
    DaGui: boolean;
    KhoaGuiNhan: string;
    ISBNCode: string;
    MaSoQTG: string;
    NgayCapQTG: Date;
    VongThau: number;
    LaDeTaiCKH: boolean;
    ThongTinLienQuan: string;
    FMAVACH: string;
    YKHDDD: string;
    BanQuyen: boolean;
    CoMSISBN: boolean;
    IsXetDuyet: boolean;
    IsSachDienTu: boolean;
    DinhDangTep: string;
    DungLuongTep: string;
    DiaChiCungCap: string;
    ID_DetaiDKL: number;
    IsCancel: boolean;
    KieuBanQuyen: KieuBanQuyen;
    BanQuyenTuNgay: Date;
    BanQuyenDenNgay: Date;
    ThongTinBanQuyen: string;
    YeuCauDocKiemDinh: boolean;
    IsDangKyLai: boolean;
    IsDaDangKyLai: boolean;
    LoaiChinhSua: number;
    Id_DetaiCKH: number;
    CreatedOnCKH: Date;
    CreatedByCKH: number;
    ID_DV_INPH: number;
    SoHuuBanQuyen: string;
    LuaTuoi: string;
    TypeLuaTuoi: number;
    CanhBao: boolean;
    IsHDBS: boolean;
    isMa12KiTu: boolean;
    SoHDBS: string;
    NgayKyHDBS: Date;
    KieuHDBS: number;
    TuNgayHDBS: Date;
    DenNgayHDBS: Date;
    CapLopKhac: string;
    tenrutgon: string;
    TenPhieu: string;
    MoTa: string;
}

/** Union các key của `PhieuDkDetai` có giá trị `Date` — dùng khi serialize gửi API (ví dụ `PhieuDkDetaiApi`). */
export type PhieuDkDetaiDateKey = {
    [K in keyof PhieuDkDetai]: PhieuDkDetai[K] extends Date ? K : never;
}[keyof PhieuDkDetai];

export type FilterPhieuDkDetai = Partial<
    Pick<PhieuDkDetai,
        "MaSo" |
        "TenDeTai" |
        "TacGia" |
        "NamXuatBan" |
        "BienTapVien" |
        "ID_MangSach" |
        "ID_DonVi" |
        "TrangThai" |
        "IsDeleted"
    >
> & {
    /** Lọc theo khoảng ngày đăng ký (RangePicker: [từ ngày, đến ngày]) */
    NgayDK?: Date[];
    HTXB?: number;
} & Relationships;

export const defaultFilterPhieuDkDetai: FilterPhieuDkDetai = {
    MaSo : "",
    TenDeTai : "",
    TacGia : "",
    NamXuatBan : "",
    BienTapVien : "",
    ID_MangSach : 0,
    HTXB : -1,
    ID_DonVi : 0,
    TrangThai : -1,
    IsDeleted: false,
    NgayDK: [] as Date[],
};

// Công đoạn đề tài
export interface Detai_Congdoan {
    id: number;
    IDCongDoan: number;
    IDDeTai: number;
    IDSach: number;
    MaCD: string;
    GhiChu: string;
    NewValue: string;
    NoiDung: string;
    OldValue: string;
    CreatedOn: Date;
    CreatedBy: number;
    EditedOn: Date;
    EditedBy: number;
    IsDeleted: boolean;

    user_create?: User;
}

export type FilterDetaiCongdoan = Partial<Detai_Congdoan> & Relationships;
//
// Quyết định in

export interface QDIn {
    id: number;
    CanCu: string;
    DiaDanh: string;
    HTXB: number;
    ID_DVQD_VMS: number;
    ID_DV_QD: number;
    ID_MangSachQDIN: number;
    ID_NguoiKi: number;
    ID_VMS: string;
    MaDonviQD: string;
    NamKeHoach: string;
    NoiNhan: string;
    SoQD: string;
    TenDonViQD: string;
    TenDonVi_VMS: string;
    TenNguoiKi: string;
    TieuDe: string;
    UserName_VMS: string;
    SoQDTuTang: number;
    NgayQD: Date;
}

export interface QDInFilter extends Partial<QDIn> {
    startDate: Date | null;
    endDate: Date | null;
};
//

// Chi tiết quyết định in
export interface CT_QD_In {
    id: number;
    ID_QD_IN: number;
    ID_Sach: number;
    ID_QDXB: number;
    THBS: string;
    SoLuongSauDieuChinh: number;
    SoLuongIn: number;
    ThoiHanNhapKho: Date;
    ID_DV_IN: number;
    TenDonViIn: string;
    GhiChu: string;
    CreatedBy: number;
    CreatedOn: Date;
    EditedBy: number;
    EditedOn: Date;
    IsDeleted: boolean;
    IsUsed: boolean;
    DaGui: boolean;
    KhoaGuiNhan: string;
    GiayInRout: string;
    GiayInBia: string;
    HDXB: boolean;
    LanTaiBan: number;
    SoTrang: number;
    KhoSach: string;
    GiayBia: string;
    MauInRuot: string;
    MauInBia: string;
    BienTapVien: string;
    MaSoSach: string;
    TenSach: string;
    MaSoCXB: string;
    MaDonViIn: string;
    TacGia: string;
    IDCT_VMS: string;
    TinhTrangXuatBan: boolean;
    DiaChiDonViIn: string;
    IsQDXB: boolean;
    MaSachVMS: string;
    TenCoSoIn: string;
    MaCoSoIn: string;
    IdCoSoIn: string;
    IsSachDienTu: boolean;
    DinhDangTep: string;
    DungLuongTep: string;
    DiaChiCungCap: string;
    LyDoDieuChinhSoLuong: string;
    IsNoiBan: boolean;
    LanNoiBan: number;
}

// Hợp đồng xuất bản - NXBGDVN
export interface HDXBNXBGDVN {
    id: number;
    TenDeTai: string;
    NamTaiBan?: string;
    NamXuatBan?: string;
    NguoiDocDuyet?: string;
    BienTapVien?: string;
    TenDonVi?: string;
    TrangThai: number;
    TenTrangThai?: string;
    PhanCong?: string;
    DaPhanCong?: boolean;
    ID_CanBoDocDuyet?: number;
    ID_DonVi?: number;
    MaSo?: string;
}

export type FilterHDXBNXBGDVN = Partial<
    Pick<HDXBNXBGDVN, "TenDeTai" | "TrangThai">
> & {
    ID_DonVi?: number;
    PhanCong?: number;
} & Relationships;

export const defaultFilterHDXBNXBGDVN: FilterHDXBNXBGDVN = {
    TenDeTai: "",
    ID_DonVi: 0,
    PhanCong: -1,
    TrangThai: -1,
};

export interface HDXBNXBGDVNXetDuyetRow {
    id: number;
    idNxCanBoDetai: number;
    TenDeTai: string;
    YKienDocDuyet: string;
    YKienHDXB: string;
    Duyet: number;
    YeuCauDocKiemDinh: boolean;
}

export interface HDXBNXBGDVNDocDuyetRow {
    id: number;
    idNxCanBoDetai: number;
    TenDeTai: string;
    TacGia: string;
    KhoSach: string;
    SoTrang: number;
    YKienNhanXet: string;
    ThongTinLienQuan: string;
    Duyet: number;
}

export interface FilterXetDuyetHDXBNXBGDVN {
    TuNgay?: string | null;
    DenNgay?: string | null;
    ID_DonVi?: number;
    ids?: number[];
}

export interface PheDuyetDiInRow {
    id: number;
    ID_DeTai: number;
    MaSo: string;
    TenSach: string;
    NamTaiBan?: string;
    NamXuatBan?: string;
    TenDonVi?: string;
    TrangThaiDocBanThao?: number;
    YKienDocBanThao?: string;
    XetDuyetBanThao?: boolean;
    DaPheDuyetDiIn: boolean;
    TenTrangThai?: string;
}

export interface FilterPheDuyetDiIn {
    TenSach?: string;
    MaSo?: string;
    NamXBTB?: string;
    ID_DonVi?: number;
    LocTheo?: number;
    TrangThai?: number;
    idsDeTai?: number[];
}

export const defaultFilterPheDuyetDiIn: FilterPheDuyetDiIn = {
    TenSach: "",
    MaSo: "",
    NamXBTB: "",
    ID_DonVi: 0,
    LocTheo: -1,
    TrangThai: -1,
};

export interface PheDuyetDiInLuuItem {
    id: number;
    YKienDocBanThao: string;
    XetDuyetBanThao: boolean;
}

// Phiếu đăng ký kế hoạch xuất bản — Cục xuất bản
export interface PhieuDkKhxbCxb {
    id: number;
    MaSo: string;
    TieuDe: string;
    NoiDung: string;
    NoiNhan2: string;
    PhanDauMaSo: string;
    SoCvNXBGD: string;
    SoGiayPhep: string;
    NgayDK: Date;
    NgayCapPhep: Date;
    ID_NguoiKi: number | null;
    KiThay: boolean;
    DaGui: boolean;
    InUsed: boolean;
    IsDeleted: boolean;
    KhoaGuiNhan: string;
    CreatedBy: number;
    CreatedOn: Date;
    EditedBy: number;
    EditedOn: Date;
}

export interface FilterPhieuDkKhxbCxb {
    TuKhoa?: string;
    startDate?: Date | null;
    endDate?: Date | null;
}

export const defaultFilterPhieuDkKhxbCxb: FilterPhieuDkKhxbCxb = {
    TuKhoa: "",
    startDate: null,
    endDate: null,
};

export interface StorePhieuDkKhxbCxbPayload {
    id?: number;
    MaSo?: string;
    TieuDe: string;
    NoiDung: string;
    NoiNhan2?: string;
    NgayDK?: Date | null;
    ID_NguoiKi?: number | null;
    KiThay?: boolean;
    listIdDeTai?: number[];
}

export interface CapMaSoCxbPayload {
    idPhieu: number;
    SoCvCxb: string;
    SoCvNxbgd: string;
    NgayCap?: Date | null;
    NamCap?: string;
    MaSoCxb: string;
}

export interface CapMaIsbnItem {
    id: number;
    ISBNCode: string;
}

export interface CapMaIsbnPayload {
    idPhieu: number;
    listIsbn: CapMaIsbnItem[];
}

export interface KetChuyenThanhSachPayload {
    idPhieu: number;
    listIdDeTai: number[];
}

export interface XetDuyetPhieuDkKhxbCxbRow {
    id: number;
    idCt: number;
    TenDeTai: string;
    TenDonVi: string;
    TrangThai: number;
}

export interface LuuXetDuyetPhieuDkKhxbCxbPayload {
    idPhieu: number;
    items: Array<{
        idDeTai: number;
        TrangThai: number;
    }>;
}

//
