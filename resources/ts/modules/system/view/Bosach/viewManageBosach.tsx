import {
    mountReactComponentOnReady,
    readRootDataProps,
} from "../../../core/utils/helpers";
import React, { useCallback, useEffect, useMemo, useState } from "react";
import type { Bosach } from "../../type/BoSach";
import { defaultPagiInfo, type PagiInfo } from "../../../page/type";
import { BosachApi } from "../../api/BosachApi";
import { ComponentPagination } from "../../../page/component/pagination";
import { Button, Col, Divider, Dropdown, Row, Table, Typography } from "antd";
import type { MenuProps, TableProps } from "antd";

const TableBosach = React.memo((props: { listBosach: Bosach[]; handleDeleteBosach: (id: number) => void }) => {
    const { listBosach, handleDeleteBosach } = props;
    const columns: TableProps<Bosach>["columns"] = useMemo(
        () => [
            { title: "STT", key: "stt", width: 72, render: (_v, _r, i) => i + 1 },
            { title: "Mã bộ sách", dataIndex: "MaBo", key: "MaBo" },
            { title: "Tên bộ sách", dataIndex: "TenBo", key: "TenBo" },
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
                            label: <a href={`/he-thong/bo-sach/cap-nhat/${record.id}`}>Chỉnh sửa</a>,
                        },
                        {
                            key: "delete",
                            label: <span className="text-danger">Xóa</span>,
                            onClick: () => handleDeleteBosach(record.id),
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
        [handleDeleteBosach],
    );

    return <Table<Bosach> rowKey="id" columns={columns} dataSource={listBosach} pagination={false} size="small" />;
});

const FilterBosach = React.memo(() => {
    return (
        <>
            <div className="py-2 px-2 border-bottom">
                <Typography.Title level={4} className="mb-0">
                    Danh mục bộ sách
                </Typography.Title>
            </div>
            <div className="py-2 px-2 border-bottom">
                <Button type="link" href="/he-thong/bo-sach/cap-nhat" className="text-success fw-semibold px-0">
                    + Thêm bộ sách
                </Button>
            </div>
        </>
    );
});

interface ViewManageBosachProps {}

export const ViewManageBosach = React.memo((props: ViewManageBosachProps) => {
    const {} = props;
    const [pagiInfo, setPagiInfo] = useState<PagiInfo>(defaultPagiInfo);
    const [listBosach, setListBosach] = useState<Bosach[]>([]);

    const getListBosach = useCallback((page?: string) => {
        const conditions = { IsDeleted: false };
        BosachApi.getPaginateBosach(conditions, page).then((res: { listResult: Bosach[]; pagiInfo: PagiInfo }) => {
            setPagiInfo(res.pagiInfo);
            setListBosach(res.listResult);
        });
    }, []);

    const handleDeleteBosach = useCallback((id: number) => {
        const isConfirmed = window.confirm("Bạn có chắc chắn muốn xóa bộ sách này không?");
        if (!isConfirmed) return;
        BosachApi.delete(id).then((res: boolean) => {
            if (res) {
                window._toastbox("Xóa bộ sách thành công", "success");
                setListBosach((prev: Bosach[]) => prev.filter((b: Bosach) => b.id !== id));
            }
        });
    }, []);

    useEffect(() => {
        getListBosach();
    }, [getListBosach]);

    return (
        <div className="px-2 py-2">
            <FilterBosach />
            <Divider className="my-2" />
            <Row gutter={12}>
                <Col span={24}>
                    <TableBosach listBosach={listBosach} handleDeleteBosach={handleDeleteBosach} />
                </Col>
            </Row>
            <ComponentPagination pagiInfo={pagiInfo} callBack={getListBosach} />
        </div>
    );
});

const ROOT_ID = "root-manage-bosach";
const bladeProps = readRootDataProps<ViewManageBosachProps>(ROOT_ID) ?? {};
mountReactComponentOnReady(ROOT_ID, <ViewManageBosach {...bladeProps} />);
