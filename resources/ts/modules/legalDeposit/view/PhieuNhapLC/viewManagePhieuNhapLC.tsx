import {
    mountReactComponentOnReady,
    readRootDataProps,
} from "../../../core/utils/helpers";
import React, { useCallback, useEffect, useMemo, useState } from "react";
import type { FilterPhieuNhapLC, PhieuNhapLC } from "../../type";
import { defaultPagiInfo, type PagiInfo } from "../../../page/type";
import { ComponentPagination } from "../../../page/component/pagination";
import { Button, Col, Divider, Dropdown, Input, Row, Table, Typography } from "antd";
import type { MenuProps, TableProps } from "antd";
import dayjs from "dayjs";
import type { Dayjs } from "dayjs";
import DatePicker from "../../../core/utils/DatePicker";
import { convertValueToDayjs, formatDateToString } from "../../../core/utils/helpersDayjs";
import { PhieuNhapLCApi } from "../../api/PhieuNhapLCApi";

const TablePhieuNhapLC = React.memo((props: { listPhieuNhapLC: PhieuNhapLC[]; handleDeletePhieuNhapLC: (id: number) => void }) => {
    const { listPhieuNhapLC, handleDeletePhieuNhapLC } = props;
    const columns: TableProps<PhieuNhapLC>["columns"] = useMemo(
        () => [
            { title: "STT", key: "stt", width: 72, render: (_v, _r, i) => i + 1 },
            { title: "Ngày nhập", dataIndex: "NgayNhap", key: "NgayNhap", render: (value: Date) => value ? formatDateToString(value) : "" },
            { title: "Số phiếu", dataIndex: "SoPhieu", key: "SoPhieu" },
            { title: "Mã số sách", dataIndex: "ID_Sach", key: "ID_Sach", render: (_v, record: PhieuNhapLC) => record.sach?.MaSo ?? "" },
            { title: "Tên sách", dataIndex: "TenSach", key: "TenSach" },
            { title: "Đơn vị nộp", dataIndex: "DonViIn", key: "DonViIn" },
            { title: "Đã làm QĐPH", dataIndex: "DaCapQDPH", key: "DaCapQDPH", render: (value: boolean) => value ? "Có" : "Không" },
            { title: "Số QĐPH", dataIndex: "SoQuyetDXB", key: "SoQuyetDXB" },
            { title: "Tờ khai", dataIndex: "", key: "" },
            {
                title: "",
                key: "action",
                width: 132,
                render: (_value, record: PhieuNhapLC) => {
                    const items: MenuProps["items"] = [
                        {
                            key: "edit",
                            label: <a href={`/quan-ly-luu-chieu/phieu-nhap-lc/cap-nhat/${record.id}`}>Chỉnh sửa</a>,
                        },
                        {
                            key: "delete",
                            label: <span className="text-danger">Xóa</span>,
                            onClick: () => handleDeletePhieuNhapLC(record.id),
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
        [handleDeletePhieuNhapLC],
    );

    return <Table<PhieuNhapLC> rowKey="id" columns={columns} dataSource={listPhieuNhapLC} pagination={false} size="small" />;
});

interface FilterPhieuNhapLCProps {
    onSearch: () => void;
    filterPhieuNhapLC: FilterPhieuNhapLC;
    setFilterPhieuNhapLC: (filterPhieuNhapLC: FilterPhieuNhapLC) => void;
}

const FilterPhieuNhapLCForm = React.memo((props: FilterPhieuNhapLCProps) => {
    const { onSearch, filterPhieuNhapLC, setFilterPhieuNhapLC } = props;
    return (
        <React.Fragment>
            <div className="py-2 px-2 border-bottom">
                <Typography.Title level={4} className="mb-0">
                    Phiếu nhập lưu chiểu đơn vị
                </Typography.Title>
            </div>
            <div className="py-2 px-2 border-bottom">
                <Button type="link" href="/quan-ly-luu-chieu/phieu-nhap-lc/cap-nhat" className="text-success fw-semibold px-0">
                    + Thêm tờ khai lưu chiểu đơn vị
                </Button>
            </div>

            <div className="py-2 px-2 d-grid gap-2" style={{ gridTemplateColumns: "2.5fr 1fr 1fr auto" }}>
                <div>
                    <div className="small text-muted mb-1">Từ khóa tìm kiếm</div>
                    <Input
                        type="text"
                        placeholder="Từ khóa"
                        value={filterPhieuNhapLC.TuKhoa ?? ""}
                        onChange={(e) => setFilterPhieuNhapLC({ ...filterPhieuNhapLC, TuKhoa: e.target.value })}
                    />
                </div>
                <div>
                    <div className="small text-muted mb-1">Từ ngày</div>
                    <DatePicker
                        className="w-100"
                        format="DD/MM/YYYY"
                        placeholder="Từ ngày"
                        value={convertValueToDayjs(filterPhieuNhapLC.TuNgay)}
                        onChange={(date: Dayjs | null | undefined) => {
                            if (!date) return;
                            setFilterPhieuNhapLC({ ...filterPhieuNhapLC, TuNgay: date.toDate() });
                        }}
                    />
                </div>
                <div>
                    <div className="small text-muted mb-1">Đến ngày</div>
                    <DatePicker
                        className="w-100"
                        format="DD/MM/YYYY"
                        placeholder="Đến ngày"
                        value={convertValueToDayjs(filterPhieuNhapLC.DenNgay)}
                        onChange={(date: Dayjs | null | undefined) => {
                            if (!date) return;
                            setFilterPhieuNhapLC({ ...filterPhieuNhapLC, DenNgay: date.toDate() });
                        }}
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

interface ViewManagePhieuNhapLCProps {}

export const ViewManagePhieuNhapLC = React.memo((props: ViewManagePhieuNhapLCProps) => {
    const {} = props;
    const [pagiInfo, setPagiInfo] = useState<PagiInfo>(defaultPagiInfo);
    const [listPhieuNhapLC, setListPhieuNhapLC] = useState<PhieuNhapLC[]>([]);
    const [filterPhieuNhapLC, setFilterPhieuNhapLC] = useState<FilterPhieuNhapLC>({
        TuKhoa: "",
        TuNgay: dayjs().subtract(1, "month").toDate(),
        DenNgay: dayjs().toDate(),
        IsDeleted: false,
        relations: ["sach"]
    });

    const getListPhieuNhapLC = useCallback((page?: string) => {
        PhieuNhapLCApi.getPaginate(filterPhieuNhapLC, page).then((res: { listResult: PhieuNhapLC[]; pagiInfo: PagiInfo }) => {
            setPagiInfo(res.pagiInfo);
            setListPhieuNhapLC(res.listResult);
        });
    }, [filterPhieuNhapLC]);

    const handleDeletePhieuNhapLC = useCallback((id: number) => {
        const isConfirmed = window.confirm("Bạn có chắc chắn muốn xóa phiếu nhập LC này không?");
        if (!isConfirmed) return;
        PhieuNhapLCApi.delete(id).then((res: boolean) => {
            if (res) {
                window._toastbox("Xóa phiếu nhập LC thành công", "success");
                setListPhieuNhapLC((prev: PhieuNhapLC[]) => prev.filter((b: PhieuNhapLC) => b.id !== id));
            }
        });
    }, []);

    useEffect(() => {
        getListPhieuNhapLC();
    }, []);

    return (
        <div className="px-2 py-2">
            <FilterPhieuNhapLCForm onSearch={() => getListPhieuNhapLC()} filterPhieuNhapLC={filterPhieuNhapLC} setFilterPhieuNhapLC={setFilterPhieuNhapLC}/>
            <Divider className="my-2" />
            <Row gutter={12}>
                <Col span={24}>
                    <TablePhieuNhapLC listPhieuNhapLC={listPhieuNhapLC} handleDeletePhieuNhapLC={handleDeletePhieuNhapLC} />
                </Col>
            </Row>
            <ComponentPagination pagiInfo={pagiInfo} callBack={getListPhieuNhapLC} />
        </div>
    );
});

const ROOT_ID = "root-manage-phieu-nhap-lc";
const bladeProps = readRootDataProps<ViewManagePhieuNhapLCProps>(ROOT_ID) ?? {};
mountReactComponentOnReady(ROOT_ID, <ViewManagePhieuNhapLC {...bladeProps} />);
