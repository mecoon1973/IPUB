import type { PagiInfo, PagiResult } from "../../page/type";
import type { Doituong } from '../type/DoiTuong';


export class DoituongApi{

    /** điều kiện mặc định khi lấy danh sách Mảng sách */
    static readonly conditionDefault : Partial<Doituong> = {
        IsDeleted : false,
    }
    /** lấy danh sách Mảng sách có phân trang */
    static async getPaginateDoituong(data : Partial<Doituong> = DoituongApi.conditionDefault, page = 'page-1') : Promise<PagiResult<Doituong>>{
        const url = "/api/system/doi-tuong/paginate/";
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

    /** lấy danh sách Mảng sách có phân trang */
    static async getListDoituong(data : Record<string, any> = {}) : Promise<Doituong[]>{
        const url = "/api/system/doi-tuong/list";
        try {
            const res = (await window._apiGet(url, data)) as Doituong[];
            return res;
        }catch(err: any){
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return [];
        }
    }

    /** cập nhật Mảng sách */
    static async upsert(data : Partial<Doituong>) : Promise<Doituong|null>{
        const url = "/api/system/doi-tuong/store";
        try {
            const res = await window._apiCreate(url, data);
            return res;
        }catch(err:any){
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return null;
        }
    }

    /** xóa Mảng sách */
    static async delete(id: number) : Promise<boolean>{
        const url = `/api/system/doi-tuong/delete/${id}`;
        try {
            const res = await window._apiDelete(url);
            return res as boolean;
        }catch(err:any){
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return false;
        }
    }
}
