import type { PagiInfo, PagiResult } from "../../page/type";
import type { FilterPhieuDkDetai, PhieuDkDetai } from '../type';

export class PhieuDkDetaiApi {
    static readonly conditionDefault : Partial<PhieuDkDetai> = {
        IsDeleted : false,
    }

    static readonly serializePayloadForStore = (data: Partial<PhieuDkDetai>): Record<string, unknown> => {
        const payload: Record<string, unknown> = { ...data };
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
            const res = await window._apiCreate(url, data);
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
}
