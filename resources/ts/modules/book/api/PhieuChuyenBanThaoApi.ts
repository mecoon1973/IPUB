import type { PagiResult } from "../../page/type";
import type { PhieuChuyenBanThao } from "../type";

export class PhieuChuyenBanThaoApi {
    static readonly conditionDefault : Partial<PhieuChuyenBanThao> = {
        IsDeleted : false,
    }
    static async getPaginate(data: Partial<PhieuChuyenBanThao> = PhieuChuyenBanThaoApi.conditionDefault, page = 'page-1'): Promise<PagiResult<PhieuChuyenBanThao>> {
        const url = "/api/phieu-chuyen-ban-thao/paginate/";
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

    static async getList(data: Partial<PhieuChuyenBanThao> = PhieuChuyenBanThaoApi.conditionDefault): Promise<PhieuChuyenBanThao[]> {
        const url = "/api/phieu-chuyen-ban-thao/list";
        try {
            const res = await window._apiGet(url, data);
            return res;
        } catch (err: any) {
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return [];
        }
    }
    static async upsert(data: Partial<PhieuChuyenBanThao>): Promise<PhieuChuyenBanThao|null> {
        const url = "/api/phieu-chuyen-ban-thao/store";
        try {
            const res = await window._apiCreate(url, data);
            return res;
        } catch (err: any) {
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return null;
        }
    }
    static async delete(id: number): Promise<boolean> {
        const url = `/api/phieu-chuyen-ban-thao/delete/${id}`;
        try {
            const res = await window._apiDelete(url);
            return res as boolean;
        } catch (err: any) {
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return false;
        }
    }
}
