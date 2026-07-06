import React, { forwardRef, useCallback, useEffect, useImperativeHandle, useMemo, useRef, useState } from "react";
import { Button, Checkbox, Col, Input, Modal, Row, Select, Table } from "antd";
import type { ColumnsType, TableProps } from "antd/es/table";
import { PhieuDkDetaiApi } from "../../../topic/api/PhieuDkDetaiApi";
import type { FilterPhieuDkDetai, PhieuDkDetai } from "../../../topic/type/PhieuDkDetai";
import { defaultFilterPhieuDkDetai } from "../../../topic/type/PhieuDkDetai";
import DatePicker from "../../../core/utils/DatePicker";
import { convertValueToDayjs } from "../../../core/utils/helpersDayjs";
import { ModalTree } from "../../../page/component/componentModalTree";
import { ComponentSelectAntObject } from "../../../page/component/componentSelectAnt";
import { useDataViewStore } from "../../../system/store/useDataViewStore";

/** Trạng thái HĐXB NXBGDVN (theo CONFIG_TRANG_THAI topic). */
const TRANG_THAI_HDXB_PHE_DUYET = 6;
const TRANG_THAI_HDXB_DE_NGHI = 5;

interface ModalChooseDeTaiFilter extends FilterPhieuDkDetai {
    TuKhoa?: string;
    LanTaiBan?: string;
    HdxbNxbgnvnPheDuyet?: boolean;
    HdxbNxbgnvnDeNghi?: boolean;
}

function defaultModalFilter(): ModalChooseDeTaiFilter {
    return {
        ...defaultFilterPhieuDkDetai,
        TuKhoa: "",
        LanTaiBan: "",
        HdxbNxbgnvnPheDuyet: false,
        HdxbNxbgnvnDeNghi: false,
    };
}

function buildSearchPayload(filter: ModalChooseDeTaiFilter): FilterPhieuDkDetai & { limit?: number } {
    const keyword = (filter.TuKhoa ?? "").trim();
    let trangThai = filter.TrangThai ?? -1;
    if (filter.HdxbNxbgnvnPheDuyet && !filter.HdxbNxbgnvnDeNghi) {
        trangThai = TRANG_THAI_HDXB_PHE_DUYET;
    } else if (filter.HdxbNxbgnvnDeNghi && !filter.HdxbNxbgnvnPheDuyet) {
        trangThai = TRANG_THAI_HDXB_DE_NGHI;
    }

    const payload: FilterPhieuDkDetai & { limit?: number } = {
        MaSo: keyword,
        TenDeTai: keyword,
        TacGia: keyword,
        BienTapVien: keyword,
        TrangThai: trangThai,
        IsDeleted: false,
        limit: 500,
    };

    if (filter.NamXuatBan) {
        payload.NamXuatBan = filter.NamXuatBan;
    }
    if (filter.ID_MangSach && filter.ID_MangSach > 0) {
        payload.ID_MangSach = filter.ID_MangSach;
    }
    if (filter.HTXB != null && filter.HTXB >= 0) {
        payload.HTXB = filter.HTXB;
    }
    if (filter.ID_DonVi && filter.ID_DonVi > 0) {
        payload.ID_DonVi = filter.ID_DonVi;
    }
    if (filter.NgayDK?.[0] && filter.NgayDK?.[1]) {
        payload.NgayDK = [filter.NgayDK[0], filter.NgayDK[1]];
    }

    return payload;
}

const HTXB_OPTIONS = [
    { value: -1, label: "-- Chọn --" },
    { value: 1, label: "Mới" },
    { value: 0, label: "Tái bản" },
];

const PAGE_SIZE = 10;

