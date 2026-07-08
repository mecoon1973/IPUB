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
}
