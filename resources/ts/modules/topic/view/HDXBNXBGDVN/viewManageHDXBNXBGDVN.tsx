import React, { useCallback, useEffect } from 'react';
import type { DonVi } from '../../../user/type';
import type { Mangsach } from '../../../system/type';
import type { FilterHDXBNXBGDVN, HDXBNXBGDVN } from '../../type';
import FilterHDXBNXBGDVNComponent from '../../component/HDXBNXBGD/FilterHDXBNXBGDVNComponent';
import TableHDXBNXBGDVNComponent from '../../component/HDXBNXBGD/TableHDXBNXBGDVNComponent';
import ActionToolbarHDXBNXBGDVN from '../../component/HDXBNXBGD/ActionToolbarHDXBNXBGDVN';
import ModalPhanCongDocDuyetHDXBNXBGDVN from '../../component/HDXBNXBGD/ModalPhanCongDocDuyetHDXBNXBGDVN';
import ModalPhanCongDocDuyetAssignHDXBNXBGDVN from '../../component/HDXBNXBGD/ModalPhanCongDocDuyetAssignHDXBNXBGDVN';
import ModalXetDuyetDeTaiHDXBNXBGDVN from '../../component/HDXBNXBGD/ModalXetDuyetDeTaiHDXBNXBGDVN';
import ModalActionsHDXBNXBGDVN from '../../component/HDXBNXBGD/ModalActionsHDXBNXBGDVN';
import { mountReactComponentOnReady, readRootDataProps } from '../../../core/utils/helpers';
import { useManageHDXBNXBGDVNStore } from '../../store/HDXBNXBGDVN/manageHDXBNXBGDVN';
import { HDXBNXBGDVNApi } from '../../api/HDXBNXBGDVNApi';
import type { PagiInfo } from '../../../page/type';
import { useDataViewStore } from '../../../system/store/useDataViewStore';
import { ComponentPagination } from '../../../page/component/pagination';
import type { User } from '../../../user/type';

interface ViewManageHDXBNXBGDVNProps {
    listDonvi: DonVi[];
    listMangsach: Mangsach[];
    mapTrangThai: Record<number, string>;
    listBTV: User[];
}

export const ViewManageHDXBNXBGDVN = React.memo((props: ViewManageHDXBNXBGDVNProps) => {
    const { listDonvi, mapTrangThai, listBTV } = props;
    const filter = useManageHDXBNXBGDVNStore((state) => state.filter);
    const setListHDXBNXBGD = useManageHDXBNXBGDVNStore((state) => state.setListHDXBNXBGD);
    const setIsLoadingSearch = useManageHDXBNXBGDVNStore((state) => state.setIsLoadingSearch);
    const pagiInfo = useManageHDXBNXBGDVNStore((state) => state.pagiInfo);
    const setPagiInfo = useManageHDXBNXBGDVNStore((state) => state.setPagiInfo);
    const setDataView = useDataViewStore((state) => state.setData);

    const getListHDXBNXBGD = useCallback((page?: string, callBack?: () => void) => {
        window._toastbox("Đang lấy dữ liệu vui lòng chờ");
        setIsLoadingSearch(true);
        HDXBNXBGDVNApi.getPaginate(filter as FilterHDXBNXBGDVN, page).then((res: { listResult: HDXBNXBGDVN[]; pagiInfo: PagiInfo }) => {
            window._toastbox("Tải dữ liệu thành công");
            setPagiInfo(res.pagiInfo);
            setListHDXBNXBGD(res.listResult);
            callBack?.();
        }).finally(() => {
            setIsLoadingSearch(false);
        });
    }, [filter, setListHDXBNXBGD, setIsLoadingSearch, setPagiInfo]);

    useEffect(() => {
        setDataView({ listDonvi, mapTrangThai });
        getListHDXBNXBGD();
    }, []);

    return (
        <div className="px-2">
            <FilterHDXBNXBGDVNComponent listDonvi={listDonvi} getListHDXBNXBGD={getListHDXBNXBGD} />
            <ActionToolbarHDXBNXBGDVN />
            <TableHDXBNXBGDVNComponent />
            <ComponentPagination pagiInfo={pagiInfo} callBack={getListHDXBNXBGD} />
            <ModalPhanCongDocDuyetAssignHDXBNXBGDVN listBTV={listBTV} onSuccess={getListHDXBNXBGD} />
            <ModalPhanCongDocDuyetHDXBNXBGDVN onSuccess={getListHDXBNXBGD} />
            <ModalXetDuyetDeTaiHDXBNXBGDVN listDonvi={listDonvi} onSuccess={getListHDXBNXBGD} />
            <ModalActionsHDXBNXBGDVN onSuccess={getListHDXBNXBGD} />
        </div>
    );
});

const ROOT_ID = "root-manage-hdxb-nxbgdvn";
const bladeProps: ViewManageHDXBNXBGDVNProps = {
    listDonvi: [] as DonVi[],
    listMangsach: [] as Mangsach[],
    mapTrangThai: {} as Record<number, string>,
    listBTV: [] as User[],
    ...readRootDataProps<ViewManageHDXBNXBGDVNProps>(ROOT_ID),
};
mountReactComponentOnReady(ROOT_ID, <ViewManageHDXBNXBGDVN {...bladeProps} />);
