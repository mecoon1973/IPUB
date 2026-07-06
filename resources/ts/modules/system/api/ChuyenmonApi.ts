import type { PagiInfo, PagiResult } from "../../page/type";
import type { Chuyenmon } from "../type/ChuyenMon";


export class ChuyenmonApi{
    /** điều kiện mặc định khi lấy danh sách Tủ sách */
    static readonly conditionDefault : Partial<Chuyenmon> = {
        IsDeleted : false,
    }
    /** lấy danh sách Tủ sách có phân trang */
    static async getPaginateChuyenmon(data : Partial<Chuyenmon> = ChuyenmonApi.conditionDefault, page = 'page-1') : Promise<PagiResult<Chuyenmon>>{
        const url = "/api/system/chuyen-mon/paginate/";
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

    /** lấy danh sách Tủ sách có phân trang */
    static async getListChuyenmon(data : Record<string, any> = {}) : Promise<Chuyenmon[]>{
        const url = "/api/system/chuyen-mon/list";
        try {
            const res = await window._apiGet(url, data);
            return res;
        }catch(err: any){
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return [];
        }
    }

    /** cập nhật Tủ sách */
    static async upsert(data : Partial<Chuyenmon>) : Promise<Chuyenmon|null>{
        const url = "/api/system/chuyen-mon/store";
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
        const url = `/api/system/chuyen-mon/delete/${id}`;
        try {
            const res = await window._apiDelete(url);
            return res as boolean;
        }catch(err:any){
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return false;
        }
    }
}
