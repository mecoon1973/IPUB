import {
    mountReactComponentOnReady,
    readRootDataProps,
} from "../../../core/utils/helpers";
import React, { useCallback, useEffect, useMemo, useState } from "react";
import type BienMoiTruong, type FilterBienMoiTruong from "../../type/BienMoiTruong";
import { defaultPagiInfo, type PagiInfo } from "../../../page/type";
import { ComponentPagination } from "../../../page/component/pagination";
import { Button, Col, Divider, Dropdown, Input, Row, Table, Typography } from "antd";
import type { MenuProps, TableProps } from "antd";
import { BienMoiTruongApi } from "../../api/BienMoiTruongApi";
import dayjs from "dayjs";

const TableBienMoiTruong = React.memo((props: { listBienMoiTruong: BienMoiTruong[]; handleDeleteBienMoiTruong: (id: number) => void }) => {
    const { listBienMoiTruong, handleDeleteBienMoiTruong } = props;
    const columns: TableProps<BienMoiTruong>["columns"] = useMemo(
        () => [
            { title: "STT", key: "stt", width: 72, render: (_v, _r, i) => i + 1 },
            { title: "Tên biến", dataIndex: "ConfigName", key: "ConfigName" },
            { title: "Giá trị", dataIndex: "ConfigValue", key: "ConfigValue" },
            { title: "Mô tả", dataIndex: "ConfigNotes", key: "ConfigNotes" },
            { title: "Ngày tạo", dataIndex: "CreatedOn", key: "CreatedOn", render: (value: Date) => value ? dayjs(value).format("DD/MM/YYYY HH:mm:ss") : "" },
            {
                title: "",
                key: "action",
                width: 132,
                render: (_value, record: BienMoiTruong) => {
                    const items: MenuProps["items"] = [
                        {
                            key: "edit",
                            label: <a href={`/he-thong/bien-moi-truong/cap-nhat/${record.id}`}>Chỉnh sửa</a>,
                        },
                        {
                            key: "delete",
                            label: <span className="text-danger">Xóa</span>,
                            onClick: () => handleDeleteBienMoiTruong(record.id),
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
        [handleDeleteBienMoiTruong],
    );

    return <Table<BienMoiTruong> rowKey="id" columns={columns} dataSource={listBienMoiTruong} pagination={false} size="small" />;
});

interface FilterBienMoiTruongProps {
    onSearch: () => void;
    filterBienMoiTruong: FilterBienMoiTruong;
    setFilterBienMoiTruong: (filterBienMoiTruong: FilterBienMoiTruong) => void;
}

const FilterBienMoiTruong = React.memo((props: FilterBienMoiTruongProps) => {
    const { onSearch, filterBienMoiTruong, setFilterBienMoiTruong } = props;
    return (
        <React.Fragment>
            <div className="py-2 px-2 border-bottom">
                <Typography.Title level={4} className="mb-0">
                    Danh mục biến mới trường
                </Typography.Title>
            </div>
            <div className="py-2 px-2 border-bottom">
                <Button type="link" href="/he-thong/bien-moi-truong/cap-nhat" className="text-success fw-semibold px-0">
                    + Thêm biến mới trường
                </Button>
            </div>

            <div className="d-grid gap-2" style={{ gridTemplateColumns: "1.2fr 1.2fr 1fr 1.5fr 1.8fr auto" }}>
                <div>
                    <div className="small text-muted mb-1">Tham số hệ thống của đơn vị</div>
                    <Input
                        type="text"
                        placeholder="Tham số hệ thống của đơn vị"
                        value={filterBienMoiTruong.ConfigSearch}
                        // onChange={(e) => setFilterBienMoiTruong({ ...filterBienMoiTruong, ConfigSearch: e.target.value })}
                    />
                </div>
                <div>
                    <div className="small text-muted mb-1">Tên, giá trị, mô tả</div>
                    <Input
                        type="text"
                        placeholder="Tìm kiếm theo tên, giá trị, mô tả"
                        value={filterBienMoiTruong.ConfigSearch}
                        onChange={(e) => setFilterBienMoiTruong({ ...filterBienMoiTruong, ConfigSearch: e.target.value })}
                    />
                </div>
                <div className="d-flex align-items-end">
                    <Button type="primary" onClick={onSearch}>
                        Tìm kiếm
                    </Button>
                </div>
            </div>
        </React.Fragment>
    );
});

interface ViewManageBienMoiTruongProps {}

export const ViewManageBienMoiTruong = React.memo((props: ViewManageBienMoiTruongProps) => {
    const {} = props;
    const [pagiInfo, setPagiInfo] = useState<PagiInfo>(defaultPagiInfo);
    const [listBienMoiTruong, setListBienMoiTruong] = useState<BienMoiTruong[]>([]);
    const [filterBienMoiTruong, setFilterBienMoiTruong] = useState<FilterBienMoiTruong>({
        ConfigSearch: "",
        id_Dv: 0,
    });

    const getListBienMoiTruong = useCallback((page?: string) => {
        BienMoiTruongApi.getPaginate(filterBienMoiTruong, page).then((res: { listResult: BienMoiTruong[]; pagiInfo: PagiInfo }) => {
            setPagiInfo(res.pagiInfo);
            setListBienMoiTruong(res.listResult);
        });
    }, [filterBienMoiTruong]);

    const handleDeleteBienMoiTruong = useCallback((id: number) => {
        const isConfirmed = window.confirm("Bạn có chắc chắn muốn xóa biến mới trường này không?");
        if (!isConfirmed) return;
        BienMoiTruongApi.delete(id).then((res: boolean) => {
            if (res) {
                window._toastbox("Xóa biến mới trường thành công", "success");
                setListBienMoiTruong((prev: BienMoiTruong[]) => prev.filter((b: BienMoiTruong) => b.id !== id));
            }
        });
    }, []);

    useEffect(() => {
        getListBienMoiTruong();
    }, []);

    return (
        <div className="px-2 py-2">
            <FilterBienMoiTruong onSearch={() => getListBienMoiTruong()} filterBienMoiTruong={filterBienMoiTruong} setFilterBienMoiTruong={setFilterBienMoiTruong}/>
            <Divider className="my-2" />
            <Row gutter={12}>
                <Col span={24}>
                    <TableBienMoiTruong listBienMoiTruong={listBienMoiTruong} handleDeleteBienMoiTruong={handleDeleteBienMoiTruong} />
                </Col>
            </Row>
            <ComponentPagination pagiInfo={pagiInfo} callBack={getListBienMoiTruong} />
        </div>
    );
});

const ROOT_ID = "root-manage-bien-moi-truong";
const bladeProps = readRootDataProps<ViewManageBienMoiTruongProps>(ROOT_ID) ?? {};
mountReactComponentOnReady(ROOT_ID, <ViewManageBienMoiTruong {...bladeProps} />);
