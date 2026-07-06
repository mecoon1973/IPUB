import React, { useCallback, useEffect, useState } from "react";
import { Button, Checkbox, Col, Input, Modal, Row, Space, Table, Typography } from "antd";
import type { ColumnsType } from "antd/es/table";
import { mountReactComponentOnReady } from "../../../core/utils/helpers";
import DatePickerAntd from "../../../core/utils/DatePicker";
import { convertValueToDayjs, formatDateToString, toIso8601UtcOffset } from "../../../core/utils/helpersDayjs";
import type { QDIn } from "../../type/QDIn";
import { QDInApi } from "../../api/QDInApi";
import FormFieldQDIn from "../../component/QDIn/FormFieldQDIn";
import ModalChooseBook from "../../component/QDIn/ModalChooseBook";

/** Form đầy đủ trong modal “Thêm mới chi tiết quyết định in”. */
export interface QDInChiTietSachForm {
    NamXuatBanTB: string;
    TenSach: string;
    BienTapVien: string;
    TacGia: string;
    SLIn: string;
    SLDangKy: string;
    SLDaCap: string;
    SLConLai: string;
    MaSoCXB: string;
    HTXB: string;
    LanTB: string;
    SoTrang: string;
    KhoSach: string;
    MauInRuot: string;
    MauInBia: string;
    GiaBia: string;
    THBS: string;
    GiayInRuot: string;
    GiayInBia: string;
    LaSachDienTu: boolean;
    SoByte: string;
    DinhDangTep: string;
    LanNoiBan: string;
    THNhapKho: Date | null;
    DiaChiCungCap: string;
    DonViIn: string;
    DiaChiDonViIn: string;
    CoSoIn: string;
    GhiChu: string;
}

/** Một dòng thông tin sách trên QĐ in. */
export interface QDInSachDong {
    key: string;
    MaSo: string;
    TenSach: string;
    SLIn: string;
    NoiIn: string;
    NgayNhapKho: string;
    TinhHinhBienSoan: string;
    GhiChu: string;
    chiTiet?: QDInChiTietSachForm;
}


function newSachKey(): string {
    return typeof crypto !== "undefined" && crypto.randomUUID
        ? crypto.randomUUID()
        : `sach_${Date.now()}_${Math.random().toString(36).slice(2, 9)}`;
}

interface TableInfoBookProps {
    dataSource: QDInSachDong[];
    onChangeRow: (index: number, patch: Partial<QDInSachDong>) => void;
    onRemoveRow: (index: number) => void;
}

const TableInfoBook = React.memo((props: TableInfoBookProps) => {
    const { dataSource, onChangeRow, onRemoveRow } = props;

    const sachColumns: ColumnsType<QDInSachDong> = [
        {
            title: "STT",
            width: 48,
            align: "center",
            render: (_, __, index) => index + 1,
        },
        {
            title: "Mã số",
            width: 110,
            render: (_, row, index) => (
                <Input
                    size="small"
                    value={row.MaSo}
                    onChange={(e) => onChangeRow(index, { MaSo: e.target.value })}
                />
            ),
        },
        {
            title: "Tên sách",
            width: 200,
            render: (_, row, index) => (
                <Input
                    size="small"
                    value={row.TenSach}
                    onChange={(e) => onChangeRow(index, { TenSach: e.target.value })}
                />
            ),
        },
        {
            title: "SL in",
            width: 88,
            render: (_, row, index) => (
                <Input
                    size="small"
                    value={row.SLIn}
                    onChange={(e) => onChangeRow(index, { SLIn: e.target.value })}
                />
            ),
        },
        {
            title: "Nơi in",
            width: 120,
            render: (_, row, index) => (
                <Input
                    size="small"
                    value={row.NoiIn}
                    onChange={(e) => onChangeRow(index, { NoiIn: e.target.value })}
                />
            ),
        },
        {
            title: "Ngày nhập kho",
            width: 130,
            render: (_, row, index) => (
                <Input
                    size="small"
                    placeholder="DD/MM/YYYY"
                    value={row.NgayNhapKho}
                    onChange={(e) => onChangeRow(index, { NgayNhapKho: e.target.value })}
                />
            ),
        },
        {
            title: "Tình hình biên soạn",
            width: 160,
            render: (_, row, index) => (
                <Input
                    size="small"
                    value={row.TinhHinhBienSoan}
                    onChange={(e) => onChangeRow(index, { TinhHinhBienSoan: e.target.value })}
                />
            ),
        },
        {
            title: "Ghi chú",
            width: 140,
            render: (_, row, index) => (
                <Input
                    size="small"
                    value={row.GhiChu}
                    onChange={(e) => onChangeRow(index, { GhiChu: e.target.value })}
                />
            ),
        },
        {
            title: "Chức năng",
            width: 88,
            align: "center",
            fixed: "right",
            render: (_, __, index) => (
                <Button type="link" danger size="small" onClick={() => onRemoveRow(index)}>
                    Xóa
                </Button>
            ),
        },
    ];

    return (
        <Table<QDInSachDong>
            size="small"
            bordered
            pagination={false}
            rowKey="key"
            scroll={{ x: "max-content" }}
            columns={sachColumns}
            dataSource={dataSource}
            locale={{ emptyText: "Chưa có dòng sách — bấm “+ Thêm thông tin sách”" }}
        />
    );
});

