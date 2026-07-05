import type { PagiResult } from "../../page/type";
import { defaultFilterHDXBNXBGDVN, type FilterHDXBNXBGDVN, type HDXBNXBGDVN } from "../type";

export class HDXBNXBGDVNApi {
    static readonly conditionDefault: FilterHDXBNXBGDVN = defaultFilterHDXBNXBGDVN;

    static readonly serializePayloadForStore = (data: Partial<HDXBNXBGDVN>): Record<string, unknown> => {
        const payload: Record<string, unknown> = { ...data };
        return payload;
    }

    static async getPaginate(data: FilterHDXBNXBGDVN = HDXBNXBGDVNApi.conditionDefault, page = 'page-1'): Promise<PagiResult<HDXBNXBGDVN>> {
        const url = "/api/topic/hdxb-nxbgdvn/paginate/";
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

    static async getList(data: FilterHDXBNXBGDVN = HDXBNXBGDVNApi.conditionDefault): Promise<HDXBNXBGDVN[]> {
        const url = "/api/topic/hdxb-nxbgdvn/list";
        try {
            const res = await window._apiGet(url, data);
            return res as HDXBNXBGDVN[];
        } catch (err: any) {
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return [];
        }
    }

    static async phanCongDocDuyet(ids: number[], idCanBo: number): Promise<boolean> {
        const url = "/api/topic/hdxb-nxbgdvn/phan-cong-doc-duyet";
        try {
            await window._apiCreate(url, { ids, idCanBo });
            return true;
        } catch (err: any) {
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return false;
        }
    }
}
