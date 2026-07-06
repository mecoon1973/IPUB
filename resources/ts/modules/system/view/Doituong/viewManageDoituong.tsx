import {
    mountReactComponentOnReady,
    readRootDataProps,
} from "../../../core/utils/helpers";
import React, { useCallback, useEffect, useMemo, useState } from "react";
import type { Doituong } from "../../type/DoiTuong";
import { defaultPagiInfo, type PagiInfo } from "../../../page/type";
import { DoituongApi } from "../../api/DoituongApi";
import { ComponentPagination } from "../../../page/component/pagination";
import { Button, Divider, Dropdown, Table } from "antd";
import type { MenuProps, TableProps } from "antd";

export const TableDoituong = React.memo((props: { listDoituong: Doituong[]; handleDeleteDoituong: (id: number) => void }) => {
    const { listDoituong, handleDeleteDoituong } = props;
    const columns: TableProps<Doituong>["columns"] = useMemo(
        () => [
            { title: "STT", key: "stt", width: 72, render: (_v, _r, i) => i + 1 },
            { title: "Mã đối tượng", dataIndex: "MaDoiTuong", key: "MaDoiTuong" },
            { title: "Tên đối tượng", dataIndex: "TenDoiTuong", key: "TenDoiTuong" },
            { title: "Mô tả", dataIndex: "MoTa", key: "MoTa" },
            { title: "Kiểu", dataIndex: "type", key: "type" },
            {
                title: "",
                key: "action",
                width: 132,
                render: (_value, record) => {
                    const items: MenuProps["items"] = [
                        {
                            key: "edit",
                            label: <a href={`/he-thong/doi-tuong/cap-nhat/${record.id}`}>Chỉnh sửa</a>,
                        },
                        {
                            key: "delete",
                            label: <span className="text-danger">Xóa</span>,
                            onClick: () => handleDeleteDoituong(record.id),
                        },
                    ];
                    return (
                        <Dropdown menu={{ items }} trigger={["click"]}>
                            <Button type="link" className="px-0">
                                Chức năng
                            </Button>
                        </Dropdown>
                    );
                },
            },
        ],
        [handleDeleteDoituong],
    );

    return <Table<Doituong> rowKey="id" columns={columns} dataSource={listDoituong} pagination={false} size="small" />;
});

interface ViewManageDoituongProps {}

export const ViewManageDoituong = React.memo((props: ViewManageDoituongProps) => {
    const {} = props;
    const [listDoituong, setListDoituong] = useState<Doituong[]>([]);
    const [pagiInfo, setPagiInfo] = useState<PagiInfo>(defaultPagiInfo);

    const getListDoituong = useCallback((page?: string) => {
        const conditions = { IsDeleted: false };
        DoituongApi.getPaginateDoituong(conditions, page).then((res: { listResult: Doituong[]; pagiInfo: PagiInfo }) => {
            setPagiInfo(res.pagiInfo);
            setListDoituong(res.listResult);
        });
    }, []);

    const handleDeleteDoituong = useCallback((id: number) => {
        const isConfirmed = window.confirm("Bạn có chắc chắn muốn xóa nhóm này không?");
        if (!isConfirmed) return;
        DoituongApi.delete(id).then((res: boolean) => {
            if (res) {
                window._toastbox("Xóa nhóm thành công", "success");
                setListDoituong((prev: Doituong[]) => prev.filter((d: Doituong) => d.id !== id));
            }
        });
    }, []);

    useEffect(() => {
        getListDoituong();
    }, [getListDoituong]);

    return (
        <div className="px-2 py-2">
            <div className="px-1 py-1">
                <Button type="link" href="/he-thong/doi-tuong/cap-nhat" className="text-success fw-semibold px-0">
                    + Thêm đối tượng
                </Button>
            </div>
            <Divider className="my-2" />
            <TableDoituong listDoituong={listDoituong} handleDeleteDoituong={handleDeleteDoituong} />
            <ComponentPagination pagiInfo={pagiInfo} callBack={getListDoituong} />
        </div>
    );
});

const ROOT_ID = "root-manage-doituong";
const bladeProps = readRootDataProps<ViewManageDoituongProps>(ROOT_ID) ?? {};
mountReactComponentOnReady(ROOT_ID, <ViewManageDoituong {...bladeProps} />);
