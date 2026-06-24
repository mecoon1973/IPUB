import type { ReactNode } from "react";


export interface DataTree {
    id: string;
    name: string;
    children: DataTree[];
}

/**
 * Props chung cho component cây (flat → arborist) — T phải là entity phân cấp.
 * Truyền `getLabel` để map tên hiển thị (TenDonVi, TenQuyen, …) theo từng model.
 */
export interface TreeComponentProps<T extends BaseTreeEntity> {
    /** danh sách dữ liệu trong cây */
    listData: T[];

    /** các thuộc tính dùng cho style */

    /** root parent key sử dụng khi muốn bọc tất cả các thằng con trong 1 thằng cha */
    rootParent?: DataTree;
    /** Nhãn hiển thị trên node (mỗi model một field khác nhau). */
    getLabel: (row: T) => string;
    /**
     * Khóa cha tương ứng gốc cây (chuỗi hóa ParentID), mặc định "-1".
     * Đổi nếu API dùng 0 hoặc giá trị khác cho node gốc.
     */
    rootParentKey?: string
    /** mở mặc định */
    openByDefault?: boolean;
    /** chiều cao */
    height?: number;
    /** chiều rộng */
    width?: number;
    /** chiều cao của mỗi dòng */
    rowHeight?: number;
    /** độ rộng của mỗi dòng */
    indent?: number;

    /** các giá trị sử lý sự kiện select và handler */

    /** Sử dụng select cho chọn đơn vị */
    usingselectChoose?: boolean;
    /**
     * Chỉ dùng kèm `selectedId: number[]`.
     * Bật `true`: chọn/bỏ chọn node cha sẽ áp dụng cho toàn bộ con cháu;
     * `handlerSelectedId` nhận mảng id đã cập nhật (cha + con).
     */
    autoSelectChild?: boolean;
    /** id đã chọn: `number` = chọn đơn (Radio), `number[]` = chọn nhiều (Checkbox). */
    selectedId?: number | number[];
    /**
     * Khi `usingselectChoose`: gọi khi user chọn/bỏ chọn node.
     * - `selectedId: number[]` + không `autoSelectChild`: trả `number` (parent tự toggle).
     * - `selectedId: number[]` + `autoSelectChild`: trả `number[]` đầy đủ sau khi chọn cha/con.
     * - `selectedId: number`: trả `number`.
     */
    handlerSelectedId?: (id: number | number[]) => void;
    /** Hàm để ở bên ngoài có thể sử lý khi chọn click node */
    handlerChooseData?: (data: T) => void;

    /** 2 giá trị sử lý context */

    /**
     * Nút menu chuột phải tuỳ chỉnh (mỗi phần tử nên là `<button type="button" className="dropdown-item">`).
     * Không truyền thì dùng mặc định Thêm / Sửa / Xóa (cần `onContextAction`).
     */
    contextMenuItems?: ReactNode[];
    /**
     * Callback để lấy record đang mở context menu ra bên ngoài.
     * - `record` là node đang click chuột phải
     * - `null` khi menu đóng
     */
    onContextMenuRecordChange?: (record: T | null) => void;
}


/**
 * Dữ liệu cơ sở cho bản ghi phân cấp (flat list → cây).
 * Các entity như đơn vị, quyền, … dùng chung khóa id cha/con và thứ tự sắp xếp.
 */
export interface BaseTreeEntity {
    id: number;
    ParentID: number;
    ThuTu?: number;
}
