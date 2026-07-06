import React from "react";
import { Dropdown, Table } from "antd";
import type { MenuProps, TableProps } from "antd";
import type Nhom from "../../type/Nhom";

interface TableNhomProps {
    listNhom: Nhom[];
    handleDeleteNhom: (id: number) => void;
}

export const TableNhom = React.memo((props: TableNhomProps) => {
    const { listNhom, handleDeleteNhom } = props;
    const columns: TableProps<Nhom>["columns"] = [
        {
            title: "STT",
            key: "index",
            width: 80,
            render: (_value, _record, index) => index + 1,
        },
        { title: "Mã nhóm", dataIndex: "MaNhomNSD", key: "MaNhomNSD" },
        { title: "Tên nhóm", dataIndex: "TenNhomNSD", key: "TenNhomNSD" },
        {
            title: "Số thành viên trong nhóm",
            dataIndex: "countCanbo",
            key: "countCanbo",
            render: (value) => value ?? 0,
        },
        {
            title: "",
            key: "action",
            width: 140,
            render: (_value, record) => {
                const items: MenuProps["items"] = [
                    { key: "manage", label: <a href={`/he-thong/nhom/quan-ly-can-bo/${record.id}`}>Quản lý cán bộ</a> },
                    { key: "permission", label: <a href={`/he-thong/nhom/phan-quyen-nhom/${record.id}`}>Phân quyền</a> },
                    { key: "edit", label: <a href={`/he-thong/nhom/cap-nhat/${record.id}`}>Chỉnh sửa</a> },
                    {
                        key: "delete",
                        label: <span className="text-danger">Xóa</span>,
                        onClick: () => handleDeleteNhom(record.id),
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
    ];

    return <Table<Nhom> rowKey={(record, index) => String(record.id ?? index)} columns={columns} dataSource={listNhom} pagination={false} size="small" />;
});
