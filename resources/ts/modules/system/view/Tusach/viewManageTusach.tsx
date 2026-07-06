import {
    mountReactComponentOnReady,
    readRootDataProps,
} from "../../../core/utils/helpers";
import React, { useCallback, useEffect, useMemo, useState } from "react";
import { defaultPagiInfo, type PagiInfo } from "../../../page/type";
import type Tusach from "../../type/TuSach";
import { ComponentPagination } from "../../../page/component/pagination";
import { TusachApi } from "../../api/TusachApi";
import { Button, Divider, Dropdown, Table } from "antd";
import type { MenuProps, TableProps } from "antd";

const TableTusach = React.memo((props: { listTusach: Tusach[]; handleDeleteTusach: (id: number) => void }) => {
    const { listTusach, handleDeleteTusach } = props;
    const columns: TableProps<Tusach>["columns"] = useMemo(
        () => [
            { title: "STT", key: "stt", width: 72, render: (_v, _r, i) => i + 1 },
            { title: "Mã tủ sách", dataIndex: "MaTuSach", key: "MaTuSach" },
            { title: "Tên tủ sách", dataIndex: "TenTuSach", key: "TenTuSach" },
            { title: "Mô tả", dataIndex: "MoTa", key: "MoTa" },
            {
                title: "",
                key: "action",
                width: 132,
                render: (_value, record) => {
                    const items: MenuProps["items"] = [
                        {
                            key: "edit",
                            label: <a href={`/he-thong/tu-sach/cap-nhat/${record.id}`}>Chỉnh sửa</a>,
                        },
                        {
                            key: "delete",
                            label: <span className="text-danger">Xóa</span>,
                            onClick: () => handleDeleteTusach(record.id),
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
        [handleDeleteTusach],
    );

    return <Table<Tusach> rowKey="id" columns={columns} dataSource={listTusach} pagination={false} size="small" bordered />;
});

interface ViewManageTusachProps {}

export const ViewManageTusach = React.memo((props: ViewManageTusachProps) => {
    const {} = props;
    const [pagiInfo, setPagiInfo] = useState<PagiInfo>(defaultPagiInfo);
    const [listTusach, setListTusach] = useState<Tusach[]>([]);

    const getListTusach = useCallback((page?: string) => {
        const conditions = { IsDeleted: false };
        TusachApi.getPaginateTusach(conditions, page).then((res: { listResult: Tusach[]; pagiInfo: PagiInfo }) => {
            setPagiInfo(res.pagiInfo);
            setListTusach(res.listResult);
        });
    }, []);

    const handleDeleteTusach = useCallback((id: number) => {
        const isConfirmed = window.confirm("Bạn có chắc chắn muốn xóa nhóm này không?");
        if (!isConfirmed) return;
        TusachApi.delete(id).then((res: boolean) => {
            if (res) {
                window._toastbox("Xóa tủ sách thành công", "success");
                setListTusach((prev: Tusach[]) => prev.filter((t: Tusach) => t.id !== id));
            }
        });
    }, []);

    useEffect(() => {
        getListTusach();
    }, [getListTusach]);

    return (
        <div className="px-2 py-2">
            <div className="px-1 py-1">
                <Button type="link" href="/he-thong/tu-sach/cap-nhat" className="text-success fw-semibold px-0">
                    + Thêm Tủ sách
                </Button>
            </div>
            <Divider className="my-2" />
            <TableTusach listTusach={listTusach} handleDeleteTusach={handleDeleteTusach} />
            <ComponentPagination pagiInfo={pagiInfo} callBack={getListTusach} />
        </div>
    );
});

const ROOT_ID = "root-manage-tusach";
const bladeProps = readRootDataProps<ViewManageTusachProps>(ROOT_ID) ?? {};
mountReactComponentOnReady(ROOT_ID, <ViewManageTusach {...bladeProps} />);
