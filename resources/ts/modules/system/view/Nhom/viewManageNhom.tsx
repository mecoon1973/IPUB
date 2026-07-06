import {
    mountReactComponentOnReady,
    readRootDataProps,
} from "../../../core/utils/helpers";
import React, { useCallback, useEffect, useMemo, useState } from "react";
import { useGetNhom } from "../../hooks/Nhom/useGetNhom";
import { defaultPagiInfo, type PagiInfo } from "../../../page/type";
import type Nhom from "../../type/Nhom";
import type { User } from "../../../user/type/User";
import { UserApi } from "../../../user/api/UserApi";
import { NhomApi } from "../../api/NhomApi";
import { TableNhom } from "../../component/Nhom/TableNhom";
import { ComponentPagination } from "../../../page/component/pagination";



interface ViewManageNhomProps {

}

export const ViewManageNhom = React.memo((props: ViewManageNhomProps) => {
    const {} = props;
    const [pagiInfo, setPagiInfo] = useState<PagiInfo>(defaultPagiInfo);
    const [listNhom, setListNhom] = useState<Nhom[]>([]);

    const getListNhom = useCallback((page?: string) => {
        const conditions = {
            IsDeleted : false
        }
        NhomApi.getListNhom(conditions, page).then((res: { listResult: Nhom[], pagiInfo: PagiInfo }) => {
            setPagiInfo(res.pagiInfo);
            setListNhom(res.listResult);
        });
    }, [setPagiInfo, setListNhom]);

    const handleDeleteNhom = useCallback((id: number) => {
        const isConfirmed = window.confirm("Bạn có chắc chắn muốn xóa nhóm này không?");
        if (!isConfirmed) return;
        NhomApi.delete(id).then((res: boolean) => {
            if(res) {
                window._toastbox("Xóa nhóm thành công", "success");
                setListNhom((prev: Nhom[]) => prev.filter((nhom: Nhom) => nhom.id !== id));
            }
        });
    }, []);

    useEffect(() => {
        getListNhom();
    }, []);
    return (
        <div className="px-2">
            <div className="py-2 px-2 border-bottom">
                <a href="/nhom/cap-nhat" className="btn btn-link text-success text-decoration-none border p-0 fw-semibold">
                    + Thêm nhóm
                </a>
            </div>
            <TableNhom listNhom={listNhom} handleDeleteNhom={handleDeleteNhom}/>
            <ComponentPagination pagiInfo={pagiInfo} callBack={getListNhom} />
        </div>
    );
});


const ROOT_ID = "root-manage-nhom";
const bladeProps = readRootDataProps<ViewManageNhomProps>(ROOT_ID) ?? {};
mountReactComponentOnReady(ROOT_ID, <ViewManageNhom {...bladeProps} />);
