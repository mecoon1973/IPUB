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
