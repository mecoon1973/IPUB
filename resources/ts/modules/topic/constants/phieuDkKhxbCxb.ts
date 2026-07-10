export const PHIEU_DK_KHXB_CXB_XET_DUYET_TRANG_THAI = [7, 8, 9] as const;

const FALLBACK_MAP_TRANG_THAI: Record<number, string> = {
    7: "16. CXB trả lại",
    8: "15. CXB đang xét duyệt",
    9: "14. CXB phê duyệt",
};

export type PhieuDkKhxbCxbXetDuyetTrangThai = (typeof PHIEU_DK_KHXB_CXB_XET_DUYET_TRANG_THAI)[number];

export function normalizeMapTrangThai(mapTrangThai: Record<number, string>): Record<number, string> {
    const normalized: Record<number, string> = { ...FALLBACK_MAP_TRANG_THAI };
    Object.entries(mapTrangThai ?? {}).forEach(([key, label]) => {
        const id = Number(key);
        if (!Number.isNaN(id) && label) {
            normalized[id] = label;
        }
    });
    return normalized;
}

export function isPhieuDkKhxbCxbXetDuyetTrangThai(value: number): value is PhieuDkKhxbCxbXetDuyetTrangThai {
    return (PHIEU_DK_KHXB_CXB_XET_DUYET_TRANG_THAI as readonly number[]).includes(value);
}

/** Chuẩn hóa trạng thái khởi tạo về nhóm CXB xét duyệt để select hiển thị label, không phải số thô. */
export function normalizeTrangThaiXetDuyetCxb(trangThai: number): PhieuDkKhxbCxbXetDuyetTrangThai {
    if (isPhieuDkKhxbCxbXetDuyetTrangThai(trangThai)) {
        return trangThai;
    }
    return 8;
}

export function getTrangThaiLabel(mapTrangThai: Record<number, string>, trangThai: number): string {
    const map = normalizeMapTrangThai(mapTrangThai);
    return map[trangThai] ?? String(trangThai);
}

export function buildPhieuDkKhxbCxbTrangThaiOptions(mapTrangThai: Record<number, string>) {
    const map = normalizeMapTrangThai(mapTrangThai);
    return PHIEU_DK_KHXB_CXB_XET_DUYET_TRANG_THAI.map((value) => ({
        value,
        label: map[value] ?? String(value),
    }));
}

export function buildTrangThaiOptionsForRow(
    trangThai: number,
    mapTrangThai: Record<number, string>,
) {
    const options = buildPhieuDkKhxbCxbTrangThaiOptions(mapTrangThai);
    const normalized = normalizeTrangThaiXetDuyetCxb(trangThai);
    if (!options.some((option) => option.value === normalized)) {
        return [
            {
                value: normalized,
                label: getTrangThaiLabel(mapTrangThai, normalized),
            },
            ...options,
        ];
    }
    return options;
}
