import {
    mountReactComponentOnReady,
    readRootDataProps,
} from "../../../core/utils/helpers";
import React, { useCallback, useEffect, useMemo, useState } from "react";
import type { DoituongSNV } from "../../type";
import { defaultPagiInfo, type PagiInfo } from "../../../page/type";
import { ComponentPagination } from "../../../page/component/pagination";
import { Button, Divider, Dropdown, Table } from "antd";
import type { MenuProps, TableProps } from "antd";
import { DoituongSNVApi } from "../../api/DoituongSNVApi";

export const TableDoituongSNV = React.memo((props: { listDoituongSNV: DoituongSNV[]; handleDeleteDoituongSNV: (id: number) => void }) => {
    const { listDoituongSNV, handleDeleteDoituongSNV } = props;
    const columns: TableProps<DoituongSNV>["columns"] = useMemo(
        () => [
            { title: "STT", key: "stt", width: 72, render: (_v, _r, i) => i + 1 },
            { title: "Tên đối tượng", dataIndex: "TenDonVi", key: "TenDonVi" },
            { title: "Thứ tự", dataIndex: "ThuTu", key: "ThuTu" },
            {
                title: "",
                key: "action",
                width: 132,
                render: (_value, record: DoituongSNV) => {
                    const items: MenuProps["items"] = [
                        {
                            key: "edit",
                            label: <a href={`/he-thong/doi-tuong-snv/cap-nhat/${record.id}`}>Chỉnh sửa</a>,
                        },
                        {
                            key: "delete",
                            label: <span className="text-danger">Xóa</span>,
                            onClick: () => handleDeleteDoituongSNV(record.id),
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
        [handleDeleteDoituongSNV],
    );

    return <Table<DoituongSNV> rowKey="id" columns={columns} dataSource={listDoituongSNV} pagination={false} size="small" />;
});

interface ViewManageDoituongSNVProps {}

export const ViewManageDoituongSNV = React.memo((props: ViewManageDoituongSNVProps) => {
    const {} = props;
    const [listDoituongSNV, setListDoituongSNV] = useState<DoituongSNV[]>([]);
    const [pagiInfo, setPagiInfo] = useState<PagiInfo>(defaultPagiInfo);

    const getListDoituongSNV = useCallback((page?: string) => {
        DoituongSNVApi.getPaginate(DoituongSNVApi.conditionDefault, page).then((res: { listResult: DoituongSNV[]; pagiInfo: PagiInfo }) => {
            setPagiInfo(res.pagiInfo);
            setListDoituongSNV(res.listResult);
        });
    }, []);

    const handleDeleteDoituongSNV = useCallback((id: number) => {
        const isConfirmed = window.confirm("Bạn có chắc chắn muốn xóa nhóm này không?");
        if (!isConfirmed) return;
        DoituongSNVApi.delete(id).then((res: boolean) => {
            if (res) {
                window._toastbox("Xóa nhóm thành công", "success");
                setListDoituongSNV((prev: DoituongSNV[]) => prev.filter((d: DoituongSNV) => d.id !== id));
            }
        });
    }, []);

    useEffect(() => {
        getListDoituongSNV();
    }, [getListDoituongSNV]);

    return (
        <div className="px-2 py-2">
            <div className="px-1 py-1">
                <Button type="link" href="/he-thong/doi-tuong-snv/cap-nhat" className="text-success fw-semibold px-0">
                    + Thêm đối tượng
                </Button>
            </div>
            <Divider className="my-2" />
            <TableDoituongSNV listDoituongSNV={listDoituongSNV} handleDeleteDoituongSNV={handleDeleteDoituongSNV} />
            <ComponentPagination pagiInfo={pagiInfo} callBack={getListDoituongSNV} />
        </div>
    );
});

const ROOT_ID = "root-manage-doituong-snv";
const bladeProps = readRootDataProps<ViewManageDoituongSNVProps>(ROOT_ID) ?? {};
mountReactComponentOnReady(ROOT_ID, <ViewManageDoituongSNV {...bladeProps} />);
