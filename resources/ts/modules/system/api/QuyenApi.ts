import type { Quyen } from "../type";

export class QuyenApi{

    /** điều kiện mặc định khi lấy danh sách đơn vị */
    static readonly conditionDefault : Partial<Quyen> = {
        IsDeleted : false,
    }

    /** lấy danh sách quyền */
        static async getAllQuyen(data : Partial<Quyen> = {}) : Promise<Quyen[]>{
        const url = "/api/system/quyen/get-all";
        try {
            const res = await window._apiGet(url, data);
            return res;
        }catch(err : any){
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return [];
        }
    }

    /** cập nhật quyền */
    static async upsert(data : Partial<Quyen>) : Promise<Quyen|null>{
        const url = "/api/system/quyen/store";
        try {
            const res = await window._apiCreate(url, data);
            return res;
        }catch(err:any){
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return null;
        }
    }

    /** xóa quyền */
    static async delete(id: number) : Promise<boolean>{
        const url = `/api/system/quyen/delete/${id}`;
        try {
            const res = await window._apiDelete(url);
            return res as boolean;
        }catch(err:any){
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return false;
        }
    }
}
