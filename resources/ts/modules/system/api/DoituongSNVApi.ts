import type { PagiInfo, PagiResult } from "../../page/type";
import type { DoituongSNV } from '../type/DoiTuongSNV';


export class DoituongSNVApi{

    /** điều kiện mặc định khi lấy danh sách Mảng sách */
    static readonly conditionDefault : Partial<DoituongSNV> = {
        IsDeleted : false,
        InUsed : true
    }
    /** lấy danh sách Mảng sách có phân trang */
    static async getPaginate(data : Partial<DoituongSNV> = DoituongSNVApi.conditionDefault, page = 'page-1') : Promise<PagiResult<DoituongSNV>>{
        const url = "/api/system/doi-tuong-snv/paginate/";
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
    static async getList(data : Record<string, any> = {}) : Promise<DoituongSNV[]>{
        const url = "/api/system/doi-tuong-snv/list";
        try {
            const res = (await window._apiGet(url, data)) as DoituongSNV[];
            return res;
        }catch(err: any){
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return [];
        }
    }

    /** cập nhật Mảng sách */
    static async upsert(data : Partial<DoituongSNV>) : Promise<DoituongSNV|null>{
        const url = "/api/system/doi-tuong-snv/store";
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
        const url = `/api/system/doi-tuong-snv/delete/${id}`;
        try {
            const res = await window._apiDelete(url);
            return res as boolean;
        }catch(err:any){
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return false;
        }
    }
}
