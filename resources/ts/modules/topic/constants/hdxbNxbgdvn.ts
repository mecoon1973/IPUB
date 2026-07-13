/** Lọc theo phân công đọc duyệt — form quản lý HĐXB NXBGDVN */
export const HDXBNXBGDVN_LOC_THEO = {
    TAT_CA: -1,
    DUOC_PHAN_CONG_TAT_CA: 1,
    DUOC_PHAN_CONG_CA_NHAN: 2,
    CHUA_PHAN_CONG: 0,
} as const;

export const HDXBNXBGDVN_LOC_THEO_OPTIONS = [
    { value: HDXBNXBGDVN_LOC_THEO.TAT_CA, label: "Tất cả" },
    { value: HDXBNXBGDVN_LOC_THEO.DUOC_PHAN_CONG_TAT_CA, label: "Được phân công (tất cả)" },
    { value: HDXBNXBGDVN_LOC_THEO.DUOC_PHAN_CONG_CA_NHAN, label: "Được phân công (cá nhân)" },
    { value: HDXBNXBGDVN_LOC_THEO.CHUA_PHAN_CONG, label: "Chưa phân công" },
] as const;

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

/** Kết luận trên modal đọc duyệt đề tài */
export const DOC_DUYET_KET_LUAN_OPTIONS = [
    { value: NX_CANBO_DETAI_DUYET.DUYET, label: "Đồng ý" },
    { value: NX_CANBO_DETAI_DUYET.TRA_LAI, label: "Trả lại" },
] as const;

/** Trạng thái đọc duyệt bản thảo — ipub_dm_sach.TrangThaiDocBanThao */
export const TRANG_THAI_DOC_BAN_THAO = {
    CHUA_DOC_DUYET: 0,
    DANG_DOC_DUYET: 1,
    DA_DOC_DUYET: 2,
} as const;

/** Kết luận trên modal phê duyệt đi in — map tới XetDuyetBanThao */
export const PHE_DUYET_DI_IN_KET_LUAN_OPTIONS = [
    { value: 0, label: "Chưa duyệt" },
    { value: 1, label: "Duyệt" },
] as const;

export const PHE_DUYET_DI_IN_TRANG_THAI_OPTIONS = [
    { value: -1, label: "Tất cả" },
    { value: TRANG_THAI_DOC_BAN_THAO.CHUA_DOC_DUYET, label: "Chưa đọc duyệt" },
    { value: TRANG_THAI_DOC_BAN_THAO.DA_DOC_DUYET, label: "Đã đọc duyệt" },
    { value: TRANG_THAI_DOC_BAN_THAO.DANG_DOC_DUYET, label: "Đang đọc duyệt" },
] as const;

/** Lọc theo phân công đọc duyệt */
export const PHE_DUYET_DI_IN_LOC_THEO_OPTIONS = [
    { value: -1, label: "Tất cả" },
    { value: 1, label: "Được phân công" },
] as const;

const TRANG_THAI_DOC_BAN_THAO_LABEL: Record<number, string> = {
    [TRANG_THAI_DOC_BAN_THAO.CHUA_DOC_DUYET]: "Chưa đọc duyệt",
    [TRANG_THAI_DOC_BAN_THAO.DANG_DOC_DUYET]: "Đang đọc duyệt",
    [TRANG_THAI_DOC_BAN_THAO.DA_DOC_DUYET]: "Đã đọc duyệt",
};

export function getTrangThaiDocBanThaoLabel(trangThai: number, tenTrangThai?: string): string {
    if (tenTrangThai) {
        return tenTrangThai;
    }
    return TRANG_THAI_DOC_BAN_THAO_LABEL[trangThai] ?? String(trangThai);
}

export function getPheDuyetDiInTrangThaiLabel(daPheDuyet: boolean, tenTrangThai?: string): string {
    if (tenTrangThai) {
        return tenTrangThai;
    }
    return daPheDuyet ? "Đã phê duyệt đi in" : "Chưa phê duyệt đi in";
}

/** Kết luận xét duyệt bản thảo — ipub_dm_sach.XetDuyetBanThao */
export function getXetDuyetBanThaoLabel(xetDuyet?: boolean): string {
    const value = xetDuyet ? 1 : 0;
    return PHE_DUYET_DI_IN_KET_LUAN_OPTIONS.find((opt) => opt.value === value)?.label ?? "Chưa duyệt";
}
