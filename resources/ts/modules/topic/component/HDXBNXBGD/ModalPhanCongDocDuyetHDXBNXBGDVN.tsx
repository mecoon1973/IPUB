import React, { useCallback, useEffect, useMemo, useState } from "react";
import { Button, Input, Modal, Select, Table, type TableProps } from "antd";
import { ReloadOutlined, SaveOutlined } from "@ant-design/icons";
import { HDXBNXBGDVNApi } from "../../api/HDXBNXBGDVNApi";
import { DOC_DUYET_KET_LUAN_OPTIONS, NX_CANBO_DETAI_DUYET } from "../../constants/hdxbNxbgdvn";
import { useManageHDXBNXBGDVNStore } from "../../store/HDXBNXBGDVN/manageHDXBNXBGDVN";
import type { HDXBNXBGDVNDocDuyetRow } from "../../type";

const { TextArea } = Input;

interface ModalPhanCongDocDuyetHDXBNXBGDVNProps {
    onSuccess?: () => void;
}

function renderThongTinDeTai(record: HDXBNXBGDVNDocDuyetRow) {
    return (
        <div className="text-primary small lh-sm">
            <div>
                <span className="fw-semibold">Tên đề tài:</span> {record.TenDeTai || "-"}
            </div>
            <div>
                <span className="fw-semibold">Tác giả:</span> {record.TacGia || "-"}
            </div>
            <div>
                <span className="fw-semibold">Khổ sách:</span> {record.KhoSach || "-"}
            </div>
            <div>
                <span className="fw-semibold">Số trang:</span> {record.SoTrang > 0 ? record.SoTrang : "-"}
            </div>
        </div>
    );
}

function ModalPhanCongDocDuyetHDXBNXBGDVN({ onSuccess }: ModalPhanCongDocDuyetHDXBNXBGDVNProps) {
    const activeModal = useManageHDXBNXBGDVNStore((state) => state.activeModal);
    const setActiveModal = useManageHDXBNXBGDVNStore((state) => state.setActiveModal);
    const selectedRowKeys = useManageHDXBNXBGDVNStore((state) => state.selectedRowKeys);

    const [rows, setRows] = useState<HDXBNXBGDVNDocDuyetRow[]>([]);
    const [initialRows, setInitialRows] = useState<HDXBNXBGDVNDocDuyetRow[]>([]);
    const [isLoading, setIsLoading] = useState(false);
    const [isSubmitting, setIsSubmitting] = useState(false);

    const open = activeModal === "docDuyet";

    const loadData = useCallback(async (ids: number[]) => {
        if (ids.length === 0) {
            setRows([]);
            setInitialRows([]);
            return;
        }

        setIsLoading(true);
        const list = await HDXBNXBGDVNApi.getListDocDuyet(ids);
        const normalized = list.map((row) => ({
            ...row,
            YKienNhanXet: row.YKienNhanXet ?? "",
            ThongTinLienQuan: row.ThongTinLienQuan ?? "",
            Duyet: row.Duyet || NX_CANBO_DETAI_DUYET.DUYET,
        }));
        setRows(normalized);
        setInitialRows(normalized);
        setIsLoading(false);
    }, []);

    useEffect(() => {
        if (!open) {
            setRows([]);
            setInitialRows([]);
            setIsSubmitting(false);
            return;
        }

        const ids = selectedRowKeys.map((key) => Number(key)).filter((id) => id > 0);
        loadData(ids);
    }, [loadData, open, selectedRowKeys]);

    const handleClose = useCallback(() => {
        setActiveModal(null);
    }, [setActiveModal]);

    const updateRow = useCallback((id: number, patch: Partial<HDXBNXBGDVNDocDuyetRow>) => {
        setRows((prev) => prev.map((row) => (row.id === id ? { ...row, ...patch } : row)));
    }, []);

    const handleSave = useCallback(
        async (closeAfterSave = false) => {
            if (rows.length === 0) {
                window._toastbox("Không có đề tài để lưu", "danger");
                return;
            }

            setIsSubmitting(true);
            const ok = await HDXBNXBGDVNApi.luuDocDuyet(rows);
            setIsSubmitting(false);

            if (!ok) {
                return;
            }

            window._toastbox("Lưu đọc duyệt thành công", "success");
            if (closeAfterSave) {
                handleClose();
                onSuccess?.();
                return;
            }

            setInitialRows(rows);
            onSuccess?.();
        },
        [handleClose, onSuccess, rows],
    );

    const handleRefresh = useCallback(() => {
        setRows(initialRows);
    }, [initialRows]);

    const columns: TableProps<HDXBNXBGDVNDocDuyetRow>["columns"] = useMemo(
        () => [
            {
                title: "STT",
                key: "stt",
                width: 56,
                render: (_v, _r, i) => i + 1,
            },
            {
                title: "THÔNG TIN ĐỀ TÀI",
                key: "ThongTinDeTai",
                width: 280,
                render: (_v, record) => renderThongTinDeTai(record),
            },
            {
                title: "Ý KIẾN NHẬN XÉT",
                key: "YKienNhanXet",
                width: 240,
                render: (_v, record) => (
                    <TextArea
                        rows={5}
                        value={record.YKienNhanXet}
                        onChange={(e) => updateRow(record.id, { YKienNhanXet: e.target.value })}
                    />
                ),
            },
            {
                title: "THÔNG TIN LIÊN QUAN",
                key: "ThongTinLienQuan",
                width: 240,
                render: (_v, record) => (
                    <TextArea
                        rows={5}
                        value={record.ThongTinLienQuan}
                        onChange={(e) => updateRow(record.id, { ThongTinLienQuan: e.target.value })}
                    />
                ),
            },
            {
                title: "KẾT LUẬN",
                key: "Duyet",
                width: 160,
                render: (_v, record) => (
                    <Select
                        style={{ width: "100%" }}
                        value={record.Duyet}
                        options={DOC_DUYET_KET_LUAN_OPTIONS.map((opt) => ({
                            value: opt.value,
                            label: opt.label,
                        }))}
                        onChange={(value) => updateRow(record.id, { Duyet: value })}
                    />
                ),
            },
        ],
        [updateRow],
    );

    return (
        <Modal
            title="ĐỌC DUYỆT ĐỀ TÀI"
            open={open}
            onCancel={handleClose}
            width="95%"
            style={{ maxWidth: 1200 }}
            footer={null}
            destroyOnClose
        >
            <div className="d-flex align-items-center gap-3 mb-3 border-bottom pb-2">
                <Button
                    type="link"
                    className="p-0 d-inline-flex align-items-center gap-1"
                    icon={<SaveOutlined />}
                    loading={isSubmitting}
                    onClick={() => handleSave(false)}
                >
                    Lưu
                </Button>
                <Button
                    type="link"
                    className="p-0 d-inline-flex align-items-center gap-1"
                    icon={<SaveOutlined />}
                    loading={isSubmitting}
                    onClick={() => handleSave(true)}
                >
                    Lưu và đóng
                </Button>
                <Button
                    type="link"
                    className="p-0 d-inline-flex align-items-center gap-1"
                    icon={<ReloadOutlined />}
                    loading={isLoading}
                    onClick={handleRefresh}
                >
                    Làm mới
                </Button>
            </div>

            <Table<HDXBNXBGDVNDocDuyetRow>
                rowKey={(record) => String(record.id)}
                columns={columns}
                dataSource={rows}
                loading={isLoading}
                pagination={false}
                size="small"
                scroll={{ x: 980, y: 420 }}
            />
        </Modal>
    );
}

export default React.memo(ModalPhanCongDocDuyetHDXBNXBGDVN);
