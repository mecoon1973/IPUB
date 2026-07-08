import { Dropdown, Table, type MenuProps, type TableProps } from "antd";
import type { QDIn } from "../../type/QDIn";
import React from "react";
import { formatDateToString } from "../../../core/utils/helpersDayjs";

interface TableQDInProps {
    listQDIn: QDIn[];
}

export const TableQDIn = React.memo(({ listQDIn }: TableQDInProps) => {
    const columns: TableProps<QDIn>["columns"] = [
        { title: "STT", key: "stt", width: 56, render: (_v, _r, i) => i + 1 },
        { title: "Ngày QĐ", dataIndex: "NgayQD", key: "NgayQD", render: (value) => formatDateToString(value) },
        { title: "Số QĐ", dataIndex: "SoQD", key: "SoQD" },
        { title: "Đơn vị QĐ", dataIndex: "TenDonViQD", key: "TenDonViQD" },
        { title: "Người kí", dataIndex: "TenNguoiKi", key: "TenNguoiKi" },
        { title: "Năm KH", dataIndex: "NamKeHoach", key: "NamKeHoach" },
        { title: "Trạng thái QĐXB", dataIndex: "TrangThai", key: "TrangThai" },
        {
            title: "",
            key: "action",
            width: 120,
            render: (_, qdIn, index) => {
                const items: MenuProps["items"] = [
                    { key: "print", label: "In", onClick: () => {}},
                    { key: "update", label: "Sửa", onClick: () => {window.location.href = `/qd-in/cap-nhat/${qdIn.id}`}},
                    { key: "delete", label: "Xóa", onClick: () => {} },
                ];
                return (
                    <Dropdown menu={{ items }} trigger={["click"]}>
                        <a href="#" onClick={(e) => e.preventDefault()}>
                            Chức năng
                        </a>
                    </Dropdown>
                );
            },
        }
    ];
    return <Table<QDIn>
                rowKey={(record, index) => String(record.id || index)}
                columns={columns}
                dataSource={listQDIn}
                pagination={false}
                size="small"
            />;
});
