import type { PagiInfo, PagiResult } from "../../page/type";
import type { BienMoiTruong, FilterBienMoiTruong } from "../type";

export class BienMoiTruongApi {
    static readonly conditionDefault : Partial<BienMoiTruong> = {
        IsDeleted : false,
    }
    static async getPaginate(data: Partial<BienMoiTruong> | FilterBienMoiTruong = BienMoiTruongApi.conditionDefault, page = 'page-1'): Promise<PagiResult<BienMoiTruong>> {
        const url = "/api/system/bien-moi-truong/paginate/";
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

    static async getList(data: Partial<BienMoiTruong> = BienMoiTruongApi.conditionDefault): Promise<BienMoiTruong[]> {
        const url = "/api/system/bien-moi-truong/list";
        try {
            const res = await window._apiGet(url, data);
            return res;
        } catch (err: any) {
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return [];
        }
    }
    static async upsert(data: Partial<BienMoiTruong>): Promise<BienMoiTruong|null> {
        const url = "/api/system/bien-moi-truong/store";
        try {
            const res = await window._apiCreate(url, data);
            return res;
        } catch (err: any) {
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return null;
        }
    }
    static async delete(id: number): Promise<boolean> {
        const url = `/api/system/bien-moi-truong/delete/${id}`;
        try {
            const res = await window._apiDelete(url);
            return res as boolean;
        } catch (err: any) {
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return false;
        }
    }
}
