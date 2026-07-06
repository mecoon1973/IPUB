import type { PagiInfo, PagiResult } from "../../page/type";
import { toIso8601UtcOffset } from "../../core/utils/helpersDayjs";
import type { User } from "../type/User";

function serializeUserPayloadForStore(data: Partial<User>): Record<string, unknown> {
    const payload: Record<string, unknown> = { ...data };

    if (Object.prototype.hasOwnProperty.call(payload, "NgaySinh")) {
        payload.NgaySinh = toIso8601UtcOffset(payload.NgaySinh);
    }
    if (Object.prototype.hasOwnProperty.call(payload, "NgayHetHan")) {
        payload.NgayHetHan = toIso8601UtcOffset(payload.NgayHetHan);
    }
    if (Object.prototype.hasOwnProperty.call(payload, "NgayCap")) {
        payload.NgayCap = toIso8601UtcOffset(payload.NgayCap);
    }

    return payload;
}

export class UserApi{
    /** lấy danh sách người dùng có phân trang */
    static async getPaginateUser(data : Record<string, any> = {}, page = 'page-1') : Promise<PagiResult<User>>{
        const url = "/api/user/paginate/";
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

    /** lấy danh sách người dùng có phân trang */
    static async getListUser(data : Record<string, any> = {}) : Promise<User[]>{
        const url = "/api/user/list";
        try {
            const res = await window._apiGet(url, data);
            return res;
        }catch(err: any){
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return [];
        }
    }

    /** cập nhật người dùng */
    static async upsert(data : Partial<User>) : Promise<User|null>{
        const url = "/api/user/store";
        try {
            const res = await window._apiCreate(url, serializeUserPayloadForStore(data));
            return res;
        }catch(err:any){
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return null;
        }
    }

    /** xóa người dùng */
    static async delete(id: number) : Promise<boolean>{
        const url = `/api/user/delete/${id}`;
        try {
            const res = await window._apiDelete(url);
            return res as boolean;
        }catch(err:any){
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return false;
        }
    }

    static async createAccount(data: Record<string, any>, id: number): Promise<User|null>{
        const url = `/api/user/create-account/${id}`;
        try {
            const res = await window._apiCreate(url, data);
            return res as User;
        }catch(err:any){
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return null;
        }
    }

    static async resetPassword(id: number): Promise<boolean>{
        const url = `/api/user/reset-password/${id}`;
        try {
            const res = await window._apiCreate(url, {});
            return res as boolean;
        }catch(err:any){
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return false;
        }
    }

    static async deleteCanboInNhom(idNhom: number, idCanbo: number): Promise<boolean>{
        const url = `/api/user/delete-canbo-in-nhom`;
        try {
            const res = await window._apiDelete(url, { idNhom, idCanbo });
            return res as boolean;
        }catch(err:any){
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return false;
        }
    }
}
