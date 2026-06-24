import {
    mountReactComponentOnReady,
    readRootDataProps,
} from "../../../core/utils/helpers";
import React, { useCallback, useEffect, useMemo, useState } from "react";
import { useManageCanboInNhomStore } from '../../store/Nhom/manageCanboInNhomStore';
import { UserApi } from "../../../user/api/UserApi";
import type { PagiInfo } from "../../../page/type";
import type { DonVi, User } from "../../../user/type";
import { ComponentPagination } from "../../../page/component/pagination";
import type { Nhom } from "../../type";
import { TableCanboInNhom } from "../../component/Nhom/TableCanboInNhom";
import { FilterCanboInNhom } from "../../component/Nhom/FilterCanboInNhom";
import { DonviApi } from "../../api/DonviApi";

interface ViewManageCanboInNhomProps {
    nhom: Nhom;
}

export const ViewManageCanboInNhom = React.memo((props: ViewManageCanboInNhomProps) => {
    const { nhom } = props;
    const pagiInfo = useManageCanboInNhomStore((state) => state.pagiInfo);
    const usernameSearch = useManageCanboInNhomStore((state) => state.usernameSearch);
    const selectedDonvi = useManageCanboInNhomStore((state) => state.selectedDonvi);
    const setListCanbo = useManageCanboInNhomStore((state) => state.setListCanbo);
    const setListDonvi = useManageCanboInNhomStore((state) => state.setListDonvi);
    const setPagiInfo = useManageCanboInNhomStore((state) => state.setPagiInfo);
    const setNhom = useManageCanboInNhomStore((state) => state.setNhom);
    const getListCanbo = useCallback((page?: string) => {
        const conditions = {
            IsDeleted : false,
            IdNhom : nhom.id,
            usernameSearch : usernameSearch,
            ID_DonVi : selectedDonvi?.id,
            relations : ["donvi"],
        }
        window._toastbox("Đang tìm kiếm người dùng")
        UserApi.getPaginateUser(conditions, page).then((res: { listResult: User[], pagiInfo: PagiInfo }) => {
            if(res){
                setPagiInfo(res.pagiInfo);
                setListCanbo(res.listResult);
                window._toastbox("Tìm kiếm người dùng thành công", "success");
            }
        });
    }, [setPagiInfo, setListCanbo, nhom, selectedDonvi, usernameSearch]);

    useEffect(() => {
        DonviApi.getAllDonvi().then((res: DonVi[]) => {
            setListDonvi(res);
        });
        getListCanbo();
        setNhom(nhom);
    }, []);

    return (
        <div className="px-2">
            <FilterCanboInNhom handleSearch={getListCanbo} />
            <TableCanboInNhom />
            <ComponentPagination pagiInfo={pagiInfo} callBack={getListCanbo} />
        </div>
    );
});


const ROOT_ID = "root-manage-canbo-in-nhom";
const bladeProps = readRootDataProps<ViewManageCanboInNhomProps>(ROOT_ID) as ViewManageCanboInNhomProps;
mountReactComponentOnReady(ROOT_ID, <ViewManageCanboInNhom {...bladeProps} />);
