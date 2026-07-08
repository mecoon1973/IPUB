import React, { useMemo, useState } from "react";
import type { DonVi } from "../../../user/type/DonVi";
import ComponentTree from "../../../page/component/componentTree";

interface TreeDonviProps {
    /** danh sách đơn vị */
    listDonvi: DonVi[];
    /** hàm để ở bên ngoài có thể sử lý khi sau khi đã chọn đơn vị */
    handlerChooseDonvi?: (donvi: DonVi) => void;
    /** hàm bắt sự kiện khi sử dụng select cho chọn đơn vị */
    handlerSelectedId?: (id: number | number[]) => void;
    /** sử dụng select cho chọn đơn vị */
    usingselectChoose?: boolean;
    /** id của đơn vị đã chọn */
    selectedId?: number | number[];
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
    /** callback khi chọn action từ menu chuột phải */
    onContextAction?: (action: "delete" | "edit" | "add-child", donvi: DonVi) => void;
}

const TreeDonvi = React.memo((props: TreeDonviProps) => {
    const {
        listDonvi,
        handlerChooseDonvi,
        handlerSelectedId,
        selectedId,
        usingselectChoose,
        openByDefault,
        height,
        width,
        rowHeight,
        indent,
        onContextAction,
    } = props;
    const isSelectChoose = usingselectChoose ?? false;
    const [contextRecord, setContextRecord] = useState<DonVi | null>(null);

    const contextMenuItems = useMemo(() => {
        if (isSelectChoose || !onContextAction || !contextRecord) return [];
        return [
            <button key="add" type="button" className="dropdown-item" onClick={() => onContextAction("add-child", contextRecord)}>
                Thêm
            </button>,
            <button key="edit" type="button" className="dropdown-item" onClick={() => onContextAction("edit", contextRecord)}>
                Sửa
            </button>,
            <button key="delete" type="button" className="dropdown-item" onClick={() => onContextAction("delete", contextRecord)}>
                Xóa
            </button>,
        ];
    }, [isSelectChoose, onContextAction, contextRecord]);

    return (
        <ComponentTree<DonVi>
            listData={listDonvi}
            getLabel={(row) => row.TenDonVi}
            rootParentKey="0"
            usingselectChoose={isSelectChoose}
            {...(selectedId !== undefined ? { selectedId } : {})}
            {...(handlerSelectedId ? { handlerSelectedId } : {})}
            {...(handlerChooseDonvi ? { handlerChooseData: handlerChooseDonvi } : {})}
            {...(openByDefault !== undefined ? { openByDefault } : {})}
            {...(height !== undefined ? { height } : {})}
            {...(width !== undefined ? { width } : {})}
            {...(rowHeight !== undefined ? { rowHeight } : {})}
            {...(indent !== undefined ? { indent } : {})}
            contextMenuItems={contextMenuItems}
            onContextMenuRecordChange={(record) => setContextRecord(record)}
        />
    );
});

export default TreeDonvi;
