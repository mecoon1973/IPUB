import type { PagiInfo, PagiResult } from "../../page/type";
import type { Congviecchebanin } from "../type";


export class CongviecchebaninApi{
    /** điều kiện mặc định khi lấy danh sách Lớp */
    static readonly conditionDefault : Partial<Congviecchebanin> = {
        IsDeleted : false,
    }
    /** lấy danh sách Lớp có phân trang */
    static async getPaginate(data : Partial<Congviecchebanin> = CongviecchebaninApi.conditionDefault, page = 'page-1') : Promise<PagiResult<Congviecchebanin>>{
        const url = "/api/system/cong-viec-che-ban-in/paginate/";
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
    static async getList(data : Record<string, any> = {}) : Promise<Congviecchebanin[]>{
        const url = "/api/system/cong-viec-che-ban-in/list";
        try {
            const res = await window._apiGet(url, data);
            return res;
        }catch(err: any){
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return [];
        }
    }

    /** cập nhật Lớp */
    static async upsert(data : Partial<Congviecchebanin>) : Promise<Congviecchebanin|null>{
        const url = "/api/system/cong-viec-che-ban-in/store";
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
        const url = `/api/system/cong-viec-che-ban-in/delete/${id}`;
        try {
            const res = await window._apiDelete(url);
            return res as boolean;
        }catch(err:any){
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return false;
        }
    }
}
