import React, { useMemo } from "react";
import { Button, Table, type TableProps } from "antd";
import { DeleteOutlined } from "@ant-design/icons";
import type { PhieuDkDetai } from "../../type";
import type { User } from "../../../user/type";

interface TableDeTaiPhieuDkKhxbCxbProps {
    listDeTai: PhieuDkDetai[];
    mapTrangThai: Record<number, string>;
    listUsers: User[];
    onRemove?: (id: number) => void;
}

export const TableDeTaiPhieuDkKhxbCxb = React.memo(({
    listDeTai,
    mapTrangThai,
    listUsers,
    onRemove,
}: TableDeTaiPhieuDkKhxbCxbProps) => {
    const mapUserName = useMemo(() => {
        const map = new Map<number, string>();
        listUsers.forEach((user) => {
            map.set(user.id, user.HoTen || user.UserName || String(user.id));
        });
        return map;
    }, [listUsers]);

    const columns: TableProps<PhieuDkDetai>["columns"] = [
        { title: "STT", key: "stt", width: 56, render: (_v, _r, i) => i + 1 },
        { title: "Mã số", dataIndex: "MaSo", key: "MaSo", width: 100 },
        { title: "Tên đề tài", dataIndex: "TenDeTai", key: "TenDeTai" },
        { title: "Tác giả", dataIndex: "TacGia", key: "TacGia", width: 120 },
        {
            title: "Năm TB/XB",
            key: "NamTBXB",
            width: 100,
            render: (_, record) => record.NamXuatBan || record.NamTaiBan || "",
        },
        {
            title: "Loại",
            key: "Loai",
            width: 90,
            render: (_, record) => (record.HTXB ? "Xuất bản" : "Tái bản"),
        },
        {
            title: "Trạng thái",
            key: "TrangThai",
            width: 140,
            render: (_, record) => mapTrangThai[record.TrangThai] ?? String(record.TrangThai ?? ""),
        },
        {
            title: "Tên người tạo",
            key: "CreatedBy",
            width: 140,
            render: (_, record) => mapUserName.get(record.CreatedBy) ?? String(record.CreatedBy || ""),
        },
        {
            title: "",
            key: "action",
            width: 56,
            align: "center",
            render: (_, record) => (
                <Button
                    type="text"
                    danger
                    size="small"
                    icon={<DeleteOutlined />}
                    title="Xóa"
                    onClick={() => onRemove?.(record.id)}
                />
            ),
        },
    ];

    return (
        <Table<PhieuDkDetai>
            rowKey={(record, index) => String(record.id ?? index)}
            columns={columns}
            dataSource={listDeTai}
            pagination={false}
            size="small"
            className="text-sm"
            scroll={{ x: 900 }}
        />
    );
});
