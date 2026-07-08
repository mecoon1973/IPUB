import { defaultPagiInfo, type PagiResult } from "../../page/type";
import type { Detai_Congdoan, FilterDetaiCongdoan } from '../type/Detai_Congdoan';

export class DetaiCongDoanApi {


    static readonly serializePayloadForStore = (data: Partial<Detai_Congdoan>): Record<string, unknown> => {
        const payload: Record<string, unknown> = { ...data };
        return payload;
    };

    static async getPaginate(data: FilterDetaiCongdoan, page = 'page-1'): Promise<PagiResult<Detai_Congdoan>> {
        const url = "/api/topic/detai-congdoan/paginate/";
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

    static async getList(data: FilterDetaiCongdoan): Promise<Detai_Congdoan[]> {
        const url = "/api/topic/detai-congdoan/list";
        try {
            const res = await window._apiGet(url, data);
            return res;
        } catch (err: any) {
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return [];
        }
    }
    static async upsert(data: Partial<Detai_Congdoan>): Promise<Detai_Congdoan|null> {
        const url = "/api/topic/detai-congdoan/store";
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
        const url = `/api/topic/detai-congdoan/delete/${id}`;
        try {
            const res = await window._apiDelete(url);
            return res as boolean;
        } catch (err: any) {
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return false;
        }
    }
}
