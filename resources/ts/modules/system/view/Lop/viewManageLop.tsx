import {
    mountReactComponentOnReady,
    readRootDataProps,
} from "../../../core/utils/helpers";
import React, { useCallback, useEffect, useMemo, useState } from "react";
import { Button, Divider, Flex } from "antd";
import { defaultPagiInfo, type PagiInfo } from "../../../page/type";
import type { Lop, Nhom } from "../../type";
import { ComponentPagination } from "../../../page/component/pagination";
import { LopApi } from "../../api/LopApi";
import { TableLop } from "../../component/Lop/TableLop";



interface ViewManageLopProps {

}

export const ViewManageLop = React.memo((props: ViewManageLopProps) => {
    const {} = props;
    const [pagiInfo, setPagiInfo] = useState<PagiInfo>(defaultPagiInfo);
    const [listLop, setListLop] = useState<Lop[]>([]);

    const getListLop = useCallback((page?: string) => {
        const conditions = {
            IsDeleted : false
        }
        LopApi.getPaginateLop(conditions, page).then((res: { listResult: Lop[], pagiInfo: PagiInfo }) => {
            setPagiInfo(res.pagiInfo);
            setListLop(res.listResult);
        });
    }, [setPagiInfo, setListLop]);

    const handleDeleteLop = useCallback((id: number) => {
        const isConfirmed = window.confirm("Bạn có chắc chắn muốn xóa nhóm này không?");
        if (!isConfirmed) return;
        LopApi.delete(id).then((res: boolean) => {
            if(res) {
                window._toastbox("Xóa nhóm thành công", "success");
                setListLop((prev: Lop[]) => prev.filter((lop: Lop) => lop.id !== id));
            }
        });
    }, []);

    useEffect(() => {
        getListLop();
    }, []);

    return (
        <div className="px-2 py-2">
            <Flex align="center" className="px-1">
                <Button type="link" href="/he-thong/lop/cap-nhat" className="text-success fw-semibold px-0">
                    + Thêm Lớp
                </Button>
            </Flex>
            <Divider className="my-2" />
            <TableLop listLop={listLop} handleDeleteLop={handleDeleteLop}/>
            <ComponentPagination pagiInfo={pagiInfo} callBack={getListLop} />
        </div>
    );
});


const ROOT_ID = "root-manage-lop";
const bladeProps = readRootDataProps<ViewManageLopProps>(ROOT_ID) ?? {};
mountReactComponentOnReady(ROOT_ID, <ViewManageLop {...bladeProps} />);
