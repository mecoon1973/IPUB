import type { PagiInfo, PagiResult } from "../../page/type";
import type { Bosach } from "../type/BoSach";

export class BosachApi {
    static readonly conditionDefault : Partial<Bosach> = {
        IsDeleted : false,
    }
    static async getPaginateBosach(data: Partial<Bosach> = BosachApi.conditionDefault, page = 'page-1'): Promise<PagiResult<Bosach>> {
        const url = "/api/system/bo-sach/paginate/";
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

    static async getListBosach(data: Partial<Bosach> = BosachApi.conditionDefault): Promise<Bosach[]> {
        const url = "/api/system/bo-sach/list";
        try {
            const res = await window._apiGet(url, data);
            return res;
        } catch (err: any) {
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return [];
        }
    }
    static async upsert(data: Partial<Bosach>): Promise<Bosach|null> {
        const url = "/api/system/bo-sach/store";
        try {
            const res = await window._apiCreate(url, data);
            return res;
        } catch (err: any) {
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return null;
        }
    }
    static async delete(id: number): Promise<boolean> {
        const url = `/api/system/bo-sach/delete/${id}`;
        try {
            const res = await window._apiDelete(url);
            return res as boolean;
        } catch (err: any) {
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return false;
        }
    }
}
