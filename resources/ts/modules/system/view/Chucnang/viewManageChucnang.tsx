import {
    mountReactComponentOnReady,
    readRootDataProps,
} from "../../../core/utils/helpers";
import React, { useCallback, useEffect, useMemo, useState } from "react";
import type { ChucNang } from "../../type/ChucNang";
import { ChucnangApi } from "../../api/ChucnangApi";
import ComponentTree from "../../../page/component/componentTree";
import type { DataTree } from "../../../core/types/baseTreeEntity";



interface ViewManageChucnangProps {

}

export const ViewManageChucnang = React.memo((props: ViewManageChucnangProps) => {
    const {} = props;
    const [listChucnang, setListChucnang] = useState<ChucNang[]>([]);
    const [contextChucnang, setContextChucnang] = useState<ChucNang | null>(null);

    const getListChucnang = useCallback(() => {
        const conditions = {
            Deleted : false
        }
        ChucnangApi.getAllChucnang(conditions).then((res: ChucNang[]) => {
            setListChucnang(res);
        });
    }, [setListChucnang]);

    const handleDeleteChucnang = useCallback((id: number) => {
        const isConfirmed = window.confirm("Bạn có chắc chắn muốn xóa nhóm này không?");
        if (!isConfirmed) return;
        ChucnangApi.delete(id).then((res: boolean) => {
            if(res) {
                window._toastbox("Xóa nhóm thành công", "success");
                setListChucnang((prev: ChucNang[]) => prev.filter((chucnang: ChucNang) => chucnang.id !== id));
            }
        });
    }, []);

    const contextMenuItems = useMemo(() => {
        const parentId = contextChucnang?.id ?? "";
        return [
            <button
                type="button"
                className="dropdown-item"
                onClick={() => {
                    window.location.href = `/he-thong/chuc-nang/cap-nhat?parentId=${parentId}`;
                }}
            >
                Thêm
            </button>,
            <button
                type="button"
                className="dropdown-item"
                onClick={() => {
                    window.location.href = `/he-thong/chuc-nang/cap-nhat/${contextChucnang?.id}`;
                }}
            >
                Sửa quyền
            </button>,
            <button
                type="button"
                className="dropdown-item"
                onClick={() => { handleDeleteChucnang(contextChucnang?.id ?? 0); }}
            >
                Xóa quyền
            </button>,
            // <button
            //     type="button"
            //     className="dropdown-item"
            //     onClick={() => {
            //     }}
            // >
            //     Gán chức năng cho quyền
            // </button>,
        ];
    }, [setListChucnang, contextChucnang]);


    useEffect(() => {
        getListChucnang();
    }, []);

    return (
        <div className="px-2">
            <div className="py-2 px-2 border-bottom">
                <a href="/he-thong/chuc-nang/cap-nhat" className="btn btn-link text-success text-decoration-none border p-0 fw-semibold">
                    + Thêm Chức năng
                </a>
            </div>
            <div>
                <ComponentTree
                    listData={listChucnang}
                    getLabel={(row: ChucNang) => row.Title ?? ""}
                    usingselectChoose={false}
                    contextMenuItems={contextMenuItems}
                    onContextMenuRecordChange={setContextChucnang}
                    rootParent={
                        {
                            id: "-1",
                            name: "Danh mục chức năng",
                            children: [],
                        } as DataTree
                    }

                />
            </div>
        </div>
    );
});


const ROOT_ID = "root-manage-chucnang";
const bladeProps = readRootDataProps<ViewManageChucnangProps>(ROOT_ID) ?? {};
mountReactComponentOnReady(ROOT_ID, <ViewManageChucnang {...bladeProps} />);
