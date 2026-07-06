import { defaultPagiInfo, type PagiResult } from "../../page/type";
import type { MangsachCXB } from "../type/MangSachCXB";


export class MangsachCXBApi{
    /** điều kiện mặc định khi lấy danh sách Mảng sách CXB */
    static readonly conditionDefault : Partial<MangsachCXB> = {
        IsDeleted : false,
    }
    /** lấy danh sách Mảng sách CXB có phân trang */
    static async getPaginateMangsachCXB(data : Partial<MangsachCXB> = MangsachCXBApi.conditionDefault, page = 'page-1') : Promise<PagiResult<MangsachCXB>>{
        const url = "/api/system/mang-sach-cxb/paginate/";
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

    /** lấy danh sách Mảng sách CXB có phân trang */
    static async getListMangsachCXB(data : Record<string, any> = {}) : Promise<MangsachCXB[]>{
        const url = "/api/system/mang-sach-cxb/list";
        try {
            const res = await window._apiGet(url, data);
            return res;
        }catch(err: any){
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return [];
        }
    }

    /** cập nhật Mảng sách CXB */
    static async upsert(data : Partial<MangsachCXB>) : Promise<MangsachCXB|null>{
        const url = "/api/system/mang-sach-cxb/store";
        try {
            const res = await window._apiCreate(url, data);
            return res;
        }catch(err:any){
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return null;
        }
    }

    /** xóa Mảng sách CXB */
    static async delete(id: number) : Promise<boolean>{
        const url = `/api/system/mang-sach-cxb/delete/${id}`;
        try {
            const res = await window._apiDelete(url);
            return res as boolean;
        }catch(err:any){
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return false;
        }
    }
}
