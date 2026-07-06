import React, { forwardRef, useCallback, useEffect, useImperativeHandle, useMemo, useRef, useState } from "react";
import { mountReactComponentOnReady, readRootDataProps } from "../../../core/utils/helpers";
import { Button, Input, Modal, Select, Table } from "antd";
import type { DonVi } from "../../../user/type/DonVi";
import type { Mangsach } from "../../../system/type/MangSach";
import { type PhieuDkDetai } from "../../type/PhieuDkDetai";
import type { TableProps } from "antd";
import { ComponentTitleStore } from "../../../page/component/componentTitleStore";
import { PlusOutlined } from '@ant-design/icons';
import { usePhieuDkDetaiStore } from "../../store/PhieuDkDetai/phieuDkDetaiStore";
import { ModalChooseDeTaiComponent } from "../../component/PhieuDkDetai/ModalChooseDeTaiComponent";
import { useDataViewStore } from "../../../system/store/useDataViewStore";


interface TableTaiBanPhieuDkDetaiProps {
}

const TableTaiBanPhieuDkDetaiComponent = React.memo((props: TableTaiBanPhieuDkDetaiProps) => {
    const listDetaiTaiBan = usePhieuDkDetaiStore((state) => state.listDetaiTaiBan);
    const columns: TableProps<PhieuDkDetai>["columns"] = useMemo(
        () => [
            { title: "STT", key: "stt", width: 60, render: (_v, _r, i) => i + 1 },
            { title: "Mã số", dataIndex: "MaSo", key: "MaSo", width: 100 },
            { title: "Tên đề tài", dataIndex: "TenDetai", key: "TenDetai" },
            { title: "Tác giả", dataIndex: "TenTacGia", key: "TenTacGia", width: 130 },
            { title: "Năm TB", dataIndex: "NamTaiBan", key: "NamTaiBan", width: 110 },
            { title: "Năm XB", dataIndex: "NamXuatBan", key: "NamXuatBan", width: 120 },
            { title: "Kiểu bản quyền", dataIndex: "KieuBanQuyen", key: "KieuBanQuyen", width: 140 },
            { title: "Bản quyền từ ngày", dataIndex: "BanQuyen", key: "BanQuyen", width: 130 },
            { title: "Bản quyền đến ngày", dataIndex: "BanQuyen", key: "BanQuyen", width: 130 },
            { title: "Đơn vị sở hữu bản quyền", dataIndex: "TenDonVi", key: "TenDonVi", width: 220 },
        ],
        [],
    );

    return (
        <Table<PhieuDkDetai>
            rowKey="id"
            columns={columns}
            dataSource={listDetaiTaiBan}
        />
    );
});

interface ViewTaiBanPhieuDkDetaiProps {
    listDonvi: DonVi[];
    listMangsach: Mangsach[];
}

export const ViewTaiBanPhieuDkDetai = React.memo((props: ViewTaiBanPhieuDkDetaiProps) => {
    const { listDonvi, listMangsach } = props;
    const yearTaiBan = usePhieuDkDetaiStore((state) => state.yearTaiBan);
    const idDonvi = usePhieuDkDetaiStore((state) => state.idDonvi);
    const setYearTaiBan = usePhieuDkDetaiStore((state) => state.setYearTaiBan);
    const setIdDonvi = usePhieuDkDetaiStore((state) => state.setIdDonvi);
    const setShowModalChonDeTai = usePhieuDkDetaiStore((state) => state.setShowModalChonDeTai);
    const setDataView = useDataViewStore((state) => state.setData);

    useEffect(() => {
        setDataView({ listDonvi, listMangsach });
    }, [])

    return (
        <div className="px-2">
            <ComponentTitleStore title="Tái bản đề tài" callbackSubmit={() => {}} disabledSubmit={false} />

            <div className="mb-2" >
                <div className="mb-2">
                    <Button
                        type="default"
                        icon={<PlusOutlined />}
                        onClick={() => setShowModalChonDeTai(true)}
                    >
                        Chọn đề tài
                    </Button>
                </div>
                <div className="d-flex align-items-end justify-content-end gap-4">
                    <div style={{ minWidth: 220 }}>
                        <div className="small mb-1">Chọn năm cần tái bản</div>
                        <Input
                            value={yearTaiBan}
                            placeholder="2027"
                            onChange={(e) => setYearTaiBan(e.target.value)}
                        />
                    </div>
                </div>
            </div>

            <TableTaiBanPhieuDkDetaiComponent/>

            <ModalChooseDeTaiComponent />
        </div>
    );
});


const ROOT_ID = "root-chuyen-ke-hoach-phieu-dk-detai";
const bladeProps: ViewTaiBanPhieuDkDetaiProps = {
    listDonvi: [],
    listMangsach: [],
    ...readRootDataProps<ViewTaiBanPhieuDkDetaiProps>(ROOT_ID),
};
mountReactComponentOnReady(ROOT_ID, <ViewTaiBanPhieuDkDetai {...bladeProps} />);
