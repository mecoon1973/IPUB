import React, { useCallback, useEffect } from "react";
import { Checkbox, Table } from "antd";
import type { TableProps } from "antd";
import type { User } from "../../type/User";

interface TableSelectUserProps {
    listUser: User[];
    selectUser: User[];
    setSelectUser: React.Dispatch<React.SetStateAction<User[]>>;
}

export const TableSelectUser = React.memo((props: TableSelectUserProps) => {
    const { listUser, selectUser, setSelectUser } = props;

    const handleSelectUser = useCallback(
        (user: User) => {
            setSelectUser((prev: User[]) => {
                if (prev.some((u) => u.id === user.id)) {
                    return prev.filter((u) => u.id !== user.id);
                }
                return [...prev, user];
            });
        },
        [setSelectUser],
    );

    const handleSelectAll = useCallback(() => {
        setSelectUser(listUser);
    }, [listUser, setSelectUser]);

    useEffect(() => {
        setSelectUser([]);
    }, [listUser, setSelectUser]);

    const allSelected = listUser.length > 0 && selectUser.length === listUser.length;

    const columns: TableProps<User>["columns"] = [
        {
            title: (
                <Checkbox
                    id="select-all-user"
                    checked={allSelected}
                    onChange={() => {
                        if (allSelected) setSelectUser([]);
                        else handleSelectAll();
                    }}
                />
            ),
            key: "pick",
            width: 48,
            render: (_v, user, index) => (
                <Checkbox
                    id={`select-user-${user.id ?? "no-id"}-${index}`}
                    checked={selectUser.some((u) => u.id === user.id)}
                    onChange={() => handleSelectUser(user)}
                />
            ),
        },
        { title: "STT", key: "stt", width: 48, render: (_v, _r, i) => i + 1 },
        { title: "Tên đăng nhập", dataIndex: "UserName", key: "UserName" },
        { title: "Họ tên", dataIndex: "HoTen", key: "HoTen" },
        { title: "Phòng ban", key: "pb", render: () => "user.ID_PhongBan" },
        { title: "Đơn vị", key: "dv", render: (_, u) => u.donvi?.TenDonVi },
    ];

    return <Table<User> rowKey={(u, i) => String(u.id ?? i)} columns={columns} dataSource={listUser} pagination={false} size="small" className="text-sm" />;
});
