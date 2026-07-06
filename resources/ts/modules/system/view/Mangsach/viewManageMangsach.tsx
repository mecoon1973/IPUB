import React, { useCallback, useEffect, useMemo, useState } from "react";
import type { Mangsach } from "../../type/MangSach";
import { ComponentPagination } from "../../../page/component/pagination";
import { readRootDataProps } from "../../../core/utils/helpers";
import { mountReactComponentOnReady } from "../../../core/utils/helpers";
import { MangsachApi } from "../../api/MangsachApi";
import ComponentTree from "../../../page/component/componentTree";
import type { DataTree } from "../../../core/types/baseTreeEntity";

interface ViewManageMangsachProps {

}

export const ViewManageMangsach = React.memo((props: ViewManageMangsachProps) => {
    const {} = props;
    const [listMangsach, setListMangsach] = useState<Mangsach[]>([]);
    const [contextMangsach, setContextMangsach] = useState<Mangsach | null>(null);

    const contextMenuItems = useMemo(() => {
        const parentId = contextMangsach?.id ?? "";
        return [
            <button
                type="button"
                className="dropdown-item"
                onClick={() => {
                    window.location.href = `/he-thong/mang-sach/cap-nhat?parentId=${parentId}`;
                }}
            >
                Thêm
            </button>,
            <button
                type="button"
                className="dropdown-item"
                onClick={() => {
                    window.location.href = `/he-thong/mang-sach/cap-nhat/${contextMangsach?.id}`;
                }}
            >
                Sửa mảng sách
            </button>,
            <button
                type="button"
                className="dropdown-item"
                onClick={() => {
                    MangsachApi.delete(contextMangsach?.id ?? 0).then((result) => {
                        if(result){
                            window._toastbox("Xóa mảng sách thành công", "success");
                            setListMangsach(listMangsach.filter((mangsach) => mangsach.id !== contextMangsach?.id));
                        }
                    });
                }}
            >
                Xóa mảng sách
            </button>,
        ];
    }, [setListMangsach, contextMangsach]);

    const getListMangsach = useCallback(() => {
        const conditions = {
            IsDeleted : false
        }
        MangsachApi.getListMangsach(conditions).then((res: Mangsach[]) => {
            setListMangsach(res);
        });
    }, [setListMangsach]);

    useEffect(() => {
        getListMangsach();
    }, []);
    return (
        <div className="px-2">
            <div className="py-2 px-2 border-bottom">
                <a href="/he-thong/mang-sach/cap-nhat" className="btn btn-link text-success text-decoration-none border p-0 fw-semibold">
                    + Thêm Mảng sách
                </a>
            </div>
            <div className="py-2">
                <ComponentTree
                    listData={listMangsach}
                    getLabel={(row: Mangsach) => row.TenMang ?? ""}
                    rootParent={
                        {
                            id: "-1",
                            name: "Danh mục mảng sách",
                            children: [],
                        } as DataTree
                    }
                    usingselectChoose={false}
                    openByDefault={true}
                    contextMenuItems={contextMenuItems}
                    onContextMenuRecordChange={setContextMangsach}
                />
            </div>
        </div>
    );
});


const ROOT_ID = "root-manage-mangsach";
const bladeProps = readRootDataProps<ViewManageMangsachProps>(ROOT_ID) ?? {};
mountReactComponentOnReady(ROOT_ID, <ViewManageMangsach {...bladeProps} />);
