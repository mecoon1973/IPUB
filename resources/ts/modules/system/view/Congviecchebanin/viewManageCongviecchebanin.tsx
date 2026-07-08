import {
    mountReactComponentOnReady,
    readRootDataProps,
} from "../../../core/utils/helpers";
import React, { useCallback, useEffect, useMemo, useState } from "react";
import { defaultPagiInfo, type PagiInfo } from "../../../page/type";
import type { Congviecchebanin } from "../../type/CongViecCheBanIn";
import { ComponentPagination } from "../../../page/component/pagination";
import { CongviecchebaninApi } from "../../api/CongviecchebaninApi";
import { Button, Divider, Dropdown, Table } from "antd";
import type { MenuProps, TableProps } from "antd";

export const TableCongviecchebanin = React.memo(
    (props: { listCongviecchebanin: Congviecchebanin[]; handleDeleteCongviecchebanin: (id: number) => void }) => {
        const { listCongviecchebanin, handleDeleteCongviecchebanin } = props;
        const columns: TableProps<Congviecchebanin>["columns"] = useMemo(
            () => [
                { title: "STT", key: "stt", width: 72, render: (_v, _r, i) => i + 1 },
                { title: "Mã công việc che ban in", dataIndex: "MaCongViec", key: "MaCongViec" },
                { title: "Tên công việc che ban in", dataIndex: "TenCongViec", key: "TenCongViec" },
                {
                    title: "",
                    key: "action",
                    width: 132,
                    render: (_value, record) => {
                        const items: MenuProps["items"] = [
                            {
                                key: "edit",
                                label: <a href={`/he-thong/cong-viec-che-ban-in/cap-nhat/${record.id}`}>Chỉnh sửa</a>,
                            },
                            {
                                key: "delete",
                                label: <span className="text-danger">Xóa</span>,
                                onClick: () => handleDeleteCongviecchebanin(record.id),
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
            [handleDeleteCongviecchebanin],
        );

        return (
            <Table<Congviecchebanin>
                rowKey="id"
                columns={columns}
                dataSource={listCongviecchebanin}
                pagination={false}
                size="small"
            />
        );
    },
);

interface ViewManageCongviecchebaninProps {}

export const ViewManageCongviecchebanin = React.memo((props: ViewManageCongviecchebaninProps) => {
    const {} = props;
    const [pagiInfo, setPagiInfo] = useState<PagiInfo>(defaultPagiInfo);
    const [listCongviecchebanin, setListCongviecchebanin] = useState<Congviecchebanin[]>([]);

    const getListCongviecchebanin = useCallback((page?: string) => {
        const conditions = { IsDeleted: false };
        CongviecchebaninApi.getPaginate(conditions, page).then((res: { listResult: Congviecchebanin[]; pagiInfo: PagiInfo }) => {
            setPagiInfo(res.pagiInfo);
            setListCongviecchebanin(res.listResult);
        });
    }, []);

    const handleDeleteCongviecchebanin = useCallback((id: number) => {
        const isConfirmed = window.confirm("Bạn có chắc chắn muốn xóa nhóm này không?");
        if (!isConfirmed) return;
        CongviecchebaninApi.delete(id).then((res: boolean) => {
            if (res) {
                window._toastbox("Xóa công việc che ban in thành công", "success");
                setListCongviecchebanin((prev: Congviecchebanin[]) => prev.filter((c: Congviecchebanin) => c.id !== id));
            }
        });
    }, []);

    useEffect(() => {
        getListCongviecchebanin();
    }, [getListCongviecchebanin]);

    return (
        <div className="px-2 py-2">
            <div className="px-1 py-1">
                <Button type="link" href="/he-thong/cong-viec-che-ban-in/cap-nhat" className="text-success fw-semibold px-0">
                    + Thêm danh mục
                </Button>
            </div>
            <Divider className="my-2" />
            <TableCongviecchebanin listCongviecchebanin={listCongviecchebanin} handleDeleteCongviecchebanin={handleDeleteCongviecchebanin} />
            <ComponentPagination pagiInfo={pagiInfo} callBack={getListCongviecchebanin} />
        </div>
    );
});

const ROOT_ID = "root-manage-congviecchebanin";
const bladeProps = readRootDataProps<ViewManageCongviecchebaninProps>(ROOT_ID) ?? {};
mountReactComponentOnReady(ROOT_ID, <ViewManageCongviecchebanin {...bladeProps} />);
