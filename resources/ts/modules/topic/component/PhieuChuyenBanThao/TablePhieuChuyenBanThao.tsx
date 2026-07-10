import React, { useCallback } from "react";
import { Dropdown, Table, type MenuProps, type TableProps } from "antd";
import { formatDateToString } from "../../../core/utils/helpersDayjs";
import { useManagePhieuChuyenBanThaoStore } from "../../store/PhieuChuyenBanThao/managePhieuChuyenBanThaoStore";
import type { PhieuChuyenBanThao } from "../../type";

interface TablePhieuChuyenBanThaoProps {
    onDelete?: (record: PhieuChuyenBanThao) => void;
}

export const TablePhieuChuyenBanThaoComponent = React.memo(({ onDelete }: TablePhieuChuyenBanThaoProps) => {
    const listPhieuChuyenBanThao = useManagePhieuChuyenBanThaoStore((state) => state.listPhieuChuyenBanThao);

    const columns: TableProps<PhieuChuyenBanThao>["columns"] = [
        { title: "STT", key: "stt", width: 56, render: (_v, _r, i) => i + 1 },
        {
            title: "Mã số",
            key: "MaSo",
            width: 140,
            render: (_, record) => record.sach?.MaSo ?? "",
        },
        {
            title: "Tên sách",
            key: "TenSach",
            render: (_, record) => record.sach?.TenSach ?? "",
        },
        {
            title: "Người giao",
            dataIndex: "NguoiGiao",
            key: "NguoiGiao",
            width: 140,
        },
        {
            title: "Người nhận",
            dataIndex: "NguoiNhan",
            key: "NguoiNhan",
            width: 140,
        },
        {
            title: "Ngày giao",
            dataIndex: "NgayGiao",
            key: "NgayGiao",
            width: 110,
            render: (value) => formatDateToString(value),
        },
        {
            title: "Đơn vị",
            key: "DonVi",
            width: 260,
            render: (_, record) => record.donvi?.TenDonVi ?? "",
        },
        {
            title: "",
            key: "action",
            width: 120,
            render: (_, record) => {
                const items: MenuProps["items"] = [
                    {
                        key: "in",
                        label: "In",
                    },
                    {
                        key: "edit",
                        label: <a href={`/phieu-chuyen-ban-thao/cap-nhat/${record.id}`}>Chỉnh sửa</a>,
                    },
                    {
                        key: "delete",
                        label: <span className="text-danger">Xóa</span>,
                        onClick: () => onDelete?.(record),
                    },
                    {
                        key: "view",
                        label: "CN vào DM sách",
                    }
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
        <Table<PhieuChuyenBanThao>
            rowKey={(record, index) => String(record.id ?? index)}
            columns={columns}
            dataSource={listPhieuChuyenBanThao}
            pagination={false}
            size="small"
            className="text-sm"
            scroll={{ x: 1100 }}
        />
    );
});
