
import { defaultPagiInfo, type PagiResult } from "../../page/type";
import type { TemplateExport, TemplateFileField } from "../type/TemplateExport";

export class TemplateExportApi{

    static readonly conditionDefault : Partial<TemplateExport> = {
        IsDeleted : false,
    }

    static async getPaginateTemplateExport(data : Partial<TemplateExport> = TemplateExportApi.conditionDefault, page = 'page-1') : Promise<PagiResult<TemplateExport>>{
        const url = "/api/system/template-export/paginate/";
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

    static async getAll(data : Partial<TemplateExport> = {}) : Promise<TemplateExport[]>{
        const url = "/api/system/template-export/get-all";
        try {
            const res = await window._apiGet(url, data);
            return res;
        }catch(err : any){
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return [];
        }
    }

    static async upsert(data : Partial<TemplateExport>) : Promise<TemplateExport|null>{
        const url = "/api/system/template-export/store";
        try {
            const res = await window._apiCreate(url, data);
            return res;
        }catch(err:any){
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return null;
        }
    }

    static async uploadTemplateFile(
        file: File,
        key: string,
        field: TemplateFileField,
    ): Promise<string | null> {
        const url = "/api/system/template-export/upload";
        const formData = new FormData();
        formData.append("file", file);
        formData.append("key", key);
        formData.append("field", field);
        try {
            const res = await window._apiUpload(url, formData);
            return res?.[field] ?? null;
        } catch (err: any) {
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra khi tải file lên, vui lòng thử lại", "danger");
            return null;
        }
    }

    static async delete(id: number) : Promise<boolean>{
        const url = `/api/system/template-export/delete/${id}`;
        try {
            const res = await window._apiDelete(url);
            return res as boolean;
        }catch(err:any){
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return false;
        }
    }
}
