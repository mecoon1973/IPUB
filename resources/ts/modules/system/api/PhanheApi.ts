import type { PhanHe } from '../type';

export class PhanHeApi{


    /** lấy danh sách phân hệ */
        static async getAllPhanhe(data : Partial<PhanHe> = {}) : Promise<PhanHe[]>{
        const url = "/api/system/phan-he/get-all";
        try {
            const res = await window._apiGet(url, data);
            return res;
        }catch(err : any){
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return [];
        }
    }

}
