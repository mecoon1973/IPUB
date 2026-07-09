import React, { useCallback, useEffect, useMemo } from "react";
import { Dropdown, Table, Tag, type MenuProps, type TableProps } from "antd";
import { usePheDuyetDiInStore } from "../../store/HDXBNXBGDVN/pheDuyetDiInStore";
import type { PheDuyetDiInRow } from "../../type";
import { getTrangThaiDocBanThaoLabel, TRANG_THAI_DOC_BAN_THAO } from "../../constants/hdxbNxbgdvn";

interface TablePheDuyetDiInProps {
    onApproveRow?: (row: PheDuyetDiInRow) => void;
}

function TablePheDuyetDiInComponent({ onApproveRow }: TablePheDuyetDiInProps) {
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
            getCheckboxProps: (record: PheDuyetDiInRow) => ({
                disabled: record.DaPheDuyetDiIn,
            }),
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
                width: 180,
                render: (_, record) => {
                    const trangThai = record.TrangThaiDocBanThao ?? TRANG_THAI_DOC_BAN_THAO.CHUA_DOC_DUYET;
                    const tagColor = trangThai === TRANG_THAI_DOC_BAN_THAO.DA_DOC_DUYET
                        ? "success"
                        : trangThai === TRANG_THAI_DOC_BAN_THAO.DANG_DOC_DUYET
                            ? "processing"
                            : "default";
                    return (
                        <Tag color={tagColor} className="m-0">
                            {getTrangThaiDocBanThaoLabel(trangThai, record.TenTrangThai)}
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
                            key: "pheDuyetDiIn",
                            label: "Phê duyệt đi in",
                            disabled: record.DaPheDuyetDiIn,
                            onClick: () => handleApproveRow(record),
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
        [handleApproveRow],
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
