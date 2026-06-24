/**
 * Node Dashboard
 * @interface NodeDashboard
 * @property {string} title Tiêu đề
 * @property {string} type Loại node
 * @property {string} routes Đường dẫn chỉ tồn tại nếu type = "link"
 * @property {NodeDashboard[]} children Các node con
 */
export interface NodeDashboard {
    title: string;
    type: "navbar" | "select" | "link";
    routes?: string;
    children?: NodeDashboard[];
}
/**
 * Thông tin phân trang
 * @interface PagiInfo
 * @property {number[]} pagi_number Mảng các số trang
 * @property {number} last Số trang cuối cùng
 * @property {number} limit Số lượng bản ghi trên mỗi trang
 * @property {number} current_page Số trang hiện tại
 * @property {number} total Tổng số bản ghi
 */
export interface PagiInfo {
    pagi_number: number[];
    last: number;
    limit: number;
    current_page: number;
    total: number;
    query: string;
    route: string;
    startCursor?: string|null;
    endCursor?: string|null;
    hasNextPage?: boolean;
    hasPreviousPage?: boolean;
}

export interface PagiResult<T> {
    listResult: T[];
    pagiInfo: PagiInfo;
}
export const defaultPagiInfo: PagiInfo = {
    pagi_number: [],
    last: 0,
    limit: 0,
    current_page: 0,
    total: 0,
    query: "",
    route: "",
};

export interface Relationships {
    fields?: string[];
    relations?: string[];
    limit?: number;
}
