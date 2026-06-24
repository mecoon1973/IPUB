import type { PagiInfo, PagiResult } from "../../page/type";
import type { Nhom } from "../type";

export class NhomApi{

    /** điều kiện mặc định khi lấy danh sách đơn vị */
    static readonly conditionDefault : Partial<Nhom> = {
        IsDeleted : false,
    }

    /** lấy danh sách người dùng */
    static async getAllNhom(data : Partial<Nhom> = {}) : Promise<Nhom[]>{
        const url = "/api/system/nhom/get-all";
        try {
            const res = await window._apiGet(url, data);
            return res;
        }catch(err : any){
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return [];
        }
    }

    /** lấy danh sách người dùng */
    static async getListNhom(data : Partial<Nhom> = {}, page = 'page-1') : Promise<PagiResult<Nhom>>{
        const url = "/api/system/nhom/list/";
        try {
            const res = await window._apiGet(url + page, data);
            return res;
        }catch(err){
            console.error(err);
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

    /** cập nhật đơn vị */
    static async upsert(data : Partial<Nhom>) : Promise<Nhom|null>{
        const url = "/api/system/nhom/store";
        try {
            const res = await window._apiCreate(url, data);
            return res;
        }catch(err:any){
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return null;
        }
    }

    /** xóa nhóm */
    static async delete(id: number) : Promise<boolean>{
        const url = `/api/system/nhom/delete/${id}`;
        try {
            const res = await window._apiDelete(url);
            return res as boolean;
        }catch(err:any){
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return false;
        }
    }

    static async addCanboToNhom(idNhom: number, listIdUser:number[]): Promise<boolean>{
        const url = `/api/system/nhom/add-canbo-to-nhom/${idNhom}`;
        try {
            const res = await window._apiCreate(url, {listIdUser});
            return res as boolean;
        }catch(err:any){
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return false;
        }
    }
}
