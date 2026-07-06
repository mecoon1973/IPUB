import {
    mountReactComponentOnReady,
    readRootDataProps,
} from "../../../core/utils/helpers";
import React, { useCallback, useEffect, useMemo, useState } from "react";
import { defaultPagiInfo, type PagiInfo } from "../../../page/type";
import type Ngoaingu from "../../type/NgoaiNgu";
import { ComponentPagination } from "../../../page/component/pagination";
import { NgoainguApi } from "../../api/NgoainguApi";
import { Button, Divider, Dropdown, Table } from "antd";
import type { MenuProps, TableProps } from "antd";

export const TableNgoaingu = React.memo((props: { listNgoaingu: Ngoaingu[]; handleDeleteNgoaingu: (id: number) => void }) => {
    const { listNgoaingu, handleDeleteNgoaingu } = props;
    const columns: TableProps<Ngoaingu>["columns"] = useMemo(
        () => [
            { title: "STT", key: "stt", width: 72, render: (_v, _r, i) => i + 1 },
            { title: "Mã Ngoại ngữ", dataIndex: "MaNgoaiNgu", key: "MaNgoaiNgu" },
            { title: "Tên Ngoại ngữ", dataIndex: "TenNgoaiNgu", key: "TenNgoaiNgu" },
            { title: "Thứ tự", dataIndex: "ThuTu", key: "ThuTu" },
            {
                title: "",
                key: "action",
                width: 132,
                render: (_value, record) => {
                    const items: MenuProps["items"] = [
                        {
                            key: "edit",
                            label: <a href={`/he-thong/ngoai-ngu/cap-nhat/${record.id}`}>Chỉnh sửa</a>,
                        },
                        {
                            key: "delete",
                            label: <span className="text-danger">Xóa</span>,
                            onClick: () => handleDeleteNgoaingu(record.id),
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
        [handleDeleteNgoaingu],
    );

    return <Table<Ngoaingu> rowKey="id" columns={columns} dataSource={listNgoaingu} pagination={false} size="small" />;
});

interface ViewManageNgoainguProps {}

export const ViewManageNgoaingu = React.memo((props: ViewManageNgoainguProps) => {
    const {} = props;
    const [pagiInfo, setPagiInfo] = useState<PagiInfo>(defaultPagiInfo);
    const [listNgoaingu, setListNgoaingu] = useState<Ngoaingu[]>([]);

    const getListNgoaingu = useCallback((page?: string) => {
        const conditions = { IsDeleted: false };
        NgoainguApi.getPaginateNgoaingu(conditions, page).then((res: { listResult: Ngoaingu[]; pagiInfo: PagiInfo }) => {
            setPagiInfo(res.pagiInfo);
            setListNgoaingu(res.listResult);
        });
    }, []);

    const handleDeleteNgoaingu = useCallback((id: number) => {
        const isConfirmed = window.confirm("Bạn có chắc chắn muốn xóa Ngoại ngữ này không?");
        if (!isConfirmed) return;
        NgoainguApi.delete(id).then((res: boolean) => {
            if (res) {
                window._toastbox("Xóa Ngoại ngữ thành công", "success");
                setListNgoaingu((prev: Ngoaingu[]) => prev.filter((n: Ngoaingu) => n.id !== id));
            }
        });
    }, []);

    useEffect(() => {
        getListNgoaingu();
    }, [getListNgoaingu]);

    return (
        <div className="px-2 py-2">
            <div className="px-1 py-1">
                <Button type="link" href="/he-thong/ngoai-ngu/cap-nhat" className="text-success fw-semibold px-0">
                    + Thêm Ngoại ngữ
                </Button>
            </div>
            <Divider className="my-2" />
            <TableNgoaingu listNgoaingu={listNgoaingu} handleDeleteNgoaingu={handleDeleteNgoaingu} />
            <ComponentPagination pagiInfo={pagiInfo} callBack={getListNgoaingu} />
        </div>
    );
});

const ROOT_ID = "root-manage-ngoaingu";
const bladeProps = readRootDataProps<ViewManageNgoainguProps>(ROOT_ID) ?? {};
mountReactComponentOnReady(ROOT_ID, <ViewManageNgoaingu {...bladeProps} />);
