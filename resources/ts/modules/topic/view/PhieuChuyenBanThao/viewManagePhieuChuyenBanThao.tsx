import React, { useCallback, useEffect } from "react";
import { mountReactComponentOnReady, readRootDataProps } from "../../../core/utils/helpers";
import { ComponentPagination } from "../../../page/component/pagination";
import type { PagiInfo } from "../../../page/type";
import type { DonVi } from "../../../user/type";
import { PhieuChuyenBanThaoApi } from "../../api/PhieuChuyenBanThaoApi";
import { FilterPhieuChuyenBanThaoComponent } from "../../component/PhieuChuyenBanThao/FilterPhieuChuyenBanThao";
import { TablePhieuChuyenBanThaoComponent } from "../../component/PhieuChuyenBanThao/TablePhieuChuyenBanThao";
import { useManagePhieuChuyenBanThaoStore } from "../../store/PhieuChuyenBanThao/managePhieuChuyenBanThaoStore";
import type { FilterPhieuChuyenBanThao, PhieuChuyenBanThao } from "../../type";

interface ViewManagePhieuChuyenBanThaoProps {
    listDonvi: DonVi[];
}

export const ViewManagePhieuChuyenBanThao = React.memo((props: ViewManagePhieuChuyenBanThaoProps) => {
    const { listDonvi } = props;
    const filter = useManagePhieuChuyenBanThaoStore((state) => state.filter);
    const pagiInfo = useManagePhieuChuyenBanThaoStore((state) => state.pagiInfo);
    const setPagiInfo = useManagePhieuChuyenBanThaoStore((state) => state.setPagiInfo);
    const setListPhieuChuyenBanThao = useManagePhieuChuyenBanThaoStore((state) => state.setListPhieuChuyenBanThao);
    const setIsLoadingSearch = useManagePhieuChuyenBanThaoStore((state) => state.setIsLoadingSearch);

    const getListPhieuChuyenBanThao = useCallback((page?: string, callBack?: () => void) => {
        window._toastbox("Đang lấy dữ liệu vui lòng chờ");
        setIsLoadingSearch(true);
        PhieuChuyenBanThaoApi.getPaginate(filter as FilterPhieuChuyenBanThao, page).then(
            (res: { listResult: PhieuChuyenBanThao[]; pagiInfo: PagiInfo }) => {
                window._toastbox("Tải dữ liệu thành công");
                setPagiInfo(res.pagiInfo);
                setListPhieuChuyenBanThao(res.listResult);
                callBack?.();
            },
        ).finally(() => {
            setIsLoadingSearch(false);
        });
    }, [filter, setIsLoadingSearch, setListPhieuChuyenBanThao, setPagiInfo]);

    const handleDeletePhieuChuyenBanThao = useCallback((record: PhieuChuyenBanThao) => {
        if (!record.id) {
            return;
        }
        const isConfirmed = window.confirm("Bạn có chắc chắn muốn xóa phiếu chuyển bản thảo này không?");
        if (!isConfirmed) {
            return;
        }
        PhieuChuyenBanThaoApi.delete(record.id).then((ok) => {
            if (!ok) {
                return;
            }
            window._toastbox("Xóa phiếu chuyển bản thảo thành công", "success");
            setListPhieuChuyenBanThao((prev) => prev.filter((item) => item.id !== record.id));
        });
    }, [setListPhieuChuyenBanThao]);

    useEffect(() => {
        getListPhieuChuyenBanThao();
    }, []);

    return (
        <div className="px-2">
            <FilterPhieuChuyenBanThaoComponent listDonvi={listDonvi} onSearch={getListPhieuChuyenBanThao} />
            <TablePhieuChuyenBanThaoComponent onDelete={handleDeletePhieuChuyenBanThao} />
            <ComponentPagination pagiInfo={pagiInfo} callBack={getListPhieuChuyenBanThao} />
        </div>
    );
});

const ROOT_ID = "root-manage-phieu-chuyen-ban-thao";
const bladeProps: ViewManagePhieuChuyenBanThaoProps = {
    listDonvi: [] as DonVi[],
    ...readRootDataProps<ViewManagePhieuChuyenBanThaoProps>(ROOT_ID),
};
mountReactComponentOnReady(ROOT_ID, <ViewManagePhieuChuyenBanThao {...bladeProps} />);
