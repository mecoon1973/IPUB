import {
    mountReactComponentOnReady,
    readRootDataProps,
} from "../../../core/utils/helpers";
import React, { useCallback, useEffect, useMemo, useState } from "react";
import { defaultPagiInfo, type PagiInfo } from "../../../page/type";
import type { Monhoc } from "../../type";
import { ComponentPagination } from "../../../page/component/pagination";
import { MonhocApi } from "../../api/MonhocApi";
import { Button, Divider, Dropdown, Table } from "antd";
import type { MenuProps, TableProps } from "antd";

const TableMonhoc = React.memo((props: { listMonhoc: Monhoc[]; handleDeleteMonhoc: (id: number) => void }) => {
    const { listMonhoc, handleDeleteMonhoc } = props;
    const columns: TableProps<Monhoc>["columns"] = useMemo(
        () => [
            { title: "STT", key: "stt", width: 72, render: (_v, _r, i) => i + 1 },
            { title: "Mã môn học", dataIndex: "MaMonHoc", key: "MaMonHoc" },
            { title: "Tên môn học", dataIndex: "TenMonHoc", key: "TenMonHoc" },
            { title: "Kí hiệu", dataIndex: "KiHieu", key: "KiHieu" },
            { title: "Mô tả", dataIndex: "MoTa", key: "MoTa" },
            {
                title: "",
                key: "action",
                width: 132,
                render: (_value, record) => {
                    const items: MenuProps["items"] = [
                        {
                            key: "edit",
                            label: <a href={`/he-thong/mon-hoc/cap-nhat/${record.id}`}>Chỉnh sửa</a>,
                        },
                        {
                            key: "delete",
                            label: <span className="text-danger">Xóa</span>,
                            onClick: () => handleDeleteMonhoc(record.id),
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
        [handleDeleteMonhoc],
    );

    return <Table<Monhoc> rowKey="id" columns={columns} dataSource={listMonhoc} pagination={false} size="small" bordered />;
});

interface ViewManageMonhocProps {}

export const ViewManageMonhoc = React.memo((props: ViewManageMonhocProps) => {
    const {} = props;
    const [pagiInfo, setPagiInfo] = useState<PagiInfo>(defaultPagiInfo);
    const [listMonhoc, setListMonhoc] = useState<Monhoc[]>([]);

    const getListMonhoc = useCallback((page?: string) => {
        const conditions = { IsDeleted: false };
        MonhocApi.getPaginateMonhoc(conditions, page).then((res: { listResult: Monhoc[]; pagiInfo: PagiInfo }) => {
            setPagiInfo(res.pagiInfo);
            setListMonhoc(res.listResult);
        });
    }, []);

    const handleDeleteMonhoc = useCallback((id: number) => {
        const isConfirmed = window.confirm("Bạn có chắc chắn muốn xóa nhóm này không?");
        if (!isConfirmed) return;
        MonhocApi.delete(id).then((res: boolean) => {
            if (res) {
                window._toastbox("Xóa môn học thành công", "success");
                setListMonhoc((prev: Monhoc[]) => prev.filter((monhoc: Monhoc) => monhoc.id !== id));
            }
        });
    }, []);

    useEffect(() => {
        getListMonhoc();
    }, [getListMonhoc]);

    return (
        <div className="px-2 py-2">
            <div className="px-1 py-1">
                <Button type="link" href="/he-thong/monhoc/cap-nhat" className="text-success fw-semibold px-0">
                    + Thêm Môn học
                </Button>
            </div>
            <Divider className="my-2" />
            <TableMonhoc listMonhoc={listMonhoc} handleDeleteMonhoc={handleDeleteMonhoc} />
            <ComponentPagination pagiInfo={pagiInfo} callBack={getListMonhoc} />
        </div>
    );
});

const ROOT_ID = "root-manage-monhoc";
const bladeProps = readRootDataProps<ViewManageMonhocProps>(ROOT_ID) ?? {};
mountReactComponentOnReady(ROOT_ID, <ViewManageMonhoc {...bladeProps} />);
