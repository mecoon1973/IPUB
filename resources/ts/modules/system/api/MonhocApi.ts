import type { PagiResult } from "../../page/type";
import type { Monhoc } from "../type";


export class MonhocApi{
    /** điều kiện mặc định khi lấy danh sách Môn học */
    static readonly conditionDefault : Partial<Monhoc> = {
        IsDeleted : false,
    }
    /** lấy danh sách Môn học có phân trang */
    static async getPaginateMonhoc(data : Partial<Monhoc> = MonhocApi.conditionDefault, page = 'page-1') : Promise<PagiResult<Monhoc>>{
        const url = "/api/system/mon-hoc/paginate/";
        try {
            const res = await window._apiGet(url + page, data);
            return res;
        }catch(err : any){
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

    /** lấy danh sách Môn học có phân trang */
    static async getListMonhoc(data : Record<string, any> = {}) : Promise<Monhoc[]>{
        const url = "/api/system/mon-hoc/list";
        try {
            const res = await window._apiGet(url, data);
            return res;
        }catch(err: any){
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return [];
        }
    }

    /** cập nhật Môn học */
    static async upsert(data : Partial<Monhoc>) : Promise<Monhoc|null>{
        const url = "/api/system/mon-hoc/store";
        try {
            const res = await window._apiCreate(url, data);
            return res;
        }catch(err:any){
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return null;
        }
    }

    /** xóa Môn học */
    static async delete(id: number) : Promise<boolean>{
        const url = `/api/system/mon-hoc/delete/${id}`;
        try {
            const res = await window._apiDelete(url);
            return res as boolean;
        }catch(err:any){
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return false;
        }
    }
}
