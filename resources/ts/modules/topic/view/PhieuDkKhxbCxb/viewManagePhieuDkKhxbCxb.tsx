import React, { useCallback, useEffect } from "react";
import { mountReactComponentOnReady, readRootDataProps } from "../../../core/utils/helpers";
import { ComponentPagination } from "../../../page/component/pagination";
import type { PagiInfo } from "../../../page/type";
import type { User } from "../../../user/type";
import { PhieuDkKhxbCxbApi } from "../../api/PhieuDkKhxbCxbApi";
import { FilterPhieuDkKhxbCxbComponent } from "../../component/PhieuDkKhxbCxb/FilterPhieuDkKhxbCxb";
import { TablePhieuDkKhxbCxbComponent } from "../../component/PhieuDkKhxbCxb/TablePhieuDkKhxbCxb";
import { ModalCapMaSoCxb } from "../../component/PhieuDkKhxbCxb/ModalCapMaSoCxb";
import { useManagePhieuDkKhxbCxbStore } from "../../store/PhieuDkKhxbCxb/managePhieuDkKhxbCxbStore";
import type { FilterPhieuDkKhxbCxb, PhieuDkKhxbCxb } from "../../type";

interface ViewManagePhieuDkKhxbCxbProps {
    listUsers: User[];
}

export const ViewManagePhieuDkKhxbCxb = React.memo((props: ViewManagePhieuDkKhxbCxbProps) => {
    const { listUsers } = props;
    const filter = useManagePhieuDkKhxbCxbStore((state) => state.filter);
    const pagiInfo = useManagePhieuDkKhxbCxbStore((state) => state.pagiInfo);
    const setPagiInfo = useManagePhieuDkKhxbCxbStore((state) => state.setPagiInfo);
    const setListPhieuDkKhxbCxb = useManagePhieuDkKhxbCxbStore((state) => state.setListPhieuDkKhxbCxb);
    const setIsLoadingSearch = useManagePhieuDkKhxbCxbStore((state) => state.setIsLoadingSearch);

    const getListPhieuDkKhxbCxb = useCallback((page?: string, callBack?: () => void) => {
        window._toastbox("Đang lấy dữ liệu vui lòng chờ");
        setIsLoadingSearch(true);
        PhieuDkKhxbCxbApi.getPaginate(filter as FilterPhieuDkKhxbCxb, page).then((res: { listResult: PhieuDkKhxbCxb[]; pagiInfo: PagiInfo }) => {
            window._toastbox("Tải dữ liệu thành công");
            setPagiInfo(res.pagiInfo);
            setListPhieuDkKhxbCxb(res.listResult);
            callBack?.();
        }).finally(() => {
            setIsLoadingSearch(false);
        });
    }, [filter, setIsLoadingSearch, setListPhieuDkKhxbCxb, setPagiInfo]);

    useEffect(() => {
        getListPhieuDkKhxbCxb();
    }, []);

    return (
        <div className="px-2">
            <FilterPhieuDkKhxbCxbComponent onSearch={getListPhieuDkKhxbCxb} />
            <TablePhieuDkKhxbCxbComponent listUsers={listUsers} />
            <ComponentPagination pagiInfo={pagiInfo} callBack={getListPhieuDkKhxbCxb} />
            <ModalCapMaSoCxb onSuccess={() => getListPhieuDkKhxbCxb()} />
        </div>
    );
});

const ROOT_ID = "root-manage-phieu-dk-khxb-cxb";
const bladeProps: ViewManagePhieuDkKhxbCxbProps = {
    listUsers: [] as User[],
    ...readRootDataProps<ViewManagePhieuDkKhxbCxbProps>(ROOT_ID),
};
mountReactComponentOnReady(ROOT_ID, <ViewManagePhieuDkKhxbCxb {...bladeProps} />);
