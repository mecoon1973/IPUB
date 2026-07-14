import React, { useCallback, useEffect, useMemo } from "react";
import { Dropdown, Table, Tag, type MenuProps, type TableProps } from "antd";
import { usePheDuyetDiInStore } from "../../store/HDXBNXBGDVN/pheDuyetDiInStore";
import type { PheDuyetDiInRow } from "../../type";
import { getPheDuyetDiInTrangThaiLabel } from "../../constants/hdxbNxbgdvn";

interface TablePheDuyetDiInProps {
    onApproveRow?: (row: PheDuyetDiInRow) => void;
    onViewDetail?: (row: PheDuyetDiInRow) => void;
}

function TablePheDuyetDiInComponent({ onApproveRow, onViewDetail }: TablePheDuyetDiInProps) {
    const listRows = usePheDuyetDiInStore((state) => state.listRows);
    const selectedRowKeys = usePheDuyetDiInStore((state) => state.selectedRowKeys);
    const setSelectedRowKeys = usePheDuyetDiInStore((state) => state.setSelectedRowKeys);

    useEffect(() => {
        setSelectedRowKeys([]);
    }, [listRows, setSelectedRowKeys]);

    const rowSelection = useMemo(
        () => ({
            selectedRowKeys,
            onChange: (keys: React.Key[]) => setSelectedRowKeys(keys.map(String)),
            // getCheckboxProps: (record: PheDuyetDiInRow) => ({
            //     disabled: record.DaPheDuyetDiIn,
            // }),
        }),
        [selectedRowKeys, setSelectedRowKeys],
    );

    const handleApproveRow = useCallback(
        (record: PheDuyetDiInRow) => {
            if (record.DaPheDuyetDiIn) {
                window._toastbox("Sách này đã được phê duyệt đi in", "info");
                return;
            }
            onApproveRow?.(record);
        },
        [onApproveRow],
    );

    const columns: TableProps<PheDuyetDiInRow>["columns"] = useMemo(
        () => [
            { title: "STT", key: "stt", width: 56, render: (_v, _r, i) => i + 1 },
            { title: "Mã số", dataIndex: "MaSo", key: "MaSo", width: 140 },
            { title: "Tên sách", dataIndex: "TenSach", key: "TenSach", ellipsis: true },
            {
                title: "Năm TBXB",
                key: "NamTaiBan",
                width: 90,
                render: (_, record) => record.NamTaiBan || record.NamXuatBan || "-",
            },
            {
                title: "Đơn vị tổ chức bản thảo",
                dataIndex: "TenDonVi",
                key: "TenDonVi",
                ellipsis: true,
                render: (value) => value || "-",
            },
            {
                title: "Trạng thái",
                key: "TrangThai",
                width: 200,
                render: (_, record) => {
                    const daPheDuyetDiIn = record.XetDuyetBanThao ?? record.DaPheDuyetDiIn ?? false;
                    return (
                        <Tag color={daPheDuyetDiIn ? "success" : "default"} className="m-0">
                            {getPheDuyetDiInTrangThaiLabel(daPheDuyetDiIn)}
                        </Tag>
                    );
                },
            },
            {
                title: "",
                key: "action",
                width: 120,
                fixed: "right",
                render: (_, record) => {
                    const items: MenuProps["items"] = [
                        {
                            key: "chitiet",
                            label: "Xem chi tiết",
                            onClick: () => onViewDetail?.(record),
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
        [onViewDetail],
    );

    return (
        <Table<PheDuyetDiInRow>
            rowKey={(record) => String(record.id)}
            rowSelection={rowSelection}
            columns={columns}
            dataSource={listRows}
            pagination={false}
            size="small"
            className="text-sm"
            scroll={{ x: 1000 }}
        />
    );
}

export default React.memo(TablePheDuyetDiInComponent);
