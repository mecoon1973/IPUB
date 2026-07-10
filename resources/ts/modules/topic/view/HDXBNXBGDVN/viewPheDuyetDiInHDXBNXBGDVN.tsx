import React, { useCallback, useEffect, useMemo, useState } from "react";

import { Button } from "antd";

import { CheckOutlined } from "@ant-design/icons";

import { mountReactComponentOnReady, readRootDataProps } from "../../../core/utils/helpers";

import { ComponentPagination } from "../../../page/component/pagination";

import type { PagiInfo } from "../../../page/type";

import type { DonVi } from "../../../user/type";
import type { Lop } from "../../../system/type/Lop";

import { HDXBNXBGDVNApi } from "../../api/HDXBNXBGDVNApi";

import FilterPheDuyetDiInComponent from "../../component/HDXBNXBGD/FilterPheDuyetDiIn";

import ModalChiTietPheDuyetDiIn from "../../component/HDXBNXBGD/ModalChiTietPheDuyetDiIn";
import ModalPheDuyetDiInHDXBNXBGDVN from "../../component/HDXBNXBGD/ModalPheDuyetDiInHDXBNXBGDVN";

import TablePheDuyetDiInComponent from "../../component/HDXBNXBGD/TablePheDuyetDiIn";

import { usePheDuyetDiInStore } from "../../store/HDXBNXBGDVN/pheDuyetDiInStore";

import { defaultFilterPheDuyetDiIn, type FilterPheDuyetDiIn, type PheDuyetDiInRow } from "../../type";



interface ViewPheDuyetDiInHDXBNXBGDVNProps {

    listDonvi: DonVi[];

    listLop: Lop[];

}



function parseIdsDeTaiFromUrl(): number[] {

    const params = new URLSearchParams(window.location.search);

    const raw = params.get("idsDeTai") ?? params.get("ids") ?? "";

    if (!raw) {

        return [];

    }

    return raw

        .split(",")

        .map((value) => Number(value.trim()))

        .filter((id) => id > 0);

}



