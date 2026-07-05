import React, { useCallback, useEffect, useMemo } from "react";
import { Dropdown, Table, Tag, type MenuProps, type TableProps } from "antd";
import type { HDXBNXBGDVN } from "../../type";
import { useManageHDXBNXBGDVNStore, type HDXBNXBGDVNModalKey } from "../../store/HDXBNXBGDVN/manageHDXBNXBGDVN";
import { useDataViewStore } from "../../../system/store/useDataViewStore";
import { getHDXBNXBGDVNTrangThaiLabel } from "../../constants/hdxbNxbgdvn";

function TableHDXBNXBGDVNComponent() {
    const listHDXBNXBGD = useManageHDXBNXBGDVNStore((state) => state.listHDXBNXBGD);
    const selectedRowKeys = useManageHDXBNXBGDVNStore((state) => state.selectedRowKeys);
    const setSelectedRowKeys = useManageHDXBNXBGDVNStore((state) => state.setSelectedRowKeys);
    const openModalForItem = useManageHDXBNXBGDVNStore((state) => state.openModalForItem);
    const mapTrangThai = useDataViewStore((state) => state.mapTrangThai);

    useEffect(() => {
        setSelectedRowKeys([]);
    }, [listHDXBNXBGD, setSelectedRowKeys]);

    const rowSelection = useMemo(
        () => ({
            selectedRowKeys,
            onChange: (keys: React.Key[]) => setSelectedRowKeys(keys.map(String)),
        }),
        [selectedRowKeys, setSelectedRowKeys],
    );

    const openRowModal = useCallback(
        (modal: HDXBNXBGDVNModalKey, record: HDXBNXBGDVN) => {
            openModalForItem(modal, record);
        },
        [openModalForItem],
    );

    const columns: TableProps<HDXBNXBGDVN>["columns"] = useMemo(
        () => [
            { title: "STT", key: "stt", width: 56, render: (_v, _r, i) => i + 1 },
            { title: "Tên đề tài", dataIndex: "TenDeTai", key: "TenDeTai", ellipsis: true },
            {
                title: "Năm TB/XB",
                key: "NamXuatBan",
                width: 100,
                render: (_, record) => record.NamXuatBan || record.NamTaiBan || "-",
            },
            {
                title: "Người đọc duyệt",
                key: "NguoiDocDuyet",
                width: 160,
                ellipsis: true,
                render: (_, record) => record.NguoiDocDuyet || record.BienTapVien || "-",
            },
            {
                title: "Đơn vị tổ chức bản thảo",
                dataIndex: "TenDonVi",
                key: "TenDonVi",
                ellipsis: true,
                render: (value) => value || "-",
            },
            {
                title: "Phân công",
                key: "PhanCong",
                width: 130,
                render: (_, record) => (
                    <Tag color={record.DaPhanCong ? "success" : "default"} className="m-0">
                        {record.PhanCong ?? (record.DaPhanCong ? "Đã phân công" : "Chưa phân công")}
                    </Tag>
                ),
            },
            {
                title: "Trạng thái",
                key: "TrangThai",
                width: 150,
                render: (_, record) =>
                    getHDXBNXBGDVNTrangThaiLabel(record.TrangThai, record.TenTrangThai, mapTrangThai) || "-",
            },
            {
                title: "",
                key: "action",
                width: 120,
                fixed: "right",
                render: (_, record) => {
                    const items: MenuProps["items"] = [
                        {
                            key: "phanCongDocDuyet",
                            label: "Phân công đọc duyệt",
                            onClick: () => openRowModal("phanCongDocDuyet", record),
                        },
                        {
                            key: "docDuyet",
                            label: "Đọc duyệt",
                            onClick: () => openRowModal("docDuyet", record),
                        },
                        {
                            key: "inPhieuTrinh",
                            label: "In phiếu trình HĐXB NXBGDVN",
                            onClick: () => openRowModal("inPhieuTrinh", record),
                        },
                        {
                            key: "xetDuyetDeTai",
                            label: "Xét duyệt đề tài",
                            onClick: () => openRowModal("xetDuyetDeTai", record),
                        },
                        {
                            key: "pheDuyetDiIn",
                            label: "Phê duyệt đi in",
                            onClick: () => openRowModal("pheDuyetDiIn", record),
                        },
                        { type: "divider" },
                        {
                            key: "detail",
                            label: <a href={`/phieu-dk-detai/cap-nhat/${record.id}`}>Xem chi tiết</a>,
                        },
                    ];
                    return (
                        <Dropdown menu={{ items }} trigger={["click"]}>
                            <a href="#" onClick={(e) => e.preventDefault()}>
                                Chức năng
                            </a>
                        </Dropdown>
                    );
                },
            },
        ],
        [mapTrangThai, openRowModal],
    );

    return (
        <Table<HDXBNXBGDVN>
            rowKey={(record) => String(record.id)}
            rowSelection={rowSelection}
            columns={columns}
            dataSource={listHDXBNXBGD}
            pagination={false}
            size="small"
            className="text-sm"
            scroll={{ x: 1100 }}
        />
    );
}

export default React.memo(TableHDXBNXBGDVNComponent);
