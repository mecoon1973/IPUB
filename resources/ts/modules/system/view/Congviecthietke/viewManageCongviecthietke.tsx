import {
    mountReactComponentOnReady,
    readRootDataProps,
} from "../../../core/utils/helpers";
import React, { useCallback, useEffect, useMemo, useState } from "react";
import { defaultPagiInfo, type PagiInfo } from "../../../page/type";
import type { Congviecthietke } from "../../type";
import { ComponentPagination } from "../../../page/component/pagination";
import { CongviecthietkeApi } from "../../api/CongviecthietkeApi";
import { Button, Divider, Dropdown, Table } from "antd";
import type { MenuProps, TableProps } from "antd";

export const TableCongviecthietke = React.memo(
    (props: { listCongviecthietke: Congviecthietke[]; handleDeleteCongviecthietke: (id: number) => void }) => {
        const { listCongviecthietke, handleDeleteCongviecthietke } = props;
        const columns: TableProps<Congviecthietke>["columns"] = useMemo(
            () => [
                { title: "STT", key: "stt", width: 72, render: (_v, _r, i) => i + 1 },
                { title: "Mã công việc thiết kế", dataIndex: "MaCongViec", key: "MaCongViec" },
                { title: "Tên công việc thiết kế", dataIndex: "TenCongViec", key: "TenCongViec" },
                { title: "Đơn vị tính", dataIndex: "DVT", key: "DVT" },
                {
                    title: "",
                    key: "action",
                    width: 132,
                    render: (_value, record) => {
                        const items: MenuProps["items"] = [
                            {
                                key: "edit",
                                label: <a href={`/he-thong/cong-viec-thiet-ke/cap-nhat/${record.id}`}>Chỉnh sửa</a>,
                            },
                            {
                                key: "delete",
                                label: <span className="text-danger">Xóa</span>,
                                onClick: () => handleDeleteCongviecthietke(record.id),
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
            [handleDeleteCongviecthietke],
        );

        return (
            <Table<Congviecthietke>
                rowKey="id"
                columns={columns}
                dataSource={listCongviecthietke}
                pagination={false}
                size="small"
            />
        );
    },
);

interface ViewManageCongviecthietkeProps {}

export const ViewManageCongviecthietke = React.memo((props: ViewManageCongviecthietkeProps) => {
    const {} = props;
    const [pagiInfo, setPagiInfo] = useState<PagiInfo>(defaultPagiInfo);
    const [listCongviecthietke, setListCongviecthietke] = useState<Congviecthietke[]>([]);

    const getListCongviecthietke = useCallback((page?: string) => {
        const conditions = { IsDeleted: false };
        CongviecthietkeApi.getPaginate(conditions, page).then((res: { listResult: Congviecthietke[]; pagiInfo: PagiInfo }) => {
            setPagiInfo(res.pagiInfo);
            setListCongviecthietke(res.listResult);
        });
    }, []);

    const handleDeleteCongviecthietke = useCallback((id: number) => {
        const isConfirmed = window.confirm("Bạn có chắc chắn muốn xóa công việc thiết kế này không?");
        if (!isConfirmed) return;
        CongviecthietkeApi.delete(id).then((res: boolean) => {
            if (res) {
                window._toastbox("Xóa công việc thiết kế thành công", "success");
                setListCongviecthietke((prev: Congviecthietke[]) => prev.filter((c: Congviecthietke) => c.id !== id));
            }
        });
    }, []);

    useEffect(() => {
        getListCongviecthietke();
    }, [getListCongviecthietke]);

    return (
        <div className="px-2 py-2">
            <div className="px-1 py-1">
                <Button type="link" href="/he-thong/cong-viec-thiet-ke/cap-nhat" className="text-success fw-semibold px-0">
                    + Thêm danh mục
                </Button>
            </div>
            <Divider className="my-2" />
            <TableCongviecthietke listCongviecthietke={listCongviecthietke} handleDeleteCongviecthietke={handleDeleteCongviecthietke} />
            <ComponentPagination pagiInfo={pagiInfo} callBack={getListCongviecthietke} />
        </div>
    );
});

const ROOT_ID = "root-manage-congviecthietke";
const bladeProps = readRootDataProps<ViewManageCongviecthietkeProps>(ROOT_ID) ?? {};
mountReactComponentOnReady(ROOT_ID, <ViewManageCongviecthietke {...bladeProps} />);
