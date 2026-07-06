import { defaultPagiInfo, type PagiInfo } from "../../page/type";
import type { DSDocRaSoat, FilterDSDocRaSoat } from "../type/DSDocRaSoat";
export class DSDocRaSoatApi {

    static async getPaginate(data: Partial<DSDocRaSoat> | FilterDSDocRaSoat, page = 'page-1'): Promise<{listResult: DSDocRaSoat[], pagiInfo: PagiInfo}> {
        const url = "/api/quality-assessment/ds-doc-ra-soat/paginate/";
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

    static async getList(data: Partial<DSDocRaSoat> | FilterDSDocRaSoat): Promise<DSDocRaSoat[]> {
        const url = "/api/quality-assessment/ds-doc-ra-soat/list";
        try {
            const res = await window._apiGet(url, data);
            return res;
        } catch (err: any) {
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return [];
        }
    }
    static async upsert(data: Partial<DSDocRaSoat>): Promise<DSDocRaSoat|null> {
        const url = "/api/quality-assessment/ds-doc-ra-soat/store";
        try {
            const res = await window._apiCreate(url, data);
            return res;
        } catch (err: any) {
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return null;
        }
    }
    static async delete(id: number): Promise<boolean> {
        const url = `/api/quality-assessment/ds-doc-ra-soat/delete/${id}`;
        try {
            const res = await window._apiDelete(url);
            return res as boolean;
        } catch (err: any) {
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return false;
        }
    }
}
