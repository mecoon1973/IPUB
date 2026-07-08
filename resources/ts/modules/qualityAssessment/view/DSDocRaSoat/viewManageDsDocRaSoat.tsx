import {
    mountReactComponentOnReady,
    readRootDataProps,
} from "../../../core/utils/helpers";
import React, { useCallback, useEffect, useMemo, useState } from "react";
import { defaultPagiInfo, type PagiInfo } from "../../../page/type";
import { ComponentPagination } from "../../../page/component/pagination";
import { CopyOutlined, DownOutlined, UpOutlined } from "@ant-design/icons";
import { Button, Col, Collapse, Divider, Dropdown, Input, Row, Select, Table, Typography } from "antd";
import type { MenuProps, TableProps } from "antd";
import DatePicker from "../../../core/utils/DatePicker";
import { convertValueToDayjs, formatDateToString } from "../../../core/utils/helpersDayjs";
import { TYPE_DOC_RA_SOAT_OPTIONS, type DSDocRaSoat, type FilterDSDocRaSoat, type TypeDSDocRaSoat, TYPE_IS_SACH_OPTIONS } from "../../type/DSDocRaSoat";
import { DSDocRaSoatApi } from "../../api/DSDocRaSoatApi";
import type { User } from "../../../user/type/User";

const { RangePicker } = DatePicker;

