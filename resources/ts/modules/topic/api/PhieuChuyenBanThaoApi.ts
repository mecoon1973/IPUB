import type { PagiResult } from "../../page/type";
import { defaultPagiInfo } from "../../page/type";
import type { FilterPhieuChuyenBanThao, PhieuChuyenBanThao } from "../type";
import { formatDateToIso8601UtcOffset } from "../../core/utils/helpersDayjs";

export class PhieuChuyenBanThaoApi {
    private static buildQuery(data: FilterPhieuChuyenBanThao): Record<string, unknown> {
        return {
            TuKhoa: data.TuKhoa ?? "",
            startDate: formatDateToIso8601UtcOffset(data.startDate ?? null),
            endDate: formatDateToIso8601UtcOffset(data.endDate ?? null),
            ID_DV: data.ID_DV && data.ID_DV > 0 ? data.ID_DV : null,
        };
    }

    static async getPaginate(
        data: FilterPhieuChuyenBanThao,
        page = "page-1",
    ): Promise<PagiResult<PhieuChuyenBanThao>> {
        const url = "/api/topic/phieu-chuyen-ban-thao/paginate/";
        try {
            const res = await window._apiGet(url + page, this.buildQuery(data));
            return res;
        } catch (err: any) {
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return {
                listResult: [],
                pagiInfo: defaultPagiInfo,
            };
        }
    }

    static async store(data: Partial<PhieuChuyenBanThao>): Promise<PhieuChuyenBanThao | null> {
        const url = "/api/topic/phieu-chuyen-ban-thao/store";
        try {
            const res = await window._apiCreate(url, {
                ...data,
                NgayGiao: formatDateToIso8601UtcOffset(data.NgayGiao ?? null),
                NgayNhan: formatDateToIso8601UtcOffset(data.NgayNhan ?? null),
            });
            return res as PhieuChuyenBanThao;
        } catch (err: any) {
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return null;
        }
    }

    static async delete(id: number): Promise<boolean> {
        const url = `/api/topic/phieu-chuyen-ban-thao/delete/${id}`;
        try {
            await window._apiDelete(url);
            return true;
        } catch (err: any) {
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return false;
        }
    }
}
