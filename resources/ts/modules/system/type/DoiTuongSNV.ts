/** Đối tượng nhận sách nghiệp vụ */
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
