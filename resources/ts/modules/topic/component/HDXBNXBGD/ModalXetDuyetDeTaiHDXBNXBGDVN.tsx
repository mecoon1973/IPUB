import React, { useCallback, useEffect, useMemo, useState } from "react";
import { Button, Checkbox, Input, Modal, Select, Table, type TableProps } from "antd";
import { ReloadOutlined, SaveOutlined, SearchOutlined } from "@ant-design/icons";
import dayjs from "dayjs";
import type { Dayjs } from "dayjs";
import DatePickerAntd from "../../../core/utils/DatePicker";
import { convertValueToDayjs, formatDateToString } from "../../../core/utils/helpersDayjs";
import { ComponentSelectAntObject } from "../../../page/component/componentSelectAnt";
import type { DonVi } from "../../../user/type";
import { HDXBNXBGDVNApi } from "../../api/HDXBNXBGDVNApi";
import { NX_CANBO_DETAI_DUYET_OPTIONS } from "../../constants/hdxbNxbgdvn";
import { useManageHDXBNXBGDVNStore } from "../../store/HDXBNXBGDVN/manageHDXBNXBGDVN";
import type { FilterXetDuyetHDXBNXBGDVN, HDXBNXBGDVNXetDuyetRow } from "../../type";

const { RangePicker } = DatePickerAntd;
const { TextArea } = Input;

function createDefaultFilter(): FilterXetDuyetHDXBNXBGDVN {
    const denNgay = dayjs();
    const tuNgay = denNgay.subtract(30, "day");
    return {
        ID_DonVi: 0,
        TuNgay: tuNgay.format("YYYY-MM-DD"),
        DenNgay: denNgay.format("YYYY-MM-DD"),
    };
}

interface ModalXetDuyetDeTaiHDXBNXBGDVNProps {
    listDonvi: DonVi[];
    onSuccess?: () => void;
}

