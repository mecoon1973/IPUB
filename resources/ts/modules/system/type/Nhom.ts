export interface Nhom {
    id: number;
    MaNhomNSD: string;
    TenNhomNSD: string;
    InUsed: boolean;
    IsDeleted: boolean;
    DaGui?: boolean;
    KhoaGuiNhan?: string;
    countCanbo?: number;

    listIdQuyen: number[];
}
