
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

    /** upload file template Excel — đặt tên file theo key để ghi đè khi cập nhật */
    static async uploadTemplateFile(file: File, key: string): Promise<string | null> {
        const url = "/api/system/template-excel/upload";
        const formData = new FormData();
        formData.append("file", file);
        formData.append("key", key);
        try {
            const res = await window._apiUpload(url, formData);
            return res?.path_file_template ?? null;
        } catch (err: any) {
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra khi tải file lên, vui lòng thử lại", "danger");
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
