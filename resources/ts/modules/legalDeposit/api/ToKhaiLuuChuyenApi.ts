import type { PagiInfo, PagiResult } from "../../page/type";
import type { ToKhaiLuuChuyen } from "../type";
export class ToKhaiLuuChuyenApi {

    static async getPaginate(data: Partial<ToKhaiLuuChuyen>, page = 'page-1'): Promise<PagiResult<ToKhaiLuuChuyen>> {
        const url = "/api/legal-deposit/to-khai-luu-chuyen/paginate/";
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

    static async getList(data: Partial<ToKhaiLuuChuyen>): Promise<ToKhaiLuuChuyen[]> {
        const url = "/api/legal-deposit/to-khai-luu-chuyen/list";
        try {
            const res = await window._apiGet(url, data);
            return res;
        } catch (err: any) {
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return [];
        }
    }
    static async upsert(data: Partial<ToKhaiLuuChuyen>): Promise<ToKhaiLuuChuyen|null> {
        const url = "/api/legal-deposit/to-khai-luu-chuyen/store";
        try {
            const res = await window._apiCreate(url, data);
            return res;
        } catch (err: any) {
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return null;
        }
    }
    static async delete(id: number): Promise<boolean> {
        const url = `/api/legal-deposit/to-khai-luu-chuyen/delete/${id}`;
        try {
            const res = await window._apiDelete(url);
            return res as boolean;
        } catch (err: any) {
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return false;
        }
    }
}
