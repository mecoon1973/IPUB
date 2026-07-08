import type { PagiInfo } from "../../page/type";
import type { DonVi } from "../../user/type/DonVi";
import type { User } from "../../user/type/User";

export class DonviApi{

    /** điều kiện mặc định khi lấy danh sách đơn vị */
    static readonly conditionDefault : Partial<DonVi> = {
        IsDeleted : false,
        NoiBo : true,
        NhaIn : false,
        LienKet : false,
    }

    /** lấy danh sách người dùng */
    static async getAllDonvi(data : Partial<DonVi> = {}) : Promise<DonVi[]>{
        const url = "/api/system/donvi/get-all";
        try {
            const res = await window._apiGet(url, data);
            return res;
        }catch(err : any){
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return [];
        }
    }

    /** cập nhật đơn vị */
    static async upsert(data : Partial<DonVi>) : Promise<DonVi|null>{
        const url = "/api/system/donvi/store";
        try {
            const res = await window._apiCreate(url, data);
            return res;
        }catch(err:any){
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return null;
        }
    }

    /** xóa đơn vị */
    static async delete(id: number) : Promise<boolean>{
        const url = `/api/system/donvi/delete/${id}`;
        try {
            const res = await window._apiDelete(url);
            return res as boolean;
        }catch(err:any){
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return false;
        }
    }
}