const TableDSDocRaSoat = React.memo((props: { listDSDocRaSoat: DSDocRaSoat[]; handleDeleteDSDocRaSoat: (id: number) => void }) => {
    const { listDSDocRaSoat, handleDeleteDSDocRaSoat } = props;
    const columns: TableProps<DSDocRaSoat>["columns"] = useMemo(
        () => [
            { title: "STT", key: "stt", render: (_v, _r, i) => i + 1 },
            { title: "Tiêu đề", dataIndex: "Title", key: "Title" },
            { title: "Loại đọc duyệt", dataIndex: "Type", key: "Type" },
            { title: "Loại dữ liệu", dataIndex: "IsSach", key: "IsSach", render: (value: boolean) => value ? "Sách" : "Đề tài" },
            { title: "Người tạo", dataIndex: "user_create", key: "user_create", render: (value: User) => value?.HoTen ?? "" },
            { title: "Ngày tạo", dataIndex: "CreatedOn", key: "CreatedOn", render: (value: Date) => value ? formatDateToString(value) : "" },
            {
                title: "",
                key: "action",
                width: 132,
                render: (_value, record) => {
                    const items: MenuProps["items"] = [
                        {
                            key: "print",
                            label: 'In',
                        },
                        {
                            key: "edit",
                            label: <a href={`/he-thong/bo-sach/cap-nhat/${record.id}`}>Chỉnh sửa</a>,
                        },
                        {
                            key: "delete",
                            label: <span className="text-danger">Xóa</span>,
                            onClick: () => handleDeleteDSDocRaSoat(record.id),
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
        [handleDeleteDSDocRaSoat],
    );

    return <Table<DSDocRaSoat> rowKey="id" columns={columns} dataSource={listDSDocRaSoat} pagination={false} size="small" />;
});

const FilterDSDocRaSoat = React.memo((props: {
    filter: FilterDSDocRaSoat;
    setFilter: (filter: FilterDSDocRaSoat) => void;
    onSearch: () => void;
}) => {
    const { filter, setFilter, onSearch } = props;
    const [showAdvanced, setShowAdvanced] = useState(false);

    const toggleAdvanced = useCallback(() => {
        setShowAdvanced((prev) => !prev);
    }, []);

    const handleLapTuPhanMem = useCallback(() => {
        window._toastbox("Chức năng lập danh sách từ phần mềm đang được phát triển", "info");
    }, []);

    const handleLapTuExcel = useCallback(() => {
        window._toastbox("Chức năng lập danh sách từ excel đang được phát triển", "info");
    }, []);

    const advancedFilterPanel = useMemo(
        () => (
            <Row gutter={[12, 12]} align="bottom">
                <Col xs={24} sm={12} lg={5}>
                    <div className="small text-muted mb-1">Tiêu đề</div>
                    <Input
                        size="small"
                        placeholder="Nhập tiêu đề"
                        allowClear
                        value={filter.Title ?? ""}
                        onChange={(e) => setFilter({ ...filter, Title: e.target.value })}
                    />
                </Col>
                <Col xs={24} sm={12} lg={4}>
                    <div className="small text-muted mb-1">Loại đọc duyệt</div>
                    <Select
                        size="small"
                        className="w-100"
                        allowClear
                        placeholder="Tất cả"
                        value={filter.Type === "" ? undefined : filter.Type}
                        options={TYPE_DOC_RA_SOAT_OPTIONS}
                        onChange={(value) => {
                            const next: FilterDSDocRaSoat = { ...filter };
                            if (value == null) {
                                next.Type = "";
                            } else {
                                next.Type = value;
                            }
                            setFilter(next);
                        }}
                    />
                </Col>
                <Col xs={24} sm={12} lg={4}>
                    <div className="small text-muted mb-1">Loại dữ liệu</div>
                    <Select
                        size="small"
                        className="w-100"
                        allowClear
                        placeholder="Tất cả"
                        value={filter.IsSach === "" ? undefined : filter.IsSach}
                        options={TYPE_IS_SACH_OPTIONS}
                        onChange={(value) => {
                            const next: FilterDSDocRaSoat = { ...filter };
                            if (value == null) {
                                next.IsSach = "";
                            } else {
                                next.IsSach = value as boolean;
                            }
                            setFilter(next);
                        }}
                    />
                </Col>
                <Col xs={24} sm={12} lg={7}>
                    <div className="small text-muted mb-1">Thời gian tạo</div>
                    <RangePicker
                        className="w-100"
                        size="small"
                        format="DD/MM/YYYY"
                        placeholder={["Từ ngày", "Đến ngày"]}
                        allowClear
                        value={
                            filter.TuNgay || filter.DenNgay
                                ? [
                                      convertValueToDayjs(filter.TuNgay) ?? null,
                                      convertValueToDayjs(filter.DenNgay) ?? null,
                                  ]
                                : null
                        }
                        onChange={(dates) => {
                            const next: FilterDSDocRaSoat = { ...filter };
                            if (!dates || (!dates[0] && !dates[1])) {
                                delete next.TuNgay;
                                delete next.DenNgay;
                            } else {
                                if (dates[0]) {
                                    next.TuNgay = dates[0].toDate();
                                } else {
                                    delete next.TuNgay;
                                }
                                if (dates[1]) {
                                    next.DenNgay = dates[1].toDate();
                                } else {
                                    delete next.DenNgay;
                                }
                            }
                            setFilter(next);
                        }}
                    />
                </Col>
                <Col xs={24} sm={12} lg={4} className="d-flex justify-content-lg-end">
                    <Button type="primary" size="small" className="w-100" onClick={onSearch}>
                        Tìm kiếm
                    </Button>
                </Col>
            </Row>
        ),
        [filter, onSearch, setFilter],
    );

    return (
        <>
            <div className="py-2 px-2 border-bottom">
                <Typography.Title level={4} className="mb-0">
                    Lập danh sách đọc
                </Typography.Title>
            </div>

            <div className="py-2 px-3 border-bottom bg-white">
                <div className="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div className="d-flex align-items-center flex-wrap gap-3">
                        <Button
                            type="link"
                            className="px-0 text-primary"
                            icon={<CopyOutlined />}
                            onClick={handleLapTuPhanMem}
                        >
                            Lập danh sách từ phần mềm
                        </Button>
                        <Button
                            type="link"
                            className="px-0 text-primary"
                            icon={<CopyOutlined />}
                            onClick={handleLapTuExcel}
                        >
                            Lập danh sách từ excel
                        </Button>
                    </div>
                    <Button
                        type="link"
                        className="px-0 text-primary"
                        icon={showAdvanced ? <UpOutlined /> : <DownOutlined />}
                        onClick={toggleAdvanced}
                    >
                        Tìm kiếm nâng cao
                    </Button>
                </div>

                <Collapse
                    activeKey={showAdvanced ? ["advanced"] : []}
                    ghost
                    bordered={false}
                    expandIcon={() => null}
                    items={[
                        {
                            key: "advanced",
                            label: " ",
                            showArrow: false,
                            collapsible: "disabled",
                            classNames: { header: "d-none" },
                            children: advancedFilterPanel,
                        },
                    ]}
                />
            </div>
        </>
    );
});

interface ViewManageDSDocRaSoatProps {}

export const ViewManageDSDocRaSoat = React.memo((props: ViewManageDSDocRaSoatProps) => {
    const {} = props;
    const [pagiInfo, setPagiInfo] = useState<PagiInfo>(defaultPagiInfo);
    const [listDSDocRaSoat, setListDSDocRaSoat] = useState<DSDocRaSoat[]>([]);
    const [filter, setFilter] = useState<FilterDSDocRaSoat>({
        Deleted: false,
        Title: "",
        Type: "",
        IsSach: "",
        relations: ["user_create"],
    });

    const buildFilterQuery = useCallback((): FilterDSDocRaSoat => {
        const q: FilterDSDocRaSoat = {
            Deleted: filter.Deleted ?? false,
        };
        if (filter.Title?.trim()) q.Title = filter.Title.trim();
        if (filter.Type) q.Type = filter.Type as TypeDSDocRaSoat;
        if (filter.IsSach !== "" && filter.IsSach !== undefined) q.IsSach = filter.IsSach as boolean;
        if (filter.TuNgay && filter.DenNgay) {
            q.TuNgay = filter.TuNgay;
            q.DenNgay = filter.DenNgay;
        }
        if (filter.relations) q.relations = filter.relations;
        return q;
    }, [filter]);

    const getListDSDocRaSoat = useCallback((page?: string) => {
        DSDocRaSoatApi.getPaginate(buildFilterQuery(), page).then((res: { listResult: DSDocRaSoat[]; pagiInfo: PagiInfo }) => {
            setPagiInfo(res.pagiInfo);
            setListDSDocRaSoat(res.listResult);
        });
    }, [buildFilterQuery]);

    const handleDeleteDSDocRaSoat = useCallback((id: number) => {
        const isConfirmed = window.confirm("Bạn có chắc chắn muốn xóa đề xuất ra soát này không?");
        if (!isConfirmed) return;
        DSDocRaSoatApi.delete(id).then((res: boolean) => {
            if (res) {
                window._toastbox("Xóa đề xuất ra soát thành công", "success");
                setListDSDocRaSoat((prev: DSDocRaSoat[]) => prev.filter((d: DSDocRaSoat) => d.id !== id));
            }
        });
    }, []);

    useEffect(() => {
        getListDSDocRaSoat();
    }, []);

    return (
        <div className="px-2 py-2">
            <FilterDSDocRaSoat filter={filter} setFilter={setFilter} onSearch={() => getListDSDocRaSoat()} />
            <Divider className="my-2" />
            <Row gutter={12}>
                <Col span={24}>
                    <TableDSDocRaSoat listDSDocRaSoat={listDSDocRaSoat} handleDeleteDSDocRaSoat={handleDeleteDSDocRaSoat} />
                </Col>
            </Row>
            <ComponentPagination pagiInfo={pagiInfo} callBack={getListDSDocRaSoat} />
        </div>
    );
});

const ROOT_ID = "root-manage-ds-doc-ra-soat";
const bladeProps = readRootDataProps<ViewManageDSDocRaSoatProps>(ROOT_ID) ?? {};
mountReactComponentOnReady(ROOT_ID, <ViewManageDSDocRaSoat {...bladeProps} />);
