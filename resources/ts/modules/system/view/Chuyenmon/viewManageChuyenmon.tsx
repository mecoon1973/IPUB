import {
    mountReactComponentOnReady,
    readRootDataProps,
} from "../../../core/utils/helpers";
import React, { useCallback, useEffect, useMemo, useState } from "react";
import { defaultPagiInfo, type PagiInfo } from "../../../page/type";
import type Chuyenmon from "../../type/ChuyenMon";
import { ComponentPagination } from "../../../page/component/pagination";
import { ChuyenmonApi } from "../../api/ChuyenmonApi";
import { Button, Divider, Dropdown, Table } from "antd";
import type { MenuProps, TableProps } from "antd";

const TableChuyenmon = React.memo((props: { listChuyenmon: Chuyenmon[]; handleDeleteChuyenmon: (id: number) => void }) => {
    const { listChuyenmon, handleDeleteChuyenmon } = props;
    const columns: TableProps<Chuyenmon>["columns"] = useMemo(
        () => [
            { title: "STT", key: "stt", width: 72, render: (_v, _r, i) => i + 1 },
            { title: "Tên chuyên môn", dataIndex: "TenChuyenMon", key: "TenChuyenMon" },
            { title: "Mô tả", dataIndex: "MoTa", key: "MoTa" },
            {
                title: "",
                key: "action",
                width: 132,
                render: (_value, record) => {
                    const items: MenuProps["items"] = [
                        {
                            key: "edit",
                            label: <a href={`/he-thong/chuyen-mon/cap-nhat/${record.id}`}>Chỉnh sửa</a>,
                        },
                        {
                            key: "delete",
                            label: <span className="text-danger">Xóa</span>,
                            onClick: () => handleDeleteChuyenmon(record.id),
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
        [handleDeleteChuyenmon],
    );

    return (
        <Table<Chuyenmon> rowKey="id" columns={columns} dataSource={listChuyenmon} pagination={false} size="small" bordered />
    );
});

interface ViewManageChuyenmonProps {}

export const ViewManageChuyenmon = React.memo((props: ViewManageChuyenmonProps) => {
    const {} = props;
    const [pagiInfo, setPagiInfo] = useState<PagiInfo>(defaultPagiInfo);
    const [listChuyenmon, setListChuyenmon] = useState<Chuyenmon[]>([]);

    const getListChuyenmon = useCallback((page?: string) => {
        const conditions = { IsDeleted: false };
        ChuyenmonApi.getPaginateChuyenmon(conditions, page).then((res: { listResult: Chuyenmon[]; pagiInfo: PagiInfo }) => {
            setPagiInfo(res.pagiInfo);
            setListChuyenmon(res.listResult);
        });
    }, []);

    const handleDeleteChuyenmon = useCallback((id: number) => {
        const isConfirmed = window.confirm("Bạn có chắc chắn muốn xóa nhóm này không?");
        if (!isConfirmed) return;
        ChuyenmonApi.delete(id).then((res: boolean) => {
            if (res) {
                window._toastbox("Xóa chuyên môn thành công", "success");
                setListChuyenmon((prev: Chuyenmon[]) => prev.filter((c: Chuyenmon) => c.id !== id));
            }
        });
    }, []);

    useEffect(() => {
        getListChuyenmon();
    }, [getListChuyenmon]);

    return (
        <div className="px-2 py-2">
            <div className="px-1 py-1">
                <Button type="link" href="/he-thong/chuyen-mon/cap-nhat" className="text-success fw-semibold px-0">
                    + Thêm Chuyên môn
                </Button>
            </div>
            <Divider className="my-2" />
            <TableChuyenmon listChuyenmon={listChuyenmon} handleDeleteChuyenmon={handleDeleteChuyenmon} />
            <ComponentPagination pagiInfo={pagiInfo} callBack={getListChuyenmon} />
        </div>
    );
});

const ROOT_ID = "root-manage-chuyenmon";
const bladeProps = readRootDataProps<ViewManageChuyenmonProps>(ROOT_ID) ?? {};
mountReactComponentOnReady(ROOT_ID, <ViewManageChuyenmon {...bladeProps} />);
