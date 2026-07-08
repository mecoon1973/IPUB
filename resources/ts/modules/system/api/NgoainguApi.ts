import { defaultPagiInfo, type PagiResult } from "../../page/type";
import type { Ngoaingu } from "../type/NgoaiNgu";


export class NgoainguApi{
    /** điều kiện mặc định khi lấy danh sách Ngoại ngữ */
    static readonly conditionDefault : Partial<Ngoaingu> = {
        IsDeleted : false,
    }
    /** lấy danh sách Ngoại ngữ có phân trang */
    static async getPaginateNgoaingu(data : Partial<Ngoaingu> = NgoainguApi.conditionDefault, page = 'page-1') : Promise<PagiResult<Ngoaingu>>{
        const url = "/api/system/ngoai-ngu/paginate/";
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

    /** lấy danh sách Ngoại ngữ có phân trang */
    static async getListNgoaingu(data : Record<string, any> = {}) : Promise<Ngoaingu[]>{
        const url = "/api/system/ngoai-ngu/list";
        try {
            const res = await window._apiGet(url, data);
            return res;
        }catch(err: any){
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return [];
        }
    }

    /** cập nhật Ngoại ngữ */
    static async upsert(data : Partial<Ngoaingu>) : Promise<Ngoaingu|null>{
        const url = "/api/system/ngoai-ngu/store";
        try {
            const res = await window._apiCreate(url, data);
            return res;
        }catch(err:any){
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return null;
        }
    }

    /** xóa Ngoại ngữ */
    static async delete(id: number) : Promise<boolean>{
        const url = `/api/system/ngoai-ngu/delete/${id}`;
        try {
            const res = await window._apiDelete(url);
            return res as boolean;
        }catch(err:any){
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return false;
        }
    }
}