export const ViewPheDuyetDiInHDXBNXBGDVN = React.memo((props: ViewPheDuyetDiInHDXBNXBGDVNProps) => {

    const { listDonvi, listLop } = props;

    const initialIdsDeTai = useMemo(() => parseIdsDeTaiFromUrl(), []);

    const filter = usePheDuyetDiInStore((state) => state.filter);

    const listRows = usePheDuyetDiInStore((state) => state.listRows);

    const pagiInfo = usePheDuyetDiInStore((state) => state.pagiInfo);

    const selectedRowKeys = usePheDuyetDiInStore((state) => state.selectedRowKeys);

    const setFilter = usePheDuyetDiInStore((state) => state.setFilter);

    const setPagiInfo = usePheDuyetDiInStore((state) => state.setPagiInfo);

    const setListRows = usePheDuyetDiInStore((state) => state.setListRows);

    const setIsLoadingSearch = usePheDuyetDiInStore((state) => state.setIsLoadingSearch);

    const setSelectedRowKeys = usePheDuyetDiInStore((state) => state.setSelectedRowKeys);



    const [modalOpen, setModalOpen] = useState(false);

    const [modalItems, setModalItems] = useState<PheDuyetDiInRow[]>([]);

    const [detailModalOpen, setDetailModalOpen] = useState(false);

    const [detailRow, setDetailRow] = useState<PheDuyetDiInRow | null>(null);



    const getListPheDuyetDiIn = useCallback((

        page?: string,

        callBack?: () => void,

        overrideFilter?: FilterPheDuyetDiIn,

    ) => {

        const payload = overrideFilter ?? (filter as FilterPheDuyetDiIn);

        window._toastbox("Đang lấy dữ liệu vui lòng chờ");

        setIsLoadingSearch(true);

        HDXBNXBGDVNApi.getPaginatePheDuyetDiIn(payload, page).then(

            (res: { listResult: PheDuyetDiInRow[]; pagiInfo: PagiInfo }) => {

                window._toastbox("Tải dữ liệu thành công");

                setPagiInfo(res.pagiInfo);

                setListRows(res.listResult);

                callBack?.();

            },

        ).finally(() => {

            setIsLoadingSearch(false);

        });

    }, [filter, setIsLoadingSearch, setListRows, setPagiInfo]);



    const openApproveModal = useCallback((rows: PheDuyetDiInRow[]) => {

        // const eligibleRows = rows.filter((row) => !row.DaPheDuyetDiIn);

        // if (eligibleRows.length === 0) {

        //     window._toastbox("Vui lòng chọn ít nhất một sách chưa phê duyệt đi in", "danger");

        //     return;

        // }

        setModalItems(rows);

        setModalOpen(true);

    }, []);



    const handleApproveSelected = useCallback(() => {

        const ids = new Set(selectedRowKeys.map((key) => String(key)));

        const rows = listRows.filter((row) => ids.has(String(row.id)));

        openApproveModal(rows);

    }, [listRows, openApproveModal, selectedRowKeys]);



    const handleApproveRow = useCallback((row: PheDuyetDiInRow) => {

        openApproveModal([row]);

    }, [openApproveModal]);



    const handleViewDetail = useCallback((row: PheDuyetDiInRow) => {

        setDetailRow(row);

        setDetailModalOpen(true);

    }, []);



    const handleDetailModalClose = useCallback(() => {

        setDetailModalOpen(false);

        setDetailRow(null);

    }, []);



    const handleModalClose = useCallback(() => {

        setModalOpen(false);

        setModalItems([]);

    }, []);



    const handleModalSuccess = useCallback(() => {

        setSelectedRowKeys([]);

        getListPheDuyetDiIn();

    }, [getListPheDuyetDiIn, setSelectedRowKeys]);



    useEffect(() => {

        const nextFilter: FilterPheDuyetDiIn = initialIdsDeTai.length > 0

            ? { ...defaultFilterPheDuyetDiIn, idsDeTai: initialIdsDeTai }

            : defaultFilterPheDuyetDiIn;

        setFilter(nextFilter);

        getListPheDuyetDiIn(undefined, undefined, nextFilter);

    }, []);



    return (

        <div className="px-2">

            <FilterPheDuyetDiInComponent listDonvi={listDonvi} onSearch={getListPheDuyetDiIn} />

            <div className="d-flex flex-wrap align-items-center gap-3 py-2 px-2 border-bottom bg-white">

                <Button

                    type="link"

                    className="p-0 d-inline-flex align-items-center gap-1"

                    icon={<CheckOutlined />}

                    onClick={handleApproveSelected}

                >

                    Phê duyệt đi in

                </Button>

            </div>

            <TablePheDuyetDiInComponent onApproveRow={handleApproveRow} onViewDetail={handleViewDetail} />

            <ComponentPagination pagiInfo={pagiInfo} callBack={getListPheDuyetDiIn} />

            <ModalPheDuyetDiInHDXBNXBGDVN

                open={modalOpen}

                items={modalItems}

                onClose={handleModalClose}

                onSuccess={handleModalSuccess}

            />

            <ModalChiTietPheDuyetDiIn

                open={detailModalOpen}

                row={detailRow}

                listLop={listLop}

                onClose={handleDetailModalClose}

            />

        </div>

    );

});



const ROOT_ID = "root-phe-duyet-di-in-hdxb-nxbgdvn";

const bladeProps: ViewPheDuyetDiInHDXBNXBGDVNProps = {

    listDonvi: [] as DonVi[],

    listLop: [] as Lop[],

    ...readRootDataProps<ViewPheDuyetDiInHDXBNXBGDVNProps>(ROOT_ID),

};

mountReactComponentOnReady(ROOT_ID, <ViewPheDuyetDiInHDXBNXBGDVN {...bladeProps} />);

