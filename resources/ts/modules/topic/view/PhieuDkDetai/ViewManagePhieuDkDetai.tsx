import React, { useCallback, useEffect, useState } from "react";
import { mountReactComponentOnReady, readRootDataProps } from "../../../core/utils/helpers";
import { ComponentPagination } from "../../../page/component/pagination";
import { defaultPagiInfo, type PagiInfo } from "../../../page/type";
import type { FilterPhieuDkDetai, PhieuDkDetai } from "../../type";
import { PhieuDkDetaiApi } from "../../api/PhieuDkDetaiApi";
import { FilterPhieuDkDetaiComponent } from "../../component/PhieuDkDetai/FilterPhieuDkDetai";
import { useManagePhieuDkDetaiStore } from "../../store/PhieuDkDetai/managePhieuDkDetaiStore";
import { TablePhieuDkDetaiComponent } from "../../component/PhieuDkDetai/TablePhieuDkDetai";
import { ModalInfoPhieuDkDetaiComponent } from "../../component/PhieuDkDetai/ModalInfoPhieuDkDetai";
import type { DonVi } from "../../../user/type";
import type { Doituong, Mangsach } from "../../../system/type";
import { ProcessStepInfoModalComponent } from "../../component/PhieuDkDetai/ProcessStepInfoModalPhieuDkDetai";
import { ModalXetDuyetNxbgdvnComponent } from "../../component/PhieuDkDetai/ModalXetDuyetNxbgdvnComponent";
import { ModalCapMaSoNxbgdComponent } from "../../component/PhieuDkDetai/ModalCapMaSoNxbgdComponent";
import { useDataViewStore } from "../../../system/store/useDataViewStore";
interface ViewManagePhieuDkDetaiProps {
    listDonvi: DonVi[];
    listMangsach: Mangsach[];
    mapTrangThai: Record<number, string>;
    listDoituong: Doituong[];
}

export const ViewManagePhieuDkDetai = React.memo((props: ViewManagePhieuDkDetaiProps) => {
    const { listDonvi, mapTrangThai, listMangsach, listDoituong } = props;
    const setListPhieuDkDetai = useManagePhieuDkDetaiStore((state) => state.setListPhieuDkDetai)
    const setDataView = useDataViewStore((state) => state.setData)
    const setPagiInfo = useManagePhieuDkDetaiStore((state) => state.setPagiInfo)
    const pagiInfo = useManagePhieuDkDetaiStore((state) => state.pagiInfo)
    const filter = useManagePhieuDkDetaiStore((state) => state.filter)
    const setIsLoadingSearch = useManagePhieuDkDetaiStore((state) => state.setIsLoadingSearch)
    const getListPhieuDkDetai = useCallback((page?: string, callBack?: () => void) => {
        window._toastbox("Đang lấy lữ liệu vui lòng chờ");
        setIsLoadingSearch(true);
        PhieuDkDetaiApi.getPaginate(filter as FilterPhieuDkDetai, page).then((res: { listResult: PhieuDkDetai[], pagiInfo: PagiInfo }) => {
            window._toastbox("Tải dữ liệu thành công");
            setPagiInfo(res.pagiInfo);
            setListPhieuDkDetai(res.listResult);
            callBack?.();
        }).finally(() => {
            setIsLoadingSearch(false);
        });
    }, [setListPhieuDkDetai, filter]);

    const handleDeletePhieuDkDetai = useCallback((id: number) => {
        const isConfirmed = window.confirm("Bạn có chắc chắn muốn xóa nhóm này không?");
        if (!isConfirmed) return;
        PhieuDkDetaiApi.delete(id).then((res: boolean) => {
            if(res) {
                window._toastbox("Xóa nhóm thành công", "success");
                setListPhieuDkDetai((prev: PhieuDkDetai[]) => prev.filter((phieuDkDetai: PhieuDkDetai) => phieuDkDetai.id !== id));
            }
        });
    }, []);


    useEffect(() => {
        setDataView({ listDonvi, mapTrangThai, listMangsach, listDoituong });
        getListPhieuDkDetai();
    }, []);

    return (
        <div className="px-2">
            <FilterPhieuDkDetaiComponent getListPhieuDkDetai={getListPhieuDkDetai}/>
            <TablePhieuDkDetaiComponent />
            <ModalInfoPhieuDkDetaiComponent />
            <ProcessStepInfoModalComponent />
            <ModalXetDuyetNxbgdvnComponent onSuccess={getListPhieuDkDetai} />
            <ModalCapMaSoNxbgdComponent onSuccess={getListPhieuDkDetai} />
            <ComponentPagination pagiInfo={pagiInfo} callBack={getListPhieuDkDetai} />
        </div>
    );
});


const ROOT_ID = "root-manage-phieu-dk-detai";
const bladeProps: ViewManagePhieuDkDetaiProps = {
    listDonvi : [] as DonVi[],
    mapTrangThai : {} as Record<number, string>,
    listMangsach : [] as Mangsach[],
    listDoituong : [] as Doituong[],
    ...readRootDataProps<ViewManagePhieuDkDetaiProps>(ROOT_ID),
};
mountReactComponentOnReady(ROOT_ID, <ViewManagePhieuDkDetai {...bladeProps} />);
