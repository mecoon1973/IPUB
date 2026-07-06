import { defaultPagiInfo, type PagiResult, type Relationships } from "../../page/type";
import type { FilterPhieuNhapLC, PhieuNhapLC } from "../type/PhieuNhapLC";
export class PhieuNhapLCApi {
    static readonly conditionDefault : Partial<PhieuNhapLC> = {
        IsDeleted : false,
    }
    static async getPaginate(data: Partial<PhieuNhapLC> | FilterPhieuNhapLC, page = 'page-1'): Promise<PagiResult<PhieuNhapLC>> {
        const url = "/api/legal-deposit/phieu-nhap-lc/paginate/";
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

    static async getList(data: Partial<PhieuNhapLC>): Promise<PhieuNhapLC[]> {
        const url = "/api/legal-deposit/phieu-nhap-lc/list";
        try {
            const res = await window._apiGet(url, data);
            return res;
        } catch (err: any) {
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return [];
        }
    }
    static async upsert(data: Partial<PhieuNhapLC>): Promise<PhieuNhapLC|null> {
        const url = "/api/legal-deposit/phieu-nhap-lc/store";
        try {
            const res = await window._apiCreate(url, data);
            return res;
        } catch (err: any) {
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return null;
        }
    }
    static async delete(id: number): Promise<boolean> {
        const url = `/api/legal-deposit/phieu-nhap-lc/delete/${id}`;
        try {
            const res = await window._apiDelete(url);
            return res as boolean;
        } catch (err: any) {
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return false;
        }
    }
}
