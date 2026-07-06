import type { Relationships } from "../../page/type";

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
