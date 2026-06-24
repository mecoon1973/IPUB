import type { Relationships } from '../page/type';
import type { Sach } from '../topic/type';
export interface FilterPhieuNhapLC extends Relationships {
    TuKhoa?: string;
    TuNgay?: Date;
    DenNgay?: Date;
    IsDeleted?: boolean;
}

export interface FilterTokhaiLuuChuyen {
    TieuDe?: string;
    NoiNop?: "cuc" | "thu-vien";
    IsDeleted?: boolean;
}

export interface PhieuNhapLC {
    id: number;
    NgayNhap: Date;
    SoPhieu: number;
    SoChungTu: number;
    ID_Sach: number;
    TenSach: string;
    ID_LoaiSachLC: number;
    SoLuongIn: number;
    SoLuong: number;
    ID_DV_In: number;
    DonViIn: string;
    LaInNoiBan: boolean;
    GhiChu: string;
    CreatedBy: number;
    CreatedOn: Date;
    EditedBy: number;
    EditedOn: Date;
    InUsed: boolean;
    IsDeleted: boolean;
    DaGui: boolean;
    KhoaGuiNhan: string;
    TacGia: string;
    SoTrang: number;
    KhoSach: string;
    GiaBia: number;
    HTXB: boolean;
    LanTaiBan: number;
    BienTapVien: string;
    NgayCXBXacNhan: Date;
    BienDich: string;
    NgonNguDichSach: string;
    NguXuatBanSach: string;
    TheLoaiSach: string;
    DiaChiInSach: string;
    SoTap: number;
    ID_QDXB: number;
    SoQuyetDXB: number;
    NgayQD: Date;
    LoaiSach: boolean;
    DiaChiWebSachDienTu: string;
    TenDonViLK: string;
    DiaChiDonViLK: string;
    SoVB: number;
    DaCapQDPH: boolean;
    TenCoSoIn: string;
    DungLuongTep: string;
    DinhDangTep: string;

    // relationships
    sach?: Sach;
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
