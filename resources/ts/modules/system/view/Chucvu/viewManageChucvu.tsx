import {
    mountReactComponentOnReady,
    readRootDataProps,
} from "../../../core/utils/helpers";
import React, { useCallback, useEffect, useMemo, useState } from "react";
import { defaultPagiInfo, type PagiInfo } from "../../../page/type";
import { ComponentPagination } from "../../../page/component/pagination";
import { Button, Divider, Dropdown, Table } from "antd";
import type { MenuProps, TableProps } from "antd";
import type { Chucvu } from "../../type";
import { ChucvuApi } from "../../api/ChucvuApi";

const TableChucvu = React.memo((props: { listChucvu: Chucvu[]; handleDeleteChucvu: (id: number) => void }) => {
    const { listChucvu, handleDeleteChucvu } = props;
    const columns: TableProps<Chucvu>["columns"] = useMemo(
        () => [
            { title: "STT", key: "stt", width: 72, render: (_v, _r, i) => i + 1 },
            { title: "Mã chức vụ", dataIndex: "MaChucVu", key: "MaChucVu" },
            { title: "Tên chức vụ", dataIndex: "TenChucVu", key: "TenChucVu" },
            { title: "Mô tả", dataIndex: "MoTa", key: "MoTa" },
            {
                title: "",
                key: "action",
                width: 132,
                render: (_value, record) => {
                    const items: MenuProps["items"] = [
                        {
                            key: "edit",
                            label: <a href={`/he-thong/chuc-vu/cap-nhat/${record.id}`}>Chỉnh sửa</a>,
                        },
                        {
                            key: "delete",
                            label: <span className="text-danger">Xóa</span>,
                            onClick: () => handleDeleteChucvu(record.id),
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
        [handleDeleteChucvu],
    );

    return <Table<Chucvu> rowKey="id" columns={columns} dataSource={listChucvu} pagination={false} size="small" bordered />;
});

interface ViewManageChucvuProps {}

export const ViewManageChucvu = React.memo((props: ViewManageChucvuProps) => {
    const {} = props;
    const [pagiInfo, setPagiInfo] = useState<PagiInfo>(defaultPagiInfo);
    const [listChucvu, setListChucvu] = useState<Chucvu[]>([]);

    const getListChucvu = useCallback((page?: string) => {
        const conditions = {
            IsDeleted: false,
        };
        ChucvuApi.getPaginateChucvu(conditions, page).then((res: { listResult: Chucvu[]; pagiInfo: PagiInfo }) => {
            setPagiInfo(res.pagiInfo);
            setListChucvu(res.listResult);
        });
    }, [setPagiInfo, setListChucvu]);

    const handleDeleteChucvu = useCallback((id: number) => {
        const isConfirmed = window.confirm("Bạn có chắc chắn muốn xóa chức vụ này không?");
        if (!isConfirmed) return;
        ChucvuApi.delete(id).then((res: boolean) => {
            if (res) {
                window._toastbox("Xóa chức vụ thành công", "success");
                setListChucvu((prev: Chucvu[]) => prev.filter((chucvu: Chucvu) => chucvu.id !== id));
            }
        });
    }, []);

    useEffect(() => {
        getListChucvu();
    }, [getListChucvu]);

    return (
        <div className="px-2 py-2">
            <div className="px-1 py-1">
                <Button type="link" href="/he-thong/chuc-vu/cap-nhat" className="text-success fw-semibold px-0">
                    + Thêm Chức vụ
                </Button>
            </div>
            <Divider className="my-2" />
            <TableChucvu listChucvu={listChucvu} handleDeleteChucvu={handleDeleteChucvu} />
            <ComponentPagination pagiInfo={pagiInfo} callBack={getListChucvu} />
        </div>
    );
});

const ROOT_ID = "root-manage-chucvu";
const bladeProps = readRootDataProps<ViewManageChucvuProps>(ROOT_ID) ?? {};
mountReactComponentOnReady(ROOT_ID, <ViewManageChucvu {...bladeProps} />);