const MODAL_CHON_DETAI_COLUMNS: ColumnsType<PhieuDkDetai> = [
    {
        title: "STT",
        key: "stt",
        width: 56,
        align: "center",
        render: (_v, _r, index) => index + 1,
    },
    { title: "Mã số", dataIndex: "MaSo", key: "MaSo", width: 100, ellipsis: true },
    {
        title: "Tên đề tài",
        dataIndex: "TenDeTai",
        key: "TenDeTai",
        ellipsis: true,
        render: (value: string) => value ?? "",
    },
    { title: "Chú thích", dataIndex: "GhiChu", key: "GhiChu", width: 120, ellipsis: true },
    { title: "Tác giả", dataIndex: "TacGia", key: "TacGia", width: 200, ellipsis: true },
    { title: "Biên tập viên", dataIndex: "BienTapVien", key: "BienTapVien", width: 140, ellipsis: true },
    {
        title: "Số lượng",
        dataIndex: "SoLuongDK",
        key: "SoLuongDK",
        width: 90,
        align: "right",
        render: (value: number) => (value != null ? Number(value).toLocaleString("vi-VN") : ""),
    },
];

export interface ModalChooseDeTaiTableHandle {
    getSelectedItems: () => PhieuDkDetai[];
    clearSelection: () => void;
}

interface TableDetaiProps {
    dataSource: PhieuDkDetai[];
}

const TableDetai = React.memo(
    forwardRef<ModalChooseDeTaiTableHandle, TableDetaiProps>(function TableDetai(props, ref) {
        const { dataSource } = props;
        const [selectedRowKeys, setSelectedRowKeys] = useState<React.Key[]>([]);
        const [currentPage, setCurrentPage] = useState(1);
        const dataSourceRef = useRef(dataSource);
        const selectedRowKeysRef = useRef<React.Key[]>([]);
        dataSourceRef.current = dataSource;

        const syncSelection = useCallback((keys: React.Key[]) => {
            selectedRowKeysRef.current = keys;
            setSelectedRowKeys(keys);
        }, []);

        useEffect(() => {
            syncSelection([]);
            setCurrentPage(1);
        }, [dataSource, syncSelection]);

        useImperativeHandle(
            ref,
            () => ({
                getSelectedItems: () => {
                    const keys = new Set(selectedRowKeysRef.current);
                    return dataSourceRef.current.filter((item) => keys.has(item.id));
                },
                clearSelection: () => syncSelection([]),
            }),
            [syncSelection],
        );

        const rowSelection = useMemo(
            () => ({
                selectedRowKeys,
                onChange: (keys: React.Key[]) => syncSelection(keys),
            }),
            [selectedRowKeys, syncSelection],
        );

        const columns: TableProps<PhieuDkDetai>["columns"] = useMemo(
            () =>
                MODAL_CHON_DETAI_COLUMNS.map((col) =>
                    col.key === "stt"
                        ? {
                              ...col,
                              render: (_v, _r, index) => (currentPage - 1) * PAGE_SIZE + index + 1,
                          }
                        : col,
                ),
            [currentPage],
        );

        return (
            <Table<PhieuDkDetai>
                rowKey="id"
                columns={columns}
                dataSource={dataSource}
                rowSelection={rowSelection}
                size="small"
                bordered
                scroll={{ y: "calc(100vh - 420px)" }}
                pagination={{
                    current: currentPage,
                    pageSize: PAGE_SIZE,
                    showSizeChanger: false,
                    size: "small",
                    onChange: (page) => setCurrentPage(page),
                    showTotal: (total, range) =>
                        total > 0 ? `${range[0]}-${range[1]} / ${total} bản ghi` : "0 bản ghi",
                }}
            />
        );
    }),
);

interface FilterProps {
    filter: ModalChooseDeTaiFilter;
    setFilter: React.Dispatch<React.SetStateAction<ModalChooseDeTaiFilter>>;
    onSearch: () => void;
    loading: boolean;
}

