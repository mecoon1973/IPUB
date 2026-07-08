export interface FilterTokhaiLuuChuyen {
    TieuDe?: string;
    NoiNop?: "cuc" | "thu-vien";
    IsDeleted?: boolean;
}

export interface ToKhaiLuuChuyen {
    id: number;
    TieuDe: string;
    NgayTao: Date;
    NguoiTao: number;
    NoiNhan: string;
    NguoiKhai: string;
    isCucXB: boolean;
    inUsed: boolean;
    isDeleted: boolean;
    NgayXacNhan: Date;
    NguoiSua: number;
    NguoiNhan: string;
    SoThuTuTu: number;
    SoThuTuDen: number;
    NgayBatDau: Date;
    NgayKetThuc: Date;
    MaDonVi: string;
    HTXB: boolean;
    LaNoiBan: boolean;
    SoTK: number;
    SoTKText: string;
}
