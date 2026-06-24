import {
    mountReactComponentOnReady,
    readRootDataProps,
} from "../../../core/utils/helpers";
import React, { useCallback, useEffect, useMemo, useState } from "react";
import { defaultPagiInfo, type PagiInfo } from "../../../page/type";
import type { MangsachCXB } from "../../type";
import { ComponentPagination } from "../../../page/component/pagination";
import { MangsachCXBApi } from "../../api/MangsachCXBApi";
import { Button, Divider, Dropdown, Table } from "antd";
import type { MenuProps, TableProps } from "antd";

export const TableMangsachCXB = React.memo((props: { listMangsachCXB: MangsachCXB[]; handleDeleteMangsachCXB: (id: number) => void }) => {
    const { listMangsachCXB, handleDeleteMangsachCXB } = props;
    const columns: TableProps<MangsachCXB>["columns"] = useMemo(
        () => [
            { title: "STT", key: "stt", width: 72, render: (_v, _r, i) => i + 1 },
            { title: "Mã mảng sách CXB", dataIndex: "MaMang", key: "MaMang" },
            { title: "Tên mảng sách CXB", dataIndex: "TenMang", key: "TenMang" },
            { title: "Mô tả", dataIndex: "MoTa", key: "MoTa" },
            {
                title: "",
                key: "action",
                width: 132,
                render: (_value, record) => {
                    const items: MenuProps["items"] = [
                        {
                            key: "edit",
                            label: <a href={`/he-thong/mang-sach-cxb/cap-nhat/${record.id}`}>Chỉnh sửa</a>,
                        },
                        {
                            key: "delete",
                            label: <span className="text-danger">Xóa</span>,
                            onClick: () => handleDeleteMangsachCXB(record.id),
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
        [handleDeleteMangsachCXB],
    );

    return <Table<MangsachCXB> rowKey="id" columns={columns} dataSource={listMangsachCXB} pagination={false} size="small" />;
});

interface ViewManageMangsachCXBProps {}

export const ViewManageMangsachCXB = React.memo((props: ViewManageMangsachCXBProps) => {
    const {} = props;
    const [pagiInfo, setPagiInfo] = useState<PagiInfo>(defaultPagiInfo);
    const [listMangsachCXB, setListMangsachCXB] = useState<MangsachCXB[]>([]);

    const getListMangsachCXB = useCallback((page?: string) => {
        const conditions = { IsDeleted: false };
        MangsachCXBApi.getPaginateMangsachCXB(conditions, page).then((res: { listResult: MangsachCXB[]; pagiInfo: PagiInfo }) => {
            setPagiInfo(res.pagiInfo);
            setListMangsachCXB(res.listResult);
        });
    }, []);

    const handleDeleteMangsachCXB = useCallback((id: number) => {
        const isConfirmed = window.confirm("Bạn có chắc chắn muốn xóa mảng sách CXB này không?");
        if (!isConfirmed) return;
        MangsachCXBApi.delete(id).then((res: boolean) => {
            if (res) {
                window._toastbox("Xóa mảng sách CXB thành công", "success");
                setListMangsachCXB((prev: MangsachCXB[]) => prev.filter((m: MangsachCXB) => m.id !== id));
            }
        });
    }, []);

    useEffect(() => {
        getListMangsachCXB();
    }, [getListMangsachCXB]);

    return (
        <div className="px-2 py-2">
            <div className="px-1 py-1">
                <Button type="link" href="/he-thong/mangsach-cxb/cap-nhat" className="text-success fw-semibold px-0">
                    + Thêm Mảng sách CXB
                </Button>
            </div>
            <Divider className="my-2" />
            <TableMangsachCXB listMangsachCXB={listMangsachCXB} handleDeleteMangsachCXB={handleDeleteMangsachCXB} />
            <ComponentPagination pagiInfo={pagiInfo} callBack={getListMangsachCXB} />
        </div>
    );
});

const ROOT_ID = "root-manage-mangsach-cxb";
const bladeProps = readRootDataProps<ViewManageMangsachCXBProps>(ROOT_ID) ?? {};
mountReactComponentOnReady(ROOT_ID, <ViewManageMangsachCXB {...bladeProps} />);
