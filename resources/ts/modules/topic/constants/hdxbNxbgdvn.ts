/** Lọc trạng thái trên form tìm kiếm HĐXB NXBGDVN */
export const HDXBNXBGDVN_TRANG_THAI_FILTER_OPTIONS = [
    { value: -1, label: "Tất cả" },
    { value: 6, label: "Đã xử lý" },
    { value: 5, label: "Đang xử lý" },
    { value: 4, label: "Đề tài bị trả lại" },
] as const;

/** Nhãn trạng thái hiển thị trên bảng (theo giao diện quản lý) */
export const HDXBNXBGDVN_TRANG_THAI_DISPLAY: Record<number, string> = {
    4: "Đề tài bị trả lại",
    5: "Đang xử lý",
    6: "Đã xử lý",
    16: "Đang xử lý",
};

export function getHDXBNXBGDVNTrangThaiLabel(
    trangThai: number,
    tenTrangThai?: string,
    mapTrangThai?: Record<number, string>,
): string {
    if (tenTrangThai) {
        return tenTrangThai;
    }
    return HDXBNXBGDVN_TRANG_THAI_DISPLAY[trangThai] ?? mapTrangThai?.[trangThai] ?? "";
}

/** Giá trị trường Duyet trong ipub_nx_canbo_detai */
export const NX_CANBO_DETAI_DUYET = {
    CHUA_XET: 0,
    DUYET: 1,
    TRA_LAI: 2,
} as const;

export const NX_CANBO_DETAI_DUYET_OPTIONS = [
    { value: NX_CANBO_DETAI_DUYET.CHUA_XET, label: "Chưa xử lý" },
    { value: NX_CANBO_DETAI_DUYET.DUYET, label: "Duyệt" },
    { value: NX_CANBO_DETAI_DUYET.TRA_LAI, label: "Trả lại" },
] as const;
