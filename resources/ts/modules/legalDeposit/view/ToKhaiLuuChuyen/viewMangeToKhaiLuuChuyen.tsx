import {
    mountReactComponentOnReady,
    readRootDataProps,
} from "../../../core/utils/helpers";
import React, { useCallback, useEffect, useMemo, useState } from "react";
import type { PhieuNhapLC } from "../../type/PhieuNhapLC";
import type { FilterTokhaiLuuChuyen, ToKhaiLuuChuyen } from "../../type/ToKhaiLuuChuyen";
import { defaultPagiInfo, type PagiInfo } from "../../../page/type";
import { ComponentPagination } from "../../../page/component/pagination";
import { BookOutlined, SearchOutlined, ReloadOutlined } from '@ant-design/icons';
import { Button, Card, Col, Dropdown, Input, Radio, Row, Table } from "antd";
import type { MenuProps, TableProps } from "antd";
import "../../../../../css/modules/legalDeposit/TokhaiLuuChuyenFilter.css";
import { ToKhaiLuuChuyenApi } from "../../api/ToKhaiLuuChuyenApi";

const TableTokhaiLuuChuyen = React.memo((props: { listToKhaiLuuChuyen: ToKhaiLuuChuyen[], handleDeleteToKhaiLuuChuyen: (id: number) => void }) => {
    const { listToKhaiLuuChuyen, handleDeleteToKhaiLuuChuyen } = props;
    const columns: TableProps<ToKhaiLuuChuyen>["columns"] = useMemo(
        () => [
            { title: "STT", key: "stt", width: 56, align: "center", render: (_v, _r, i) => i + 1 },
            { title: "Mã Tờ khai", dataIndex: "", key: "", ellipsis: true },
            { title: "Tiêu đề", dataIndex: "TieuDe", key: "TieuDe", ellipsis: true },
            { title: "Người tạo", dataIndex: "", key: "", ellipsis: true },
            { title: "Ngày tạo", dataIndex: "", key: "", ellipsis: true },
            { title: "Ngày CXB xác nhận", dataIndex: "", key: "", ellipsis: true },
            { title: "Tổng số lượng sách", dataIndex: "", key: "", ellipsis: true },
            { title: "Số lượng đã cấp QĐPH", dataIndex: "", key: "", ellipsis: true },
            {
                title: "",
                key: "action",
                width: 140,
                render: (_value, record, index) => {
                    const items: MenuProps["items"] = [
                        {
                            key: "update-date",
                            label: "Cập nhật ngày CXB",
                        },
                        {
                            key: "edit",
                            label: "Chỉnh sửa tờ khai LC",
                        },
                        {
                            key: "list-qdph",
                            label: "Danh sách QĐPH",
                        },
                        {
                            key: "create-list-sign",
                            label: "Lập danh mục ký",
                        },
                        {
                            key: "detail-to-khai-lc",
                            label: "Chi tiết tờ khai LC",
                        },
                        {
                            key: "print",
                            label: "In",
                        },
                        {
                            key: "delete",
                            label: <span className="text-danger">Xóa</span>,
                            onClick: () => handleDeleteToKhaiLuuChuyen(record.id),
                        },
                    ];
                    return (
                        <Dropdown menu={{ items }} trigger={["click"]}>
                            <a onClick={(e) => e.preventDefault()} href="#">
                                Chức năng
                            </a>
                        </Dropdown>
                    );
                },
            },

        ],
        [],
    );

    return (
        <Table<ToKhaiLuuChuyen>
            rowKey="id"
            columns={columns}
            dataSource={listToKhaiLuuChuyen}
            pagination={false}
            size="small"
            bordered
            scroll={{ x: 1100 }}
        />
    );
});

interface FilterTokhaiLuuChuyenProps {
    contentSearch: string;
    setContentSearch: (contentSearch: string) => void;
    onChooseBook: () => void;
}


