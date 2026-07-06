import type { PagiInfo, PagiResult } from "../../page/type";
import type { LoaiXBP } from "../type/LoaiXBP";


export class LoaiXBPApi{
    /** điều kiện mặc định khi lấy danh sách Lớp */
    static readonly conditionDefault : Partial<LoaiXBP> = {
        IsDeleted : false,
    }
    /** lấy danh sách Lớp có phân trang */
    static async getPaginate(data : Partial<LoaiXBP> = LoaiXBPApi.conditionDefault, page = 'page-1') : Promise<PagiResult<LoaiXBP>>{
        const url = "/api/system/loai-xbp/paginate/";
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

    /** lấy danh sách Lớp có phân trang */
    static async getList(data : Record<string, any> = {}) : Promise<LoaiXBP[]>{
        const url = "/api/system/loai-xbp/list";
        try {
            const res = await window._apiGet(url, data);
            return res;
        }catch(err: any){
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return [];
        }
    }

    /** cập nhật Lớp */
    static async upsert(data : Partial<LoaiXBP>) : Promise<LoaiXBP|null>{
        const url = "/api/system/loai-xbp/store";
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
        const url = `/api/system/loai-xbp/delete/${id}`;
        try {
            const res = await window._apiDelete(url);
            return res as boolean;
        }catch(err:any){
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return false;
        }
    }
}
