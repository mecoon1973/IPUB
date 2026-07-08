import { defaultPagiInfo, type PagiResult } from "../../page/type";
import type { LoaiXbpLc } from "../type/LoaiXbpLc";


export class LoaiXbpLcApi{
    static readonly conditionDefault : Partial<LoaiXbpLc> = {
        IsDeleted : false,
        InUsed : true,
        TenLoai : "",
    }
    static async getPaginate(data : Partial<LoaiXbpLc> = LoaiXbpLcApi.conditionDefault, page = 'page-1') : Promise<PagiResult<LoaiXbpLc>>{
        const url = "/api/system/loai-xbp-luu-chieu/paginate/";
        try {
            const res = await window._apiGet(url + page, data);
            return res;
        }catch(err : any){
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return {
                listResult: [],
                pagiInfo: defaultPagiInfo,
            };
        }
    }

    static async getList(data : Record<string, any> = {}) : Promise<LoaiXbpLc[]>{
        const url = "/api/system/loai-xbp-luu-chieu/list";
        try {
            const res = await window._apiGet(url, data);
            return res;
        }catch(err: any){
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return [];
        }
    }

    static async upsert(data : Partial<LoaiXbpLc>) : Promise<LoaiXbpLc|null>{
        const url = "/api/system/loai-xbp-luu-chieu/store";
        try {
            const res = await window._apiCreate(url, data);
            return res;
        }catch(err:any){
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return null;
        }
    }

    static async delete(id: number) : Promise<boolean>{
        const url = `/api/system/loai-xbp-luu-chieu/delete/${id}`;
        try {
            const res = await window._apiDelete(url);
            return res as boolean;
        }catch(err:any){
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return false;
        }
    }
}
