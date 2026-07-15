import { defaultPagiInfo, type PagiResult } from "../../page/type";
import { formatDateToIso8601UtcOffset } from "../../core/utils/helpersDayjs";
import type { FilterPhieuDkDetai, FormDataPrintPhieuDkDeTai, PhieuDkDetai, PhieuDkDetaiDateKey } from '../type/PhieuDkDetai';

const STORE_DATE_KEYS: PhieuDkDetaiDateKey[] = [
    "NgayDK",
    "BanQuyenTuNgay",
    "BanQuyenDenNgay",
    "NgayKyHDBS",
    "TuNgayHDBS",
    "DenNgayHDBS",
];

export class PhieuDkDetaiApi {
    static readonly conditionDefault : Partial<PhieuDkDetai> = {
        IsDeleted : false,
    }

    /**
     * Date → ISO string trước khi POST (jQuery form-urlencoded gọi Date.toString() nếu để nguyên Date).
     * Không gán `null`: `$.param` bỏ qua null/undefined → field biến mất khỏi body.
     * Mongo trả `_id` — gửi lên API bằng `id` để update.
     */
    static readonly serializePayloadForStore = (data: Partial<PhieuDkDetai>): Record<string, unknown> => {
        const payload: Record<string, unknown> = { ...data };
        const raw = payload as { id?: unknown; _id?: unknown };
        if ((raw.id == null || raw.id === 0) && raw._id != null) {
            payload.id = Number(raw._id);
        }
        delete raw._id;

        for (const key of STORE_DATE_KEYS) {
            if (!(key in payload) || payload[key] == null) {
                continue;
            }
            const formatted = formatDateToIso8601UtcOffset(payload[key]);
            if (formatted == null) {
                delete payload[key];
                continue;
            }
            payload[key] = formatted;
        }
        return payload;
    };

    static async getPaginate(data: Partial<PhieuDkDetai> | FilterPhieuDkDetai = PhieuDkDetaiApi.conditionDefault, page = 'page-1'): Promise<PagiResult<PhieuDkDetai>> {
        const url = "/api/topic/phieu-dk-detai/paginate/";
        try {
            const res = await window._apiGet(url + page, data);
            return res;
        } catch (err: any) {
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return {
                listResult: [],
                pagiInfo: defaultPagiInfo,
            };
        }
    }

    static async getList(data: Partial<PhieuDkDetai> | FilterPhieuDkDetai = PhieuDkDetaiApi.conditionDefault): Promise<PhieuDkDetai[]> {
        const url = "/api/topic/phieu-dk-detai/list";
        try {
            const res = await window._apiGet(url, data);
            return res;
        } catch (err: any) {
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return [];
        }
    }
    static async upsert(data: Partial<PhieuDkDetai>): Promise<PhieuDkDetai|null> {
        const url = "/api/topic/phieu-dk-detai/store";
        try {
            const res = await window._apiCreate(url, PhieuDkDetaiApi.serializePayloadForStore(data));
            return res;
        } catch (err: any) {
            console.log(err);
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return null;
        }
    }
    static async delete(id: number): Promise<boolean> {
        const url = `/api/topic/phieu-dk-detai/delete/${id}`;
        try {
            const res = await window._apiDelete(url);
            return res as boolean;
        } catch (err: any) {
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return false;
        }
    }

    static async xetDuyetNxbgdvn(id: number): Promise<PhieuDkDetai | null> {
        const url = "/api/topic/phieu-dk-detai/xet-duyet-nxbgdvn";
        try {
            const res = await window._apiCreate(url, { id });
            return res as PhieuDkDetai;
        } catch (err: any) {
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return null;
        }
    }

    static async previewMaSoNxbgd(id: number, isMa12KiTu: boolean): Promise<string> {
        const url = "/api/topic/phieu-dk-detai/cap-ma-so/preview";
        try {
            const res = await window._apiGet(url, { id, isMa12KiTu: isMa12KiTu ? 1 : 0 });
            return (res as { maSo?: string })?.maSo ?? "";
        } catch (err: any) {
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return "";
        }
    }

    static async capMaSoNxbgd(id: number, maSo: string, isMa12KiTu: boolean): Promise<PhieuDkDetai | null> {
        const url = "/api/topic/phieu-dk-detai/cap-ma-so";
        try {
            const res = await window._apiCreate(url, {
                id,
                maSo,
                isMa12KiTu: isMa12KiTu ? 1 : 0,
            });
            return res as PhieuDkDetai;
        } catch (err: any) {
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return null;
        }
    }
    static async printPhieuDkDeTai(id: number, data: FormDataPrintPhieuDkDeTai): Promise<string> {
        const url = `/api/topic/phieu-dk-detai/print/${id}`;
        try {
            window._toastbox("Đang xử lý...", "info");
            const res = await window._apiGet(url, data);
            window._toastbox("Xử lý hoàn tất", "success");
            const fileUrl = typeof res === "string" ? res : (res?.url ?? "");
            return typeof fileUrl === "string" ? fileUrl : "";
        } catch (err: any) {
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return "";
        }
    }
}
