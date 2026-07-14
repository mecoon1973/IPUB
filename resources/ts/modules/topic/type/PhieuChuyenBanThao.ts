import type { Relationships } from "../../page/type";

export interface PhieuChuyenBanThaoSach {
    id?: number;
    MaSo?: string;
    TenSach?: string;
    MaSoCXB?: string;
    ISBNCode?: string;
    NamXuatBan?: string;
    NamTaiBan?: string;
    ID_MangSach?: number;
}

export interface PhieuChuyenBanThao {
    id: number;
    ID_Sach: number | null;
    ID_DeTai: number | null;
    ID_DV: number | null;
    ID_MangSach: number | null;
    ID_BTVNhan: number | null;
    ID_ListBienTapVien: string;
    /** Danh sách id BTV dùng trên form (client) */
    idListBTV?: number[];
    ID_NguoiKy: number | null;
    NgayGiao: Date | null;
    NgayNhan: Date | null;
    NguoiGiao: string;
    NguoiNhan: string;
    TacGia: string;
    BienTapVien: string;
    SoTrang: number;
    SoTrangRuotSach: number;
    SoTrangPhuBan: number;
    SoBo: number;
    SoBoBanThao: number;
    SoBoBiaMau: number;
    SoBoPhimBia: number;
    Dai: number;
    Rong: number;
    MauInBia: number;
    MauInRout: number;
    SoMauInBia: number | null;
    LanIn: number;
    CheBanCan: boolean;
    CoAoBoc: boolean;
    LoaiBia: boolean;
    LoaiPhieu: boolean;
    IsSachDienTu: boolean;
    MaDVIN: string;
    DiaChiCungCap: string;
    DinhDangTep: string;
    DungLuongTep: string;
    GhiChu: string;
    sach?: PhieuChuyenBanThaoSach | null;
    donvi?: {
        id?: number;
        TenDonVi?: string;
    } | null;
    nguoiKy?: {
        id?: number;
        HoTen?: string;
    } | null;
}

export interface FilterPhieuChuyenBanThao extends Relationships {
    TuKhoa?: string;
    startDate?: Date | null;
    endDate?: Date | null;
    ID_DV?: number | null;
    IsDeleted?: boolean;
}

export const defaultFilterPhieuChuyenBanThao: FilterPhieuChuyenBanThao = {
    TuKhoa: "",
    startDate: null,
    endDate: null,
    ID_DV: null,
};

export function parseIdListBienTapVien(value?: string | null): number[] {
    if (!value?.trim()) {
        return [];
    }
    return value
        .split(",")
        .map((item) => Number(item.trim()))
        .filter((id) => !Number.isNaN(id) && id > 0);
}

export function formatIdListBienTapVien(ids: number[]): string {
    return ids.filter((id) => id > 0).join(",");
}

export function buildBienTapVienLabel(ids: number[], listBTV: { id: number; HoTen?: string }[]): string {
    return ids
        .map((id) => listBTV.find((user) => user.id === id)?.HoTen?.trim())
        .filter((name): name is string => Boolean(name))
        .join(", ");
}
