import {
    mountReactComponentOnReady,
    readRootDataProps,
} from "../../../core/utils/helpers";
import React, { useCallback, useEffect, useMemo, useState } from "react";
import { defaultPagiInfo, type PagiInfo } from "../../../page/type";
import type { LoaiXBP } from "../../type/LoaiXBP";
import { ComponentPagination } from "../../../page/component/pagination";
import { LoaiXBPApi } from "../../api/LoaiXBPApi";
import { Button, Divider, Dropdown, Table } from "antd";
import type { MenuProps, TableProps } from "antd";

export const TableLoaiXBP = React.memo((props: { listLoaiXBP: LoaiXBP[]; handleDeleteLoaiXBP: (id: number) => void }) => {
    const { listLoaiXBP, handleDeleteLoaiXBP } = props;
    const columns: TableProps<LoaiXBP>["columns"] = useMemo(
        () => [
            { title: "STT", key: "stt", width: 72, render: (_v, _r, i) => i + 1 },
            { title: "Mã loại xuất bản", dataIndex: "MaLoai", key: "MaLoai" },
            { title: "Tên loại xuất bản", dataIndex: "TenLoai", key: "TenLoai" },
            { title: "Mô tả", dataIndex: "MoTa", key: "MoTa" },
            {
                title: "Kiểu",
                key: "type",
                render: (_v, r) => `Mẫu MP${r.Type}`,
            },
            {
                title: "",
                key: "action",
                width: 132,
                render: (_value, record) => {
                    const items: MenuProps["items"] = [
                        {
                            key: "edit",
                            label: <a href={`/he-thong/loai-xbp/cap-nhat/${record.id}`}>Chỉnh sửa</a>,
                        },
                        {
                            key: "delete",
                            label: <span className="text-danger">Xóa</span>,
                            onClick: () => handleDeleteLoaiXBP(record.id),
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
        [handleDeleteLoaiXBP],
    );

    return <Table<LoaiXBP> rowKey="id" columns={columns} dataSource={listLoaiXBP} pagination={false} size="small" />;
});

interface ViewManageLoaiXBPProps {}

export const ViewManageLoaiXBP = React.memo((props: ViewManageLoaiXBPProps) => {
    const {} = props;
    const [pagiInfo, setPagiInfo] = useState<PagiInfo>(defaultPagiInfo);
    const [listLoaiXBP, setListLoaiXBP] = useState<LoaiXBP[]>([]);

    const getListLoaiXBP = useCallback((page?: string) => {
        const conditions = { IsDeleted: false };
        LoaiXBPApi.getPaginate(conditions, page).then((res: { listResult: LoaiXBP[]; pagiInfo: PagiInfo }) => {
            setPagiInfo(res.pagiInfo);
            setListLoaiXBP(res.listResult);
        });
    }, []);

    const handleDeleteLoaiXBP = useCallback((id: number) => {
        const isConfirmed = window.confirm("Bạn có chắc chắn muốn xóa nhóm này không?");
        if (!isConfirmed) return;
        LoaiXBPApi.delete(id).then((res: boolean) => {
            if (res) {
                window._toastbox("Xóa nhóm thành công", "success");
                setListLoaiXBP((prev: LoaiXBP[]) => prev.filter((x: LoaiXBP) => x.id !== id));
            }
        });
    }, []);

    useEffect(() => {
        getListLoaiXBP();
    }, [getListLoaiXBP]);

    return (
        <div className="px-2 py-2">
            <div className="px-1 py-1">
                <Button type="link" href="/he-thong/loai-xbp/cap-nhat" className="text-success fw-semibold px-0">
                    + Thêm Loại Xuất Bản
                </Button>
            </div>
            <Divider className="my-2" />
            <TableLoaiXBP listLoaiXBP={listLoaiXBP} handleDeleteLoaiXBP={handleDeleteLoaiXBP} />
            <ComponentPagination pagiInfo={pagiInfo} callBack={getListLoaiXBP} />
        </div>
    );
});

const ROOT_ID = "root-manage-loai-xbp";
const bladeProps = readRootDataProps<ViewManageLoaiXBPProps>(ROOT_ID) ?? {};
mountReactComponentOnReady(ROOT_ID, <ViewManageLoaiXBP {...bladeProps} />);
