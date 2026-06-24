
export interface HDXB {
    id: number;
    MaHDXB: string;
    TenHDXB: string;
    ThuTu: number;
    IsDeleted: boolean;
    Description: string;
    InUsed: boolean;
    DaGui?: boolean;
    KhoaGuiNhan?: string;
    EditedOn?: number;
    EditedBy?: number;
    CreateBy?: number;
    CreateOn?: number;
}

export interface Quyen {
    id: number;
    ParentID: number;
    ThuTu: number;
    MaQuyen: string;
    TenQuyen: string;
    IsDeleted: boolean;
    InUsed: boolean;

    listIdFunctions: number[];
}

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

export interface ChucNang {
    id: number;
    Code: string;
    Title: string;
    ParentID: number;
    NodeID: string;
    Href: string;
    Leaf: boolean;
    ChildFunctionCode: string;
    NameID: string;
    Visible: boolean;
    Root: boolean;
    Position: number;
    Description: string;
    Deleted: boolean;
    NotChange: boolean;
    StatusCode: string;
    PhanHeID: number;
    Order: number;
    FunctionCode: string;
    OnMenu: boolean;
    Icon: string;
    Crumb: string;
    Target: string;
    isLinkFull: boolean;

    ThuTu: number;
}

export interface PhanHe {
    id: number;
    Code: string;
    TenPhanHe: string;
    Type: string;
    Order: number;
}

export interface Lop {
    id: number;
    MaLop: string;
    TenLop: string;
    KiHieu: string;
    IsDeleted: boolean;
    InUsed: boolean;
    DaGui?: boolean;
    KhoaGuiNhan?: string;
}

export interface Monhoc {
    id: number;
    MaMonHoc: string;
    TenMonHoc: string;
    MoTa: string;
    KiHieu: string;
    IsDeleted: boolean;
    IsUsed: boolean;
    DaGui?: boolean;
    KhoaGuiNhan?: string;
}

export interface Mangsach {
    id: number;
    MaMang: string;
    TenMang: string;
    MoTa: string;
    KiHieu: string;
    ParentID: number;
    VAT: number;
    iOrder: number;
    IsDeleted: boolean;
    IsUsed: boolean;
    /** @description (ThuTu = iOrder) giá trị ThuTu này không có trong database đang được khai báo ở lúc lấy api lên (mục đích thêm để sắp xếp chúng ở cây) */
    ThuTu?: number;
    DaGui?: boolean;
    KhoaGuiNhan?: string;
}

export interface Bosach {
    id: number;
    MaBo: string;
    TenBo: string;
    MoTa: string;
    KiHieu: string;
    IsDeleted: boolean;
    IsUsed: boolean;
}

export interface Doituong {
    id : number;
    MaDoiTuong : string;
    TenDoiTuong : string;
    MoTa : string;
    KiHieu : string;
    IsDeleted : boolean;
    DaGui : boolean;
    InUsed : boolean;
    type : string;
}

export interface Tusach {
    id: number;
    MaTuSach: string;
    TenTuSach: string;
    MoTa: string;
    IsDeleted?: boolean;
    InUsed?: boolean;
}

export interface Chuyenmon {
    id: number;
    TenChuyenMon: string;
    MoTa: string;
    IsDeleted?: boolean;
    InUsed?: boolean;
}

export interface Chucvu {
    id: number;
    MaChucVu: string;
    TenChucVu: string;
    MoTa: string;
    IsDeleted?: boolean;
    InUsed?: boolean;
}

export interface LoaiXBP {
    id: number;
    MaLoai: string;
    TenLoai: string;
    KiHieu: string;
    MoTa: string;
    Type: number;
    IsDeleted?: boolean;
    InUsed?: boolean;
}

export interface MangsachCXB {
    id: number;
    MaMang: string;
    TenMang: string;
    MoTa: string;
    IsDeleted?: boolean;
    InUsed?: boolean;
}

export interface Ngoaingu {
    id: number;
    MaNgoaiNgu: string;
    TenNgoaiNgu: string;
    ThuTu: number;
    IsDeleted?: boolean;
    InUsed?: boolean;
}

export interface Congviecchebanin {
    id: number;
    MaCongViec: string;
    TenCongViec: string;
    MoTa: string;
    IsDeleted?: boolean;
    InUsed?: boolean;
}

export interface Congviecthietke {
    id: number;
    MaCongViec: string;
    TenCongViec: string;
    DVT: string;
    IsDeleted?: boolean;
    InUsed?: boolean;
}

export interface SystemLog {
    id: number;
    UserID: number;
    Desc: string;
    IPAddress: string;
    ActionTime: Date;
    InUse: boolean;
}

export interface SystemLogFilter {
    accountName?: string;
    userName?: string;
    content?: string;
    id_Dv?: number;
    startDate?: Date;
    endDate?: Date;
}

export interface BienMoiTruong {
    id: number;
    ConfigName: string;
    ConfigNotes: string;
    ConfigValue: string;
    IsDeleted?: boolean;
    InUsed?: boolean;
    CreateBy?: number;
    CreatedOn?: Date;
    EditedBy?: number;
    EditedOn?: Date;
    AllowDelete?: boolean;
    AllowEdit?: boolean;
}

export interface FilterBienMoiTruong {
    ConfigSearch: string;
    id_Dv: number;
}

/**
 * Đơn vị lưu chuyển
 */
export interface DonviLC {
    id: number;
    Ten: string;
    ThuTu: number;
    KhoaGuiNhan: string;
    IsDeleted: boolean;
    InUsed: boolean;
    DaGui: boolean;
    LoaiXbpLc: SimpleLoaiXbpLc[];
}

export interface SimpleLoaiXbpLc {
    ID_LOAI_XBP_LC: number;
    SoLuong: number;
}

/**
 * Loại xuất bản lưu chiểu
 * */
export interface LoaiXbpLc {
    id: number;
    TenLoai: string;
    IsDeleted: boolean;
    InUsed: boolean;
    DaGui: boolean;
}


/**
 * Đối tượng nhận sách nghiệp vụ
 * */
export interface DoituongSNV {
    id: number;
    TenDonVi: string;
    ThuTu: number;
    IsDeleted: boolean;
    InUsed: boolean;
    DaGui: boolean;

    /** chưa convert dữ liệu dưới backend */
    listLoaiSNV: {
        id: number;
        SoLuong: number;
    }[];
}

export interface LoaiSnv {
    id: number;
    TenLoai: string;
    MangSach: string;
    ID_MangSach: number;
    IsDeleted: boolean;
    InUsed: boolean;
}
