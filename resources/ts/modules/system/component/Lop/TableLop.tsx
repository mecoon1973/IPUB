import React from "react";
import { Dropdown, Table } from "antd";
import type { MenuProps, TableProps } from "antd";
import type Lop from "../../type/Lop";

interface TableLopProps {
    listLop: Lop[];
    handleDeleteLop: (id: number) => void;
}

export const TableLop = React.memo((props: TableLopProps) => {
    const { listLop, handleDeleteLop } = props;
    const columns: TableProps<Lop>["columns"] = [
        {
            title: "STT",
            key: "index",
            width: 80,
            render: (_value, _record, index) => index + 1,
        },
        { title: "Mã cấp, lớp", dataIndex: "MaLop", key: "MaLop" },
        { title: "Tên cấp, lớp", dataIndex: "TenLop", key: "TenLop" },
        { title: "Kí hiệu", dataIndex: "KiHieu", key: "KiHieu" },
        {
            title: "",
            key: "action",
            width: 140,
            render: (_value, record, index) => {
                const items: MenuProps["items"] = [
                    {
                        key: "edit",
                        label: <a href={`/he-thong/lop/cap-nhat/${record.id}`}>Chỉnh sửa</a>,
                    },
                    {
                        key: "delete",
                        label: <span className="text-danger">Xóa</span>,
                        onClick: () => handleDeleteLop(record.id),
                    },
                ];
                return (
                    <Dropdown menu={{ items }} trigger={["click"]}>
                        <a onClick={(e) => e.preventDefault()} href="#">
                            Chức năng
                        </a>
                    </Dropdown>
                );
            },
        },
    ];

    return <Table<Lop> rowKey={(record, index) => String(record.id ?? index)} columns={columns} dataSource={listLop} pagination={false} size="small" />;
});
