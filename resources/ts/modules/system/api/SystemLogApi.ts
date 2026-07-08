import { defaultPagiInfo, type PagiResult } from "../../page/type";
import type { SystemLog, SystemLogFilter } from "../type/SystemLog";


export class SystemLogApi{
    /** lấy danh sách Lịch sử thao tác có phân trang */
    static async getPaginateSystemLog(data : SystemLogFilter | Partial<SystemLog>, page = 'page-1') : Promise<PagiResult<SystemLog>>{
        const url = "/api/system/system-log/paginate/";
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

    /** lấy danh sách Tủ sách có phân trang */
    static async getListSystemLog(data : SystemLogFilter | Partial<SystemLog>) : Promise<SystemLog[]>{
        const url = "/api/system/system-log/list";
        try {
            const res = await window._apiGet(url, data);
            return res;
        }catch(err: any){
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return [];
        }
    }

}
