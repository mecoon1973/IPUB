import React, { useEffect, useMemo, useState } from "react";
import { Dropdown, Table, type MenuProps, type TableProps } from "antd";
import type { HDXBNXBGDVN } from "../../type";
import { useManageHDXBNXBGDVNStore } from "../../store/HDXBNXBGDVN/manageHDXBNXBGDVN";
import { useDataViewStore } from "../../../system/store/useDataViewStore";

function TableHDXBNXBGDVNComponent() {
    const listHDXBNXBGD = useManageHDXBNXBGDVNStore((state) => state.listHDXBNXBGD);
    const mapTrangThai = useDataViewStore((state) => state.mapTrangThai);
    const [selectedRowKeys, setSelectedRowKeys] = useState<React.Key[]>([]);

    useEffect(() => {
        setSelectedRowKeys([]);
    }, [listHDXBNXBGD]);

    const rowSelection = useMemo(
        () => ({
            selectedRowKeys,
            onChange: (keys: React.Key[]) => setSelectedRowKeys(keys),
        }),
        [selectedRowKeys],
    );

    const columns: TableProps<HDXBNXBGDVN>["columns"] = [
        { title: "STT", key: "stt", width: 56, render: (_v, _r, i) => i + 1 },
        { title: "Tên đề tài", dataIndex: "TenDeTai", key: "TenDeTai" },
        { title: "Năm TB/XB", dataIndex: "NamXuatBan", key: "NamXuatBan" },
        { title: "Người đọc duyệt", dataIndex: "NguoiDocDuyet", key: "NguoiDocDuyet" },
        { title: "Đơn vị tổ chức bản thảo", dataIndex: "TenDonVi", key: "TenDonVi" },
        {
            title: "Phân công",
            dataIndex: "PhanCong",
            key: "PhanCong",
            // render: (_, record) => record?.PhanCong ?? "-",
        },
        {
            title: "",
            key: "action",
            width: 120,
            render: (_, record) => {
                const items: MenuProps["items"] = [
                    { key: "detail", label: "Xem chi tiết", onClick: () => {} },
                    { key: "edit", label: "Chỉnh sửa", onClick: () => {} },
                    { key: "delete", label: <span className="text-danger">Xóa</span>, onClick: () => {} },
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
    ];

    return (
        <Table<HDXBNXBGDVN>
            rowKey={(record, index) => String(record.id ?? index)}
            rowSelection={rowSelection}
            columns={columns}
            dataSource={listHDXBNXBGD}
            pagination={false}
            size="small"
        />
    );
}

export default React.memo(TableHDXBNXBGDVNComponent);
