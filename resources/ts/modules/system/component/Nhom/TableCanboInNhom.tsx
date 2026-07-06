import React from "react";
import { Table } from "antd";
import type { TableProps } from "antd";
import type { User } from "../../../user/type/User";
import { useManageCanboInNhomStore } from "../../store/Nhom/manageCanboInNhomStore";
import { formatDateToString } from "../../../core/utils/helpersDayjs";
import { UserApi } from "../../../user/api/UserApi";
import { ComponentIcon } from "../../../page/component/componentIcon";

interface RowCanboInNhomProps {
    canbo: User;
    index: number;
}

interface TableCanboInNhomProps {

}

export const TableCanboInNhom = React.memo((props: TableCanboInNhomProps) => {
    const { } = props;
    const listCanbo = useManageCanboInNhomStore((state) => state.listCanbo);
    const nhom = useManageCanboInNhomStore((state) => state.nhom);

    const handleDeleteCanbo = (canbo: User) => {
        const isConfirmed = window.confirm(`Bạn có chắc chắn muốn xóa ${canbo.HoTen} khỏi nhóm ${nhom?.TenNhomNSD} không?`);
        if (!isConfirmed) return;
        UserApi.deleteCanboInNhom(nhom?.id ?? 0, canbo.id).then((res: boolean) => {
            if (res) {
                window._toastbox("Xóa thành công", "success");
            }
        });
    };

    const columns: TableProps<User>["columns"] = [
        { title: "STT", key: "index", width: 80, render: (_value, _record, index) => index + 1 },
        { title: "Tên đăng nhập", dataIndex: "UserName", key: "UserName" },
        { title: "Họ tên", dataIndex: "HoTen", key: "HoTen" },
        { title: "Email", dataIndex: "Email", key: "Email" },
        { title: "Ngày sinh", dataIndex: "NgaySinh", key: "NgaySinh", render: (value) => formatDateToString(value) },
        { title: "Chức vụ", dataIndex: "ChucVuText", key: "ChucVuText" },
        { title: "Phòng ban", key: "phongban", render: () => "canbo.ID_PhongBan" },
        { title: "Đơn vị", key: "donvi", render: (_value, record) => record.donvi?.TenDonVi ?? "" },
        {
            title: "",
            key: "action",
            width: 60,
            render: (_value, record) => (
                <ComponentIcon nameIcon="close-md" width={20} height={20} onClick={() => handleDeleteCanbo(record)} />
            ),
        },
    ];

    return <Table<User> rowKey={(record, index) => String(record.id ?? index)} columns={columns} dataSource={listCanbo} pagination={false} size="small" />;
});
