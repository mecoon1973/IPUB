import { defaultPagiInfo, type PagiResult } from "../../page/type";
import type { Lop } from "../type/Lop";


export class LopApi{
    /** điều kiện mặc định khi lấy danh sách Lớp */
    static readonly conditionDefault : Partial<Lop> = {
        IsDeleted : false,
    }
    /** lấy danh sách Lớp có phân trang */
    static async getPaginateLop(data : Partial<Lop> = LopApi.conditionDefault, page = 'page-1') : Promise<PagiResult<Lop>>{
        const url = "/api/system/lop/paginate/";
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

    /** lấy danh sách Lớp có phân trang */
    static async getListLop(data : Record<string, any> = {}) : Promise<Lop[]>{
        const url = "/api/system/lop/list";
        try {
            const res = await window._apiGet(url, data);
            return res;
        }catch(err: any){
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return [];
        }
    }

    /** cập nhật Lớp */
    static async upsert(data : Partial<Lop>) : Promise<Lop|null>{
        const url = "/api/system/lop/store";
        try {
            const res = await window._apiCreate(url, data);
            return res;
        }catch(err:any){
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return null;
        }
    }

    /** xóa Lớp */
    static async delete(id: number) : Promise<boolean>{
        const url = `/api/system/lop/delete/${id}`;
        try {
            const res = await window._apiDelete(url);
            return res as boolean;
        }catch(err:any){
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return false;
        }
    }
}
