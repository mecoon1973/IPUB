
import type { ChucNang } from "../type/ChucNang";

/** Trùng key với `FrmStoreChucnangRequest` (PHP) — chỉ gửi các field này khi store. */
export function buildChucnangStorePayload(form: Partial<ChucNang>): Record<string, string | number | boolean> {
    return {
        id: form.id ?? 0,
        Code: form.Code ?? "",
        Title: form.Title ?? "",
        FunctionCode: form.FunctionCode ?? "",
        Href: form.Href ?? "",
        ChildFunctionCode: form.ChildFunctionCode ?? "",
        isLinkFull: form.isLinkFull ?? false,
        Target: form.Target ?? "_blank",
        Description: form.Description ?? "",
        OnMenu: form.OnMenu ?? false,
        ThuTu: form.ThuTu ?? 0,
        ParentID: form.ParentID ?? 0,
        PhanHeID: form.PhanHeID ?? 0,
        Crumb: form.Crumb ?? "",
    };
}

export class ChucnangApi{

    /** điều kiện mặc định khi lấy danh sách đơn vị */
    static readonly conditionDefault : Partial<ChucNang> = {
        Deleted : false,
    }

    /** lấy danh sách người dùng */
    static async getAllChucnang(data : Partial<ChucNang> = {}) : Promise<ChucNang[]>{
        const url = "/api/system/chuc-nang/get-all";
        try {
            const res = await window._apiGet(url, data);
            return res;
        }catch(err : any){
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return [];
        }
    }

    /** cập nhật đơn vị */
    static async upsert(data : Partial<ChucNang>) : Promise<ChucNang|null>{
        const url = "/api/system/chuc-nang/store";
        try {
            const res = await window._apiCreate(url, buildChucnangStorePayload(data));
            return res;
        }catch(err:any){
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return null;
        }
    }

    /** xóa đơn vị */
    static async delete(id: number) : Promise<boolean>{
        const url = `/api/system/chuc-nang/delete/${id}`;
        try {
            const res = await window._apiDelete(url);
            return res as boolean;
        }catch(err:any){
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return false;
        }
    }
}
