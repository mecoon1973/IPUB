import type { Chucvu } from "../../system/type/ChucVu";
import type { Chuyenmon } from "../../system/type/ChuyenMon";
import type { DonVi } from "./DonVi";

export interface User {
    id: number;
    MaCanBo: string;
    HoTen: string;
    NgaySinh: Date;
    ID_ChucVu: number;
    ChucVuText: string;
    ID_DonVi?: number;
    ID_ChuyenMon?: number;
    SoDienThoai: string;
    Email: string;
    DiaChi: string;
    password?: string;
    UserName: string;
    IsActive?: boolean;
    IsEditor?: boolean;
    UserThemes?: string;
    NgayHetHan?: Date;
    SoLuongBanGhi?: number;
    ID_Scale?: number;
    NguoiKi?: boolean;
    InUsed?: boolean;
    IsDeleted?: boolean;
    DaGui?: boolean;
    KhoaGuiNhan?: string;
    MaSoChungChi?: string;
    NgayCap?: number;
    NoiCap?: string;
    ChucDanhBienTap?: string;
    isSpecial?: boolean;
    KyQDXB?: boolean;
    UQKyQDXB?: boolean;
    NguoiSoanThao?: boolean;
    KyNhayQDXB?: boolean;
    ID_VSSIGN?: string;
    SignatureUrl_VSSIGN?: string;
    isActive_VSSIGN?: boolean;

    nhom_ids: number[];
    quyen_ids: number[];

    chucvu?: Chucvu;
    donvi?: DonVi;
    chuyenmon?: Chuyenmon;
}