const Filter = React.memo((props: FilterProps) => {
    const { filter, setFilter, onSearch, loading } = props;
    const listDonvi = useDataViewStore((state) => state.listDonvi);
    const listMangsach = useDataViewStore((state) => state.listMangsach);
    const [showModalMangsach, setShowModalMangsach] = useState(false);

    const updateFilter = useCallback(
        <K extends keyof ModalChooseDeTaiFilter>(key: K, value: ModalChooseDeTaiFilter[K]) => {
            setFilter((prev) => ({ ...prev, [key]: value }));
        },
        [setFilter],
    );

    const tenMangSach = useMemo(
        () => listMangsach.find((m) => m.id === filter.ID_MangSach)?.TenMang ?? "",
        [filter.ID_MangSach, listMangsach],
    );

    const ngayDkTu = filter.NgayDK?.[0];
    const ngayDkDen = filter.NgayDK?.[1];

    return (
        <div className="mb-2">
            <Row gutter={[12, 8]} align="bottom">
                <Col flex="1 1 280px">
                    <div className="small text-muted mb-1">Từ khóa tìm kiếm</div>
                    <Input
                        size="small"
                        allowClear
                        placeholder="Từ khóa tìm kiếm"
                        value={filter.TuKhoa ?? ""}
                        onChange={(e) => updateFilter("TuKhoa", e.target.value)}
                    />
                </Col>
                <Col xs={24} sm={8} md={6} lg={4} xl={3}>
                    <div className="small text-muted mb-1">Lần tái bản</div>
                    <Input
                        size="small"
                        allowClear
                        placeholder="Lần tái bản"
                        value={filter.LanTaiBan ?? ""}
                        onChange={(e) => updateFilter("LanTaiBan", e.target.value)}
                    />
                </Col>
            </Row>

            <Row gutter={[12, 8]} align="bottom" className="mt-1">
                <Col xs={24} sm={12} md={6}>
                    <div className="small text-muted mb-1">Tên đơn vị</div>
                    <ComponentSelectAntObject
                        size="small"
                        listData={listDonvi}
                        keyValue="id"
                        labelValue="TenDonVi"
                        value={filter.ID_DonVi && filter.ID_DonVi > 0 ? filter.ID_DonVi : null}
                        onChange={(value) => updateFilter("ID_DonVi", (value as number) ?? 0)}
                        placeholder="Tên đơn vị"
                        allowClear
                        showSearch
                        optionFilterProp="label"
                        filterOption={(input, option) =>
                            String(option?.label ?? "").toLowerCase().includes(input.toLowerCase())
                        }
                        style={{ width: "100%" }}
                    />
                </Col>
                <Col xs={24} sm={12} md={6}>
                    <div className="small text-muted mb-1">Chọn mảng sách</div>
                    <Input
                        size="small"
                        readOnly
                        placeholder="Chọn mảng sách"
                        value={tenMangSach}
                        onClick={() => setShowModalMangsach(true)}
                    />
                </Col>
                <Col xs={24} sm={12} md={6}>
                    <div className="small text-muted mb-1">Hình thức xuất bản</div>
                    <Select
                        size="small"
                        className="w-100"
                        value={filter.HTXB ?? -1}
                        options={HTXB_OPTIONS}
                        onChange={(value) => updateFilter("HTXB", value)}
                    />
                </Col>
                <Col xs={24} sm={12} md={6}>
                    <div className="small text-muted mb-1">Năm XB/TB</div>
                    <Input
                        size="small"
                        allowClear
                        placeholder="Năm XB/TB"
                        value={filter.NamXuatBan ?? ""}
                        onChange={(e) => updateFilter("NamXuatBan", e.target.value)}
                    />
                </Col>
            </Row>

            <Row gutter={[12, 8]} align="bottom" className="mt-1">
                <Col xs={24} lg={10} xl={9}>
                    <div className="d-flex flex-wrap gap-3 pt-1">
                        <Checkbox
                            checked={filter.HdxbNxbgnvnPheDuyet ?? false}
                            onChange={(e) => updateFilter("HdxbNxbgnvnPheDuyet", e.target.checked)}
                        >
                            HĐXB NXBGDVN phê duyệt
                        </Checkbox>
                        <Checkbox
                            checked={filter.HdxbNxbgnvnDeNghi ?? false}
                            onChange={(e) => updateFilter("HdxbNxbgnvnDeNghi", e.target.checked)}
                        >
                            HĐXB NXBGDVN đề nghị
                        </Checkbox>
                    </div>
                </Col>
                <Col xs={12} sm={8} md={5} lg={4}>
                    <div className="small text-muted mb-1">Từ ngày đăng ký</div>
                    <DatePicker
                        size="small"
                        className="w-100"
                        format="DD/MM/YYYY"
                        placeholder="Từ ngày"
                        allowClear
                        value={convertValueToDayjs(ngayDkTu) ?? null}
                        onChange={(date) => {
                            if (!date && !ngayDkDen) {
                                updateFilter("NgayDK", undefined);
                                return;
                            }
                            updateFilter("NgayDK", [
                                date?.toDate() ?? ngayDkDen!,
                                ngayDkDen ?? date!.toDate(),
                            ]);
                        }}
                    />
                </Col>
                <Col xs={12} sm={8} md={5} lg={4}>
                    <div className="small text-muted mb-1">Đến ngày đăng ký</div>
                    <DatePicker
                        size="small"
                        className="w-100"
                        format="DD/MM/YYYY"
                        placeholder="Đến ngày"
                        allowClear
                        value={convertValueToDayjs(ngayDkDen) ?? null}
                        onChange={(date) => {
                            if (!date && !ngayDkTu) {
                                updateFilter("NgayDK", undefined);
                                return;
                            }
                            updateFilter("NgayDK", [
                                ngayDkTu ?? date!.toDate(),
                                date?.toDate() ?? ngayDkTu!,
                            ]);
                        }}
                    />
                </Col>
                <Col xs={24} sm={8} md={6} lg={4} className="ms-lg-auto">
                    <Button
                        type="default"
                        size="small"
                        className="w-100"
                        loading={loading}
                        onClick={onSearch}
                    >
                        Tìm kiếm
                    </Button>
                </Col>
            </Row>

            <ModalTree
                title="Chọn mảng sách"
                show={showModalMangsach}
                onHide={() => setShowModalMangsach(false)}
                listData={listMangsach}
                getLabel={(mang) => mang.TenMang}
                handlerChoose={(mang) => updateFilter("ID_MangSach", mang.id)}
                usingselectChoose
                size={"xs"}
            />
        </div>
    );
});