TableInfoBook.displayName = "TableInfoBook";


interface ViewStoreQDInProps {}

function emptyQDInForm(): Partial<QDIn> {
    const y = new Date().getFullYear();
    return {
        id: 0,
        SoQD: "",
        NgayQD: new Date(),
        NamKeHoach: String(y),
        TieuDe: "",
        CanCu: "",
        MaDonviQD: "",
        TenDonViQD: "",
        DiaDanh: "",
        TenNguoiKi: "",
        // ChucVu: "",
        NoiNhan: "",
        HTXB: 0,
        ID_DVQD_VMS: 0,
        ID_DV_QD: 0,
        ID_MangSachQDIN: 0,
        ID_NguoiKi: 0,
        ID_VMS: "",
        TenDonVi_VMS: "",
        UserName_VMS: "",
        SoQDTuTang: 0,
    };
}

export const ViewStoreQDIn = React.memo((_props: ViewStoreQDInProps) => {
    const [form, setForm] = useState<Partial<QDIn>>(emptyQDInForm);
    const [saving, setSaving] = useState(false);
    const [sachRows, setSachRows] = useState<QDInSachDong[]>([]);
    const [modalSachOpen, setModalSachOpen] = useState(false);

    const setField = useCallback(<K extends keyof QDIn>(key: K, value: Partial<QDIn>[K] | undefined) => {
        setForm((prev) => ({ ...prev, [key]: value }));
    }, []);

    const onChangeSachRow = useCallback((index: number, patch: Partial<QDInSachDong>) => {
        setSachRows((rows) => rows.map((r, i) => (i === index ? { ...r, ...patch } : r)));
    }, []);

    const onRemoveSachRow = useCallback((index: number) => {
        setSachRows((rows) => rows.filter((_, i) => i !== index));
    }, []);

    const onChooseSachFromModal = useCallback((row: QDInSachDong) => {
        setSachRows((rows) => [...rows, row]);
    }, []);

    const handleSave = useCallback(() => {
        setSaving(true);
        const payload: Record<string, unknown> = {
            id: form.id ?? 0,
            SoQD: form.SoQD ?? "",
            NgayQD: toIso8601UtcOffset(form.NgayQD),
            NamKeHoach: form.NamKeHoach ?? "",
            TieuDe: form.TieuDe ?? "",
            CanCu: form.CanCu ?? "",
            MaDonviQD: form.MaDonviQD ?? "",
            TenDonViQD: form.TenDonViQD ?? "",
            DiaDanh: form.DiaDanh ?? "",
            TenNguoiKi: form.TenNguoiKi ?? "",
            // ChucVu: form.ChucVu ?? "",
            NoiNhan: form.NoiNhan ?? "",
            HTXB: form.HTXB ?? 0,
            ID_DVQD_VMS: form.ID_DVQD_VMS ?? 0,
            ID_DV_QD: form.ID_DV_QD ?? 0,
            ID_MangSachQDIN: form.ID_MangSachQDIN ?? 0,
            ID_NguoiKi: form.ID_NguoiKi ?? 0,
            ID_VMS: form.ID_VMS ?? "",
            TenDonVi_VMS: form.TenDonVi_VMS ?? "",
            UserName_VMS: form.UserName_VMS ?? "",
            SoQDTuTang: form.SoQDTuTang ?? 0,
        };
        QDInApi.upsert(payload as Partial<QDIn>)
            .then((res) => {
                if (res?.id) {
                    setForm((prev) => ({ ...prev, id: res.id }));
                }
            })
            .finally(() => setSaving(false));
    }, [form]);

    return (
        <div className="px-2 py-2">
            <Typography.Title level={4} className="mb-3 border-bottom pb-2">
                Quyết định in
            </Typography.Title>

            <Space className="mb-3" wrap>
                <Button type="primary" size="small" loading={saving} onClick={handleSave}>
                    Lưu
                </Button>
                <Button size="small" onClick={() => {}}>
                    Sao chép
                </Button>
                <Button size="small" onClick={() => {}}>
                    Làm mới
                </Button>
            </Space>

            <FormFieldQDIn form={form} setField={setField} />

            <div className="mb-2">
                <Button type="link" className="text-success p-0 fw-semibold" onClick={() => setModalSachOpen(true)}>
                    + Thêm thông tin sách
                </Button>
            </div>

            <TableInfoBook dataSource={sachRows} onChangeRow={onChangeSachRow} onRemoveRow={onRemoveSachRow} />

            {/* <ModalChooseBook visible={modalSachOpen} onClose={() => setModalSachOpen(false)} onChoose={onChooseSachFromModal} /> */}
        </div>
    );
});

ViewStoreQDIn.displayName = "ViewStoreQDIn";

const ROOT_ID = "root-store-qd-in";
const bladeProps: ViewStoreQDInProps = {};
mountReactComponentOnReady(ROOT_ID, <ViewStoreQDIn {...bladeProps} />);