function ModalXetDuyetDeTaiHDXBNXBGDVN({ listDonvi, onSuccess }: ModalXetDuyetDeTaiHDXBNXBGDVNProps) {
    const activeModal = useManageHDXBNXBGDVNStore((state) => state.activeModal);
    const setActiveModal = useManageHDXBNXBGDVNStore((state) => state.setActiveModal);
    const selectedRowKeys = useManageHDXBNXBGDVNStore((state) => state.selectedRowKeys);

    const [rows, setRows] = useState<HDXBNXBGDVNXetDuyetRow[]>([]);
    const [filter, setFilter] = useState<FilterXetDuyetHDXBNXBGDVN>(createDefaultFilter);
    const [isLoading, setIsLoading] = useState(false);
    const [isSubmitting, setIsSubmitting] = useState(false);
    const [restrictIds, setRestrictIds] = useState<number[]>([]);
    const [donViWarning, setDonViWarning] = useState(false);

    const open = activeModal === "xetDuyetDeTai";

    const loadData = useCallback(async (nextFilter: FilterXetDuyetHDXBNXBGDVN, ids?: number[]) => {
        setIsLoading(true);
        const payload: FilterXetDuyetHDXBNXBGDVN = { ...nextFilter };
        if (ids && ids.length > 0) {
            payload.ids = ids;
        }
        const list = await HDXBNXBGDVNApi.getListXetDuyet(payload);
        setRows(list);
        setIsLoading(false);
    }, []);

    useEffect(() => {
        if (!open) {
            setRows([]);
            setFilter(createDefaultFilter());
            setRestrictIds([]);
            setIsSubmitting(false);
            setDonViWarning(false);
            return;
        }

        const ids = selectedRowKeys.map((key) => Number(key)).filter((id) => id > 0);
        setRestrictIds(ids);
        setFilter(createDefaultFilter());
    }, [open, selectedRowKeys]);

    const handleClose = useCallback(() => {
        setActiveModal(null);
    }, [setActiveModal]);

    const handleSearch = useCallback(() => {
        if (!filter.ID_DonVi || filter.ID_DonVi <= 0) {
            setDonViWarning(true);
            window._toastbox("Vui lòng chọn đơn vị tổ chức bản thảo", "danger");
            return;
        }
        setDonViWarning(false);
        loadData(filter, restrictIds.length > 0 ? restrictIds : undefined);
    }, [filter, loadData, restrictIds]);

    const handleRefresh = useCallback(() => {
        setFilter(createDefaultFilter());
        setRestrictIds([]);
        setRows([]);
        setDonViWarning(false);
    }, []);

    const updateRow = useCallback((id: number, patch: Partial<HDXBNXBGDVNXetDuyetRow>) => {
        setRows((prev) => prev.map((row) => (row.id === id ? { ...row, ...patch } : row)));
    }, []);

    const handleSave = useCallback(async () => {
        if (rows.length === 0) {
            window._toastbox("Không có đề tài để lưu", "danger");
            return;
        }

        setIsSubmitting(true);
        const ok = await HDXBNXBGDVNApi.luuXetDuyetDeTai(rows);
        setIsSubmitting(false);

        if (!ok) return;

        window._toastbox("Lưu xét duyệt đề tài thành công", "success");
        handleClose();
        onSuccess?.();
    }, [handleClose, onSuccess, rows]);

    const handleDateChange = useCallback((dates: [Dayjs | null, Dayjs | null] | null) => {
        if (!dates || !dates[0] || !dates[1]) {
            setFilter((prev) => ({ ...prev, TuNgay: null, DenNgay: null }));
            return;
        }
        setFilter((prev) => ({
            ...prev,
            TuNgay: formatDateToString(dates[0]!.toDate(), "YYYY-MM-DD"),
            DenNgay: formatDateToString(dates[1]!.toDate(), "YYYY-MM-DD"),
        }));
    }, []);

    const columns: TableProps<HDXBNXBGDVNXetDuyetRow>["columns"] = useMemo(
        () => [
            { title: "STT", key: "stt", width: 56, render: (_v, _r, i) => i + 1 },
            {
                title: "TÊN ĐỀ TÀI",
                dataIndex: "TenDeTai",
                key: "TenDeTai",
                width: 200,
                render: (value: string) => <span className="text-primary">{value}</span>,
            },
            {
                title: "Ý KIẾN ĐỌC DUYỆT",
                key: "YKienDocDuyet",
                width: 220,
                render: (_v, record) => (
                    <TextArea
                        rows={3}
                        value={record.YKienDocDuyet}
                        onChange={(e) => updateRow(record.id, { YKienDocDuyet: e.target.value })}
                    />
                ),
            },
            {
                title: "Ý KIẾN HĐXB",
                key: "YKienHDXB",
                width: 220,
                render: (_v, record) => (
                    <TextArea
                        rows={3}
                        value={record.YKienHDXB}
                        onChange={(e) => updateRow(record.id, { YKienHDXB: e.target.value })}
                    />
                ),
            },
            {
                title: "XÉT DUYỆT",
                key: "Duyet",
                width: 140,
                render: (_v, record) => (
                    <Select
                        style={{ width: "100%" }}
                        value={record.Duyet}
                        options={NX_CANBO_DETAI_DUYET_OPTIONS.map((opt) => ({ value: opt.value, label: opt.label }))}
                        onChange={(value) => updateRow(record.id, { Duyet: value })}
                    />
                ),
            },
            {
                title: "ĐỌC KIỂM ĐỊNH",
                key: "YeuCauDocKiemDinh",
                width: 120,
                align: "center",
                render: (_v, record) => (
                    <Checkbox
                        checked={record.YeuCauDocKiemDinh}
                        onChange={(e) => updateRow(record.id, { YeuCauDocKiemDinh: e.target.checked })}
                    />
                ),
            },
        ],
        [updateRow],
    );

    return (
        <Modal
            title="XÉT DUYỆT ĐỀ TÀI"
            open={open}
            onCancel={handleClose}
            width="95%"
            style={{ maxWidth: 1200 }}
            footer={[
                <Button key="cancel" onClick={handleClose}>
                    Hủy
                </Button>,
                <Button key="save" type="primary" loading={isSubmitting} onClick={handleSave}>
                    Lưu
                </Button>,
            ]}
        >
            <div className="row g-2 align-items-end mb-3">
                <div className="col-md-3">
                    <label className="form-label mb-1 fw-semibold">Từ ngày — Đến ngày</label>
                    <RangePicker
                        style={{ width: "100%" }}
                        size="middle"
                        format="DD/MM/YYYY"
                        placeholder={["Từ ngày", "Đến ngày"]}
                        allowClear
                        value={[convertValueToDayjs(filter.TuNgay), convertValueToDayjs(filter.DenNgay)]}
                        onChange={handleDateChange}
                    />
                </div>
                <div className="col-md-5">
                    <label className="form-label mb-1 fw-semibold">
                        Đơn vị tổ chức bản thảo <span className="text-danger">*</span>
                    </label>
                    <ComponentSelectAntObject
                        listData={listDonvi}
                        keyValue="id"
                        labelValue="TenDonVi"
                        onCustomLabel={(item) =>
                            item.MaDonVi ? `${item.MaDonVi} — ${item.TenDonVi}` : item.TenDonVi
                        }
                        value={filter.ID_DonVi && filter.ID_DonVi > 0 ? filter.ID_DonVi : ""}
                        onChange={(value) => {
                            setDonViWarning(false);
                            setFilter((prev) => ({
                                ...prev,
                                ID_DonVi: value ? Number(value) : 0,
                            }));
                        }}
                        placeholder="— Chọn đơn vị tổ chức bản thảo —"
                        style={{ width: "100%" }}
                        size="middle"
                        showSearch
                        warning={donViWarning}
                        optionFilterProp="label"
                        filterOption={(input, option) =>
                            String(option?.label ?? "").toLowerCase().includes(input.toLowerCase())
                        }
                    />
                    {donViWarning && (
                        <div className="small text-danger mt-1">Vui lòng chọn đơn vị tổ chức bản thảo</div>
                    )}
                </div>
                <div className="col-md-4 d-flex flex-wrap gap-2 align-items-end">
                    <Button type="primary" icon={<SearchOutlined />} loading={isLoading} onClick={handleSearch}>
                        Tìm kiếm
                    </Button>
                    <Button icon={<SaveOutlined />} loading={isSubmitting} onClick={handleSave}>
                        Lưu
                    </Button>
                    <Button icon={<ReloadOutlined />} loading={isLoading} onClick={handleRefresh}>
                        Làm mới
                    </Button>
                </div>
            </div>

            <Table<HDXBNXBGDVNXetDuyetRow>
                rowKey={(record) => String(record.id)}
                columns={columns}
                dataSource={rows}
                loading={isLoading}
                pagination={false}
                size="small"
                scroll={{ x: 1000, y: 400 }}
            />
        </Modal>
    );
}

export default React.memo(ModalXetDuyetDeTaiHDXBNXBGDVN);
