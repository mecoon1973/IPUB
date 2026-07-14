import React, { useMemo } from "react";
import { Dropdown, Table, type MenuProps, type TableProps } from "antd";
import { formatDateToString } from "../../../core/utils/helpersDayjs";
import { useManagePhieuDkKhxbCxbStore } from "../../store/PhieuDkKhxbCxb/managePhieuDkKhxbCxbStore";
import type { PhieuDkKhxbCxb } from "../../type";
import type { User } from "../../../user/type";

interface TablePhieuDkKhxbCxbProps {
    listUsers: User[];
}

export const TablePhieuDkKhxbCxbComponent = React.memo(({ listUsers }: TablePhieuDkKhxbCxbProps) => {
    const listPhieuDkKhxbCxb = useManagePhieuDkKhxbCxbStore((state) => state.listPhieuDkKhxbCxb);
    const openModalCapMaCxb = useManagePhieuDkKhxbCxbStore((state) => state.openModalCapMaCxb);
    const openModalKetChuyen = useManagePhieuDkKhxbCxbStore((state) => state.openModalKetChuyen);
    const openModalXetDuyet = useManagePhieuDkKhxbCxbStore((state) => state.openModalXetDuyet);

    const mapUserName = useMemo(() => {
        const map = new Map<number, string>();
        listUsers.forEach((user) => {
            map.set(user.id, user.HoTen || user.UserName || String(user.id));
        });
        return map;
    }, [listUsers]);

    const columns: TableProps<PhieuDkKhxbCxb>["columns"] = [
        { title: "STT", key: "stt", width: 56, render: (_v, _r, i) => i + 1 },
        {
            title: "Mã phiếu",
            dataIndex: "MaSo",
            key: "MaSo",
            render: (value, record) => (
                <span className={!record.DaGui ? "text-danger" : undefined}>{value}</span>
            ),
        },
        {
            title: "Tiêu đề",
            dataIndex: "TieuDe",
            key: "TieuDe",
            render: (value, record) => (
                <span className={!record.DaGui ? "text-danger" : undefined}>{value}</span>
            ),
        },
        { title: "Nội dung", dataIndex: "NoiDung", key: "NoiDung" },
        {
            title: "Ngày ĐK",
            dataIndex: "NgayDK",
            key: "NgayDK",
            render: (value) => formatDateToString(value),
        },
        { title: "Số giấy phép", dataIndex: "SoGiayPhep", key: "SoGiayPhep" },
        {
            title: "Ngày cấp phép",
            dataIndex: "NgayCapPhep",
            key: "NgayCapPhep",
            render: (value) => formatDateToString(value),
        },
        {
            title: "Người tạo",
            key: "CreatedBy",
            render: (_, record) => mapUserName.get(record.CreatedBy) ?? String(record.CreatedBy || ""),
        },
        {
            title: "Ngày tạo",
            dataIndex: "CreatedOn",
            key: "CreatedOn",
            render: (value) => formatDateToString(value, "DD/MM/YYYY | HH:mm"),
        },
        {
            title: "",
            key: "action",
            width: 120,
            render: (_, record) => {
                const items: MenuProps["items"] = [
                    { key: "in", label: "In", onClick: () => {} },
                    { key: "inphieu", label: "In phiếu gửi đơn vị", onClick: () => {} },
                    { key: "masocxb", label: "Cấp mã số CXB", onClick: () => openModalCapMaCxb(record) },
                    { key: "maISBN", label: <a href={`/phieu-dk-khxb-cxb/cap-ma-isbn/${record.id}`}>Cấp mã ISBN</a> },
                    { key: "edit", label: <a href={`/phieu-dk-khxb-cxb/cap-nhat/${record.id}`}>Chỉnh sửa</a> },
                    { key: "print", label: "In phiếu", onClick: () => {} },
                    { key: "xetduyet", label: "Xét duyệt", onClick: () => openModalXetDuyet(record) },
                    { key: "ketchuuyendetai", label: "Kết chuyển đề tài", onClick: () => openModalKetChuyen(record) },
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
        <Table<PhieuDkKhxbCxb>
            rowKey={(record, index) => String(record.id ?? record.MaSo ?? index)}
            columns={columns}
            dataSource={listPhieuDkKhxbCxb}
            pagination={false}
            size="small"
            className="text-sm"
        />
    );
});