interface ModalChooseDeTaiComponentProps {
    showModalChonDeTai: boolean;
    setShowModalChonDeTai: (showModalChonDeTai: boolean) => void;
    onSave?: (items: PhieuDkDetai[]) => void;
}

export const ModalChooseDeTaiComponent = React.memo((props: ModalChooseDeTaiComponentProps) => {
    const { showModalChonDeTai, setShowModalChonDeTai, onSave } = props;

    const [filter, setFilter] = useState<ModalChooseDeTaiFilter>(defaultModalFilter);
    const [dataSource, setDataSource] = useState<PhieuDkDetai[]>([]);
    const [loading, setLoading] = useState(false);
    const tableRef = useRef<ModalChooseDeTaiTableHandle>(null);

    const onSearch = useCallback(() => {
        setLoading(true);
        PhieuDkDetaiApi.getList(buildSearchPayload(filter))
            .then((res) => {
                setDataSource(res);
                if (res.length === 0) {
                    window._toastbox("Không tìm thấy dữ liệu", "warning");
                }
            })
            .finally(() => setLoading(false));
    }, [filter]);

    const handleSave = useCallback(() => {
        const selected = tableRef.current?.getSelectedItems() ?? [];
        if (selected.length === 0) {
            window._toastbox("Vui lòng chọn ít nhất một đề tài", "warning");
            return;
        }
        onSave?.(selected);
        tableRef.current?.clearSelection();
        setShowModalChonDeTai(false);
    }, [onSave, setShowModalChonDeTai]);

    const handleClose = useCallback(() => {
        setShowModalChonDeTai(false);
    }, [setShowModalChonDeTai]);

    return (
        <Modal
            open={showModalChonDeTai}
            onCancel={handleClose}
            title="DANH SÁCH ĐỀ TÀI HOẶC SÁCH CHƯA ĐỌC KIỂM ĐỊNH VÀ RÀ SOÁT"
            width="xl"
            destroyOnClose
            footer={[
                <Button key="save" type="primary" onClick={handleSave}>
                    Lưu lại
                </Button>,
                <Button key="close" onClick={handleClose}>
                    Hủy
                </Button>,
            ]}
        >
            <Filter filter={filter} setFilter={setFilter} onSearch={onSearch} loading={loading} />
            <TableDetai ref={tableRef} dataSource={dataSource} />
        </Modal>
    );
});
