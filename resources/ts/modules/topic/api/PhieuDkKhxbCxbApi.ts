import type { PagiResult } from "../../page/type";
import type { CapMaIsbnPayload, CapMaSoCxbPayload, FilterPhieuDkKhxbCxb, KetChuyenThanhSachPayload, PhieuDkDetai, PhieuDkKhxbCxb, StorePhieuDkKhxbCxbPayload } from "../type";
import { formatDateToIso8601UtcOffset } from "../../core/utils/helpersDayjs";

export class PhieuDkKhxbCxbApi {
    private static buildQuery(data: FilterPhieuDkKhxbCxb): Record<string, unknown> {
        return {
            TuKhoa: data.TuKhoa ?? "",
            startDate: formatDateToIso8601UtcOffset(data.startDate ?? null),
            endDate: formatDateToIso8601UtcOffset(data.endDate ?? null),
        };
    }

    static async getPaginate(data: FilterPhieuDkKhxbCxb, page = "page-1"): Promise<PagiResult<PhieuDkKhxbCxb>> {
        const url = "/api/topic/phieu-dk-khxb-cxb/paginate/";
        try {
            const res = await window._apiGet(url + page, this.buildQuery(data));
            return res;
        } catch (err: unknown) {
            const message = (err as { responseJSON?: { message?: string } })?.responseJSON?.message
                || "Có lỗi xảy ra, vui lòng thử lại";
            window._toastbox(message, "danger");
            return {
                listResult: [],
                pagiInfo: {
                    pagi_number: [],
                    last: 0,
                    limit: 0,
                    current_page: 0,
                    total: 0,
                    query: "",
                    route: url,
                },
            };
        }
    }

    static async getList(data: FilterPhieuDkKhxbCxb): Promise<PhieuDkKhxbCxb[]> {
        const url = "/api/topic/phieu-dk-khxb-cxb/list";
        try {
            const res = await window._apiGet(url, this.buildQuery(data));
            return res;
        } catch (err: unknown) {
            const message = (err as { responseJSON?: { message?: string } })?.responseJSON?.message
                || "Có lỗi xảy ra, vui lòng thử lại";
            window._toastbox(message, "danger");
            return [];
        }
    }

    static async previewMaSo(): Promise<string> {
        const url = "/api/topic/phieu-dk-khxb-cxb/ma-so/preview";
        try {
            const res = await window._apiGet(url);
            return res?.MaSo ?? "";
        } catch (err: unknown) {
            const message = (err as { responseJSON?: { message?: string } })?.responseJSON?.message
                || "Có lỗi xảy ra, vui lòng thử lại";
            window._toastbox(message, "danger");
            return "";
        }
    }

    static async store(data: StorePhieuDkKhxbCxbPayload): Promise<{ phieu: PhieuDkKhxbCxb; listDeTai: PhieuDkDetai[] } | null> {
        const url = "/api/topic/phieu-dk-khxb-cxb/store";
        try {
            const res = await window._apiCreate(url, {
                ...data,
                NgayDK: formatDateToIso8601UtcOffset(data.NgayDK ?? null),
            });
            return res;
        } catch (err: unknown) {
            const message = (err as { responseJSON?: { message?: string } })?.responseJSON?.message
                || "Có lỗi xảy ra, vui lòng thử lại";
            window._toastbox(message, "danger");
            return null;
        }
    }

    static async getDetail(id: number): Promise<{ phieu: PhieuDkKhxbCxb; listDeTai: PhieuDkDetai[] } | null> {
        const url = `/api/topic/phieu-dk-khxb-cxb/detail/${id}`;
        try {
            const res = await window._apiGet(url);
            return res as { phieu: PhieuDkKhxbCxb; listDeTai: PhieuDkDetai[] };
        } catch (err: unknown) {
            const message = (err as { responseJSON?: { message?: string } })?.responseJSON?.message
                || "Có lỗi xảy ra, vui lòng thử lại";
            window._toastbox(message, "danger");
            return null;
        }
    }

    static async previewMaSoCxb(): Promise<number> {
        const url = "/api/topic/phieu-dk-khxb-cxb/cap-ma-cxb/preview";
        try {
            const res = await window._apiGet(url);
            return Number(res?.MaSoCxb ?? 0);
        } catch (err: unknown) {
            const message = (err as { responseJSON?: { message?: string } })?.responseJSON?.message
                || "Có lỗi xảy ra, vui lòng thử lại";
            window._toastbox(message, "danger");
            return 0;
        }
    }

    static async capMaSoCxb(
        data: CapMaSoCxbPayload,
    ): Promise<{ phieu: PhieuDkKhxbCxb; MaSoCXB: string; listDeTai: PhieuDkDetai[] } | null> {
        const url = "/api/topic/phieu-dk-khxb-cxb/cap-ma-cxb";
        try {
            const res = await window._apiCreate(url, {
                ...data,
                NgayCap: formatDateToIso8601UtcOffset(data.NgayCap ?? null),
            });
            return res as { phieu: PhieuDkKhxbCxb; MaSoCXB: string; listDeTai: PhieuDkDetai[] };
        } catch (err: unknown) {
            const message = (err as { responseJSON?: { message?: string } })?.responseJSON?.message
                || "Có lỗi xảy ra, vui lòng thử lại";
            window._toastbox(message, "danger");
            return null;
        }
    }

    static async capMaIsbn(
        data: CapMaIsbnPayload,
    ): Promise<{ phieu: PhieuDkKhxbCxb; listDeTai: PhieuDkDetai[] } | null> {
        const url = "/api/topic/phieu-dk-khxb-cxb/cap-ma-isbn";
        try {
            const res = await window._apiCreate(url, data);
            return res as { phieu: PhieuDkKhxbCxb; listDeTai: PhieuDkDetai[] };
        } catch (err: unknown) {
            const message = (err as { responseJSON?: { message?: string } })?.responseJSON?.message
                || "Có lỗi xảy ra, vui lòng thử lại";
            window._toastbox(message, "danger");
            return null;
        }
    }

    static async ketChuyenThanhSach(
        data: KetChuyenThanhSachPayload,
    ): Promise<{ phieu: PhieuDkKhxbCxb; countKetChuyen: number; listDeTai: PhieuDkDetai[] } | null> {
        const url = "/api/topic/phieu-dk-khxb-cxb/ket-chuyen-thanh-sach";
        try {
            const res = await window._apiCreate(url, data);
            return res as { phieu: PhieuDkKhxbCxb; countKetChuyen: number; listDeTai: PhieuDkDetai[] };
        } catch (err: unknown) {
            const message = (err as { responseJSON?: { message?: string } })?.responseJSON?.message
                || "Có lỗi xảy ra, vui lòng thử lại";
            window._toastbox(message, "danger");
            return null;
        }
    }
}
