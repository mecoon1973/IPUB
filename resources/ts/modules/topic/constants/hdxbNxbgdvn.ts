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
