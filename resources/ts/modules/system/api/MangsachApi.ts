import type { PagiInfo, PagiResult } from "../../page/type";
import type { Mangsach } from "../type";


export class MangsachApi{
    /** Chuẩn hóa sau API: cây dùng ThuTu để sort, DB/API dùng iOrder */
    private static withThuTuFromOrder(rows: Mangsach[]): Mangsach[] {
        return rows.map((row) => ({ ...row, ThuTu: row.iOrder }));
    }

    /** điều kiện mặc định khi lấy danh sách Mảng sách */
    static readonly conditionDefault : Partial<Mangsach> = {
        IsDeleted : false,
    }
    /** lấy danh sách Mảng sách có phân trang */
    static async getPaginateMangsach(data : Partial<Mangsach> = MangsachApi.conditionDefault, page = 'page-1') : Promise<PagiResult<Mangsach>>{
        const url = "/api/system/mang-sach/paginate/";
        try {
            const res = await window._apiGet(url + page, data);
            return {
                ...res,
                listResult: MangsachApi.withThuTuFromOrder(res.listResult ?? []),
            };
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
    static async getListMangsach(data : Record<string, any> = {}) : Promise<Mangsach[]>{
        const url = "/api/system/mang-sach/list";
        try {
            const res = (await window._apiGet(url, data)) as Mangsach[];
            return MangsachApi.withThuTuFromOrder(res);
        }catch(err: any){
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return [];
        }
    }

    /** cập nhật Mảng sách */
    static async upsert(data : Partial<Mangsach>) : Promise<Mangsach|null>{
        const url = "/api/system/mang-sach/store";
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
        const url = `/api/system/mang-sach/delete/${id}`;
        try {
            const res = await window._apiDelete(url);
            return res as boolean;
        }catch(err:any){
            window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            return false;
        }
    }
}
