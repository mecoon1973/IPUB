import {
    mountReactComponentOnReady,
    readRootDataProps,
} from "../../../core/utils/helpers";
import React, { useCallback, useEffect, useMemo, useState } from "react";
import type { FilterTokhaiLuuChuyen, PhieuNhapLC } from "../../type";
import { defaultPagiInfo, type PagiInfo } from "../../../page/type";
import { ComponentPagination } from "../../../page/component/pagination";
import { BookOutlined, SearchOutlined } from "@ant-design/icons";
import { Button, Card, Col, Input, Radio, Row, Table } from "antd";
import type { TableProps } from "antd";
import dayjs from "dayjs";
import { PhieuNhapLCApi } from "../../api/PhieuNhapLCApi";
import "../../../../../css/modules/legalDeposit/TokhaiLuuChuyenFilter.css";

const TableTokhaiLuuChuyen = React.memo((props: { listPhieuNhapLC: PhieuNhapLC[] }) => {
    const { listPhieuNhapLC } = props;
    const columns: TableProps<PhieuNhapLC>["columns"] = useMemo(
        () => [
            { title: "STT", key: "stt", width: 56, align: "center", render: (_v, _r, i) => i + 1 },
            { title: "Mã số", dataIndex: "ID_Sach", key: "ID_Sach", width: 80 },
            { title: "Tên sách", dataIndex: "TenSach", key: "TenSach", ellipsis: true },
            { title: "Tác giả", dataIndex: "TacGia", key: "TacGia", width: 120, ellipsis: true },
            {
                title: "Ngày nộp",
                dataIndex: "NgayNhap",
                key: "NgayNhap",
                width: 100,
                render: (value: Date) => (value ? dayjs(value).format("DD/MM/YYYY") : ""),
            },
            { title: "Loại", dataIndex: "TheLoaiSach", key: "TheLoaiSach", width: 90, ellipsis: true },
            { title: "Số trang", dataIndex: "SoTrang", key: "SoTrang", width: 72, align: "center" },
            { title: "Khổ sách", dataIndex: "KhoSach", key: "KhoSach", width: 80 },
            { title: "Số bản", dataIndex: "SoLuongIn", key: "SoLuongIn", width: 72, align: "center" },
            { title: "Nộp LC", dataIndex: "SoLuong", key: "SoLuong", width: 72, align: "center" },
            {
                title: "GPXB",
                key: "GPXB",
                width: 88,
                render: (_v, record) =>
                    record.SoQuyetDXB
                        ? String(record.SoQuyetDXB)
                        : record.NgayCXBXacNhan
                          ? dayjs(record.NgayCXBXacNhan).format("DD/MM/YYYY")
                          : "",
            },
            {
                title: "Giá bìa",
                dataIndex: "GiaBia",
                key: "GiaBia",
                width: 88,
                align: "right",
                render: (value: number) => (value != null && value !== 0 ? String(value) : ""),
            },
        ],
        [],
    );

    return (
        <Table<PhieuNhapLC>
            rowKey="id"
            columns={columns}
            dataSource={listPhieuNhapLC}
            pagination={false}
            size="small"
            bordered
            scroll={{ x: 1100 }}
        />
    );
});

interface FilterTokhaiLuuChuyenProps {
    filter: FilterTokhaiLuuChuyen;
    setFilter: (filter: FilterTokhaiLuuChuyen) => void;
    onChooseBook: () => void;
}

const NOI_NOP_OPTIONS = [
    { label: "Nộp cục xuất bản", value: "cuc" as const },
    { label: "Nộp thư viện quốc gia", value: "thu-vien" as const },
];

const FilterTokhaiLuuChuyenForm = React.memo((props: FilterTokhaiLuuChuyenProps) => {
    const { filter, setFilter, onChooseBook } = props;

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
                        placeholder="Nhập tiêu đề tờ khai lưu chuyển..."
                        prefix={<SearchOutlined className="text-secondary" />}
                        value={filter.TieuDe ?? ""}
                        onChange={(e) => setFilter({ ...filter, TieuDe: e.target.value })}
                    />
                </Col>
                <Col xs={24} lg={8} xl={7}>
                    <div className="tokhai-lc-filter__actions">
                        <Button type="primary" size="middle" icon={<BookOutlined />} onClick={onChooseBook} block>
                            Chọn sách
                        </Button>
                    </div>
                </Col>
            </Row>

            <Row gutter={[20, 8]} align="middle" className="tokhai-lc-filter__noi-nop-row">
                <Col xs={24} md={4} lg={3} xl={2}>
                    <span className="tokhai-lc-filter__label mb-0">Nơi nộp</span>
                </Col>
                <Col xs={24} md={20} lg={21} xl={22}>
                    <Radio.Group
                        className="tokhai-lc-filter__noi-nop d-flex flex-wrap gap-2"
                        optionType="button"
                        buttonStyle="solid"
                        value={filter.NoiNop ?? "cuc"}
                        options={NOI_NOP_OPTIONS}
                        onChange={(e) => setFilter({ ...filter, NoiNop: e.target.value })}
                    />
                </Col>
            </Row>
        </Card>
    );
});

interface ViewStoreTokhaiLuuChuyenProps {}

export const ViewStoreTokhaiLuuChuyen = React.memo((props: ViewStoreTokhaiLuuChuyenProps) => {
    const {} = props;
    const [pagiInfo, setPagiInfo] = useState<PagiInfo>(defaultPagiInfo);
    const [listPhieuNhapLC, setListPhieuNhapLC] = useState<PhieuNhapLC[]>([]);
    const [filter, setFilter] = useState<FilterTokhaiLuuChuyen>({
        TieuDe: "",
        NoiNop: "cuc",
        IsDeleted: false,
    });

    const getListPhieuNhapLC = useCallback(
        (page?: string) => {
            PhieuNhapLCApi.getPaginate(
                {
                    // TuKhoa: filter.TieuDe,
                    // IsDeleted: filter.IsDeleted,
                },
                page,
            ).then((res: { listResult: PhieuNhapLC[]; pagiInfo: PagiInfo }) => {
                setPagiInfo(res.pagiInfo);
                setListPhieuNhapLC(res.listResult);
            });
        },
        [filter],
    );

    const handleChooseBook = useCallback(() => {
        window._toastbox("Chức năng chọn sách đang được phát triển", "info");
    }, []);

    // useEffect(() => {
    //     getListPhieuNhapLC();
    // }, []);

    return (
        <div className="px-2 py-2">
            <FilterTokhaiLuuChuyenForm filter={filter} setFilter={setFilter} onChooseBook={handleChooseBook} />
            <Row gutter={12} className="mt-0">
                <Col span={24}>
                    <TableTokhaiLuuChuyen listPhieuNhapLC={listPhieuNhapLC} />
                </Col>
            </Row>
            <ComponentPagination pagiInfo={pagiInfo} callBack={getListPhieuNhapLC} />
        </div>
    );
});

const ROOT_ID = "root-store-to-khai-luu-chuyen";
const bladeProps = readRootDataProps<ViewStoreTokhaiLuuChuyenProps>(ROOT_ID) ?? {};
mountReactComponentOnReady(ROOT_ID, <ViewStoreTokhaiLuuChuyen {...bladeProps} />);
