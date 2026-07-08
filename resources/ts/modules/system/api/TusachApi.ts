import { defaultPagiInfo, type PagiResult } from "../../page/type";
import type { Tusach } from "../type/TuSach";


export class TusachApi{
    /** điều kiện mặc định khi lấy danh sách Tủ sách */
    static readonly conditionDefault : Partial<Tusach> = {
        IsDeleted : false,
    }
    /** lấy danh sách Tủ sách có phân trang */
    static async getPaginateTusach(data : Partial<Tusach> = TusachApi.conditionDefault, page = 'page-1') : Promise<PagiResult<Tusach>>{
        const url = "/api/system/tu-sach/paginate/";
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

    /** lấy danh sách Tủ sách có phân trang */
    static async getListTusach(data : Record<string, any> = {}) : Promise<Tusach[]>{
        const url = "/api/system/tu-sach/list";
        try {
            const res = await window._apiGet(url, data);
            return res;
        }catch(err: any){
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return [];
        }
    }

    /** cập nhật Tủ sách */
    static async upsert(data : Partial<Tusach>) : Promise<Tusach|null>{
        const url = "/api/system/tu-sach/store";
        try {
            const res = await window._apiCreate(url, data);
            return res;
        }catch(err:any){
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return null;
        }
    }

    /** xóa Tủ sách */
    static async delete(id: number) : Promise<boolean>{
        const url = `/api/system/tu-sach/delete/${id}`;
        try {
            const res = await window._apiDelete(url);
            return res as boolean;
        }catch(err:any){
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return false;
        }
    }
}
