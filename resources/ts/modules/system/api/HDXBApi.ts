import type { HDXB } from "../type";

export class HDXBApi{

    /** điều kiện mặc định khi lấy danh sách đơn vị */
    static readonly conditionDefault : Partial<HDXB> = {
        IsDeleted : false,
    }

    /** lấy danh sách người dùng */
    static async getAllHDXB(data : Partial<HDXB> = {}) : Promise<HDXB[]>{
        const url = "/api/system/hdxb/get-all";
        try {
            const res = await window._apiGet(url, data);
            return res;
        }catch(err : any){
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return [];
        }
    }

    /** cập nhật đơn vị */
    static async upsert(data : Partial<HDXB>) : Promise<HDXB|null>{
        console.log(data);
        const url = "/api/system/hdxb/store";
        try {
            const res = await window._apiCreate(url, data);
            return res;
        }catch(err:any){
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return null;
        }
    }
}
