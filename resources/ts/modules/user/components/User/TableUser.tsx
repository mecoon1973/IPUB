import React from "react";
import { Dropdown, Table } from "antd";
import type { MenuProps, TableProps } from "antd";
import type { User } from "../../type";
import { formatDateToString } from "../../../core/utils/helpersDayjs";
import { UserApi } from "../../api/UserApi";

interface TableUserProps {
    listUser: User[];
    handleDeleteUser: (id: number) => void;
}

export const TableUser = React.memo((props: TableUserProps) => {
    const { listUser, handleDeleteUser } = props;

    const columns: TableProps<User>["columns"] = [
        { title: "STT", key: "stt", width: 52, render: (_v, _r, index) => index + 1 },
        { title: "Username", dataIndex: "UserName", key: "UserName" },
        { title: "Họ tên", dataIndex: "HoTen", key: "HoTen" },
        { title: "Email", dataIndex: "Email", key: "Email" },
        {
            title: "Ngày sinh",
            key: "NgaySinh",
            render: (_, u) => formatDateToString(u.NgaySinh),
        },
        { title: "Chức vụ", dataIndex: "ChucVuText", key: "ChucVuText" },
        { title: "Phòng ban", key: "phongban", render: () => "user.ID_PhongBan" },
        { title: "Đơn vị", dataIndex: "ID_DonVi", key: "ID_DonVi" },
        {
            title: "Là BTV",
            key: "isSpecial",
            render: (_, u) => (u.isSpecial ? "Có" : "Không"),
        },
        {
            title: "Đồng bộ TK ký số",
            key: "KyQDXB",
            render: (_, u) => (u.KyQDXB ? "Có" : "Không"),
        },
        {
            title: "Chức năng",
            key: "action",
            width: 110,
            render: (_v, user, index) => {
                const items: MenuProps["items"] = [
                    { key: "edit", label: <a href={`/tai-khoan/cap-nhat/${user.id}`}>Sửa</a> },
                    { key: "account", label: <a href={`/tai-khoan/${user.id}/cap-tai-khoan`}>Cấp tài khoản</a> },
                    { key: "perm", label: <a href={`/tai-khoan/${user.id}/phan-quyen`}>Phân quyền</a> },
                    {
                        key: "reset",
                        label: "Reset mật khẩu",
                        onClick: () =>
                            UserApi.resetPassword(user.id).then((res: boolean) => {
                                if (res) window._toastbox("Mật khẩu đã được reset", "success");
                            }),
                    },
                    { key: "sync", label: <a href={`/tai-khoan/${user.id}/dong-bo-ky-so`}>Đồng bộ TK ký số</a> },
                    {
                        key: "delete",
                        label: <span className="text-danger">Xóa</span>,
                        onClick: () => handleDeleteUser(user.id),
                    },
                ];
                return (
                    <Dropdown menu={{ items }} trigger={["click"]}>
                        <a href="#" onClick={(e) => e.preventDefault()} id={`action-user-${user.id ?? index}`}>
                            Hành động
                        </a>
                    </Dropdown>
                );
            },
        },
    ];

    return <Table<User> rowKey={(u, i) => String(u.id ?? i)} columns={columns} dataSource={listUser} pagination={false} size="small" className="text-sm" />;
});