const FilterTokhaiLuuChuyenForm = React.memo((props: FilterTokhaiLuuChuyenProps) => {
    const { contentSearch, setContentSearch, onChooseBook } = props;

    return (
        <Card className="tokhai-lc-filter" bordered={false}>
            <Row gutter={[20, 16]} align="bottom">
                <Col xs={24} lg={16} xl={17}>
                    <label className="tokhai-lc-filter__label" htmlFor="tokhai-filter-tieu-de">
                        Tiêu đề
                    </label>
                    <Input
                        id="tokhai-filter-tieu-de"
                        size="middle"
                        allowClear
                        placeholder="Nhập mã số sách, tên sách để tìm kiếm"
                        prefix={<SearchOutlined className="text-secondary" />}
                        value={contentSearch ?? ""}
                        onChange={(e) => setContentSearch(e.target.value)}
                    />
                </Col>
                <Col xs={24} lg={8} xl={7}>
                    <div className="tokhai-lc-filter__actions">
                        <Button type="default" size="middle" icon={<SearchOutlined />} onClick={onChooseBook} block>
                            Tìm kiếm
                        </Button>
                        <Button type="default" size="middle" icon={<ReloadOutlined />} onClick={onChooseBook} block>
                            Tải lại
                        </Button>
                    </div>
                </Col>
            </Row>
        </Card>
    );
});

interface ViewManageTokhaiLuuChuyenProps {}

export const ViewManageTokhaiLuuChuyen = React.memo((props: ViewManageTokhaiLuuChuyenProps) => {
    const {} = props;
    const [pagiInfo, setPagiInfo] = useState<PagiInfo>(defaultPagiInfo);
    const [listToKhaiLuuChuyen, setListToKhaiLuuChuyen] = useState<ToKhaiLuuChuyen[]>([]);
    const [contentSearch, setContentSearch] = useState<string>("");

    const getListToKhaiLuuChuyen = useCallback(
        (page?: string) => {
            ToKhaiLuuChuyenApi.getPaginate(
                {
                    TieuDe: contentSearch,
                },
                page,
            ).then((res: { listResult: ToKhaiLuuChuyen[]; pagiInfo: PagiInfo }) => {
                setPagiInfo(res.pagiInfo);
                setListToKhaiLuuChuyen(res.listResult);
            });
        },
        [contentSearch],
    );

    const handleChooseBook = useCallback(() => {
        window._toastbox("Chức năng chọn sách đang được phát triển", "info");
    }, []);

    useEffect(() => {
        getListToKhaiLuuChuyen();
    }, []);

    const handleDeleteToKhaiLuuChuyen = useCallback((id: number) => {
        ToKhaiLuuChuyenApi.delete(id).then((res: boolean) => {
            if(res) {
                window._toastbox("Xóa tờ khai lưu chuyển thành công", "success");
                getListToKhaiLuuChuyen();
                setListToKhaiLuuChuyen((prev: ToKhaiLuuChuyen[]) => prev.filter((toKhaiLuuChuyen: ToKhaiLuuChuyen) => toKhaiLuuChuyen.id !== id));
            }
        });
    }, [getListToKhaiLuuChuyen]);
    return (
        <div className="px-2 py-2">
            <FilterTokhaiLuuChuyenForm contentSearch={contentSearch} setContentSearch={setContentSearch} onChooseBook={handleChooseBook} />
            <Row gutter={12} className="mt-0">
                <Col span={24}>
                    <TableTokhaiLuuChuyen listToKhaiLuuChuyen={listToKhaiLuuChuyen} handleDeleteToKhaiLuuChuyen={handleDeleteToKhaiLuuChuyen} />
                </Col>
            </Row>
            <ComponentPagination pagiInfo={pagiInfo} callBack={getListToKhaiLuuChuyen} />
        </div>
    );
});

const ROOT_ID = "root-manage-to-khai-luu-chuyen";
const bladeProps = readRootDataProps<ViewManageTokhaiLuuChuyenProps>(ROOT_ID) ?? {};
mountReactComponentOnReady(ROOT_ID, <ViewManageTokhaiLuuChuyen {...bladeProps} />);
