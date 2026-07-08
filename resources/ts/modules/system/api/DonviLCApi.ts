import { defaultPagiInfo, type PagiResult } from "../../page/type";
import type { DonviLC } from "../type/DonviLC";

export class DonviLCApi{

    /** điều kiện mặc định khi lấy danh sách đơn vị */
    static readonly conditionDefault : Partial<DonviLC> = {
        Ten : "",
        IsDeleted : false,
    }

    static async getPaginate(data : Partial<DonviLC> = DonviLCApi.conditionDefault, page = 'page-1') : Promise<PagiResult<DonviLC>>{
        const url = "/api/system/donvi-lc/paginate/";
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
    static async getList(data : Record<string, any> = {}) : Promise<DonviLC[]>{
        const url = "/api/system/donvi-lc/list";
        try {
            const res = await window._apiGet(url, data);
            return res;
        }catch(err: any){
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return [];
        }
    }

    /** cập nhật Lớp */
    static async upsert(data : Partial<DonviLC>) : Promise<DonviLC|null>{
        const url = "/api/system/donvi-lc/store";
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
        const url = `/api/system/donvi-lc/delete/${id}`;
        try {
            const res = await window._apiDelete(url);
            return res as boolean;
        }catch(err:any){
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return false;
        }
    }
}
