
import { defaultPagiInfo, type PagiResult } from "../../page/type";
import type { TemplateExcel } from "../type/TemplateExcel";

export class TemplateExcelApi{

    /** điều kiện mặc định khi lấy danh sách Template Excel */
    static readonly conditionDefault : Partial<TemplateExcel> = {
        IsDeleted : false,
    }

    /** lấy danh sách Template Excel có phân trang */
    static async getPaginateTemplateExcel(data : Partial<TemplateExcel> = TemplateExcelApi.conditionDefault, page = 'page-1') : Promise<PagiResult<TemplateExcel>>{
        const url = "/api/system/template-excel/paginate/";
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

    /** lấy danh sách Template Excel */
        static async getAll(data : Partial<TemplateExcel> = {}) : Promise<TemplateExcel[]>{
        const url = "/api/system/template-excel/get-all";
        try {
            const res = await window._apiGet(url, data);
            return res;
        }catch(err : any){
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return [];
        }
    }

    /** cập nhật Template Excel */
    static async upsert(data : Partial<TemplateExcel>) : Promise<TemplateExcel|null>{
        const url = "/api/system/template-excel/store";
        try {
            const res = await window._apiCreate(url, data);
            return res;
        }catch(err:any){
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return null;
        }
    }

    /** xóa Template Excel */
    static async delete(id: number) : Promise<boolean>{
        const url = `/api/system/template-excel/delete/${id}`;
        try {
            const res = await window._apiDelete(url);
            return res as boolean;
        }catch(err:any){
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return false;
        }
    }
}
