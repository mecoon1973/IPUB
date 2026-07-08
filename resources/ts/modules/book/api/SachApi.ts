import { defaultPagiInfo, type PagiResult } from "../../page/type";
import type { FilterSach, Sach } from "../type/Sach";

export class SachApi {
    static readonly conditionDefault : Partial<Sach> = {
        IsDeleted : false,
    }
    static async getPaginate(data: Partial<Sach> | FilterSach = SachApi.conditionDefault, page = 'page-1'): Promise<PagiResult<Sach>> {
        const url = "/api/book/paginate/";
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

    static async getList(data: Partial<Sach> = SachApi.conditionDefault): Promise<Sach[]> {
        const url = "/api/book/list";
        try {
            const res = await window._apiGet(url, data);
            return res;
        } catch (err: any) {
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return [];
        }
    }
    static async upsert(data: Partial<Sach>): Promise<Sach|null> {
        const url = "/api/book/store";
        try {
            const res = await window._apiCreate(url, data);
            return res;
        } catch (err: any) {
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return null;
        }
    }
    static async delete(id: number): Promise<boolean> {
        const url = `/api/book/delete/${id}`;
        try {
            const res = await window._apiDelete(url);
            return res as boolean;
        } catch (err: any) {
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return false;
        }
    }
}
