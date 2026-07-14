import React, { useCallback, useEffect, useMemo, useState } from "react";
import { Button, Input, Modal, Select, Table, type TableProps } from "antd";
import { ReloadOutlined, SaveOutlined } from "@ant-design/icons";
import { HDXBNXBGDVNApi } from "../../api/HDXBNXBGDVNApi";
import { PHE_DUYET_DI_IN_KET_LUAN_OPTIONS } from "../../constants/hdxbNxbgdvn";
import type { PheDuyetDiInLuuItem, PheDuyetDiInRow } from "../../type";

const { TextArea } = Input;

interface ModalPheDuyetDiInHDXBNXBGDVNProps {
    open: boolean;
    items: PheDuyetDiInRow[];
    onClose: () => void;
    onSuccess?: () => void;
}

function toKetLuanValue(xetDuyet?: boolean): number {
    return xetDuyet ? 1 : 0;
}

function fromKetLuanValue(value: number): boolean {
    return value === 1;
}

function mapRowsToFormState(items: PheDuyetDiInRow[]): PheDuyetDiInRow[] {
    return items.map((item) => ({
        ...item,
        YKienDocBanThao: item.YKienDocBanThao ?? "",
        XetDuyetBanThao: item.XetDuyetBanThao ?? item.DaPheDuyetDiIn ?? false,
    }));
}

function ModalPheDuyetDiInHDXBNXBGDVN({
    open,
    items,
    onClose,
    onSuccess,
}: ModalPheDuyetDiInHDXBNXBGDVNProps) {
    const [rows, setRows] = useState<PheDuyetDiInRow[]>([]);
    const [isSubmitting, setIsSubmitting] = useState(false);

    useEffect(() => {
        if (!open) {
            setRows([]);
            setIsSubmitting(false);
            return;
        }
        setRows(mapRowsToFormState(items));
    }, [open, items]);

    const updateRow = useCallback((id: number, patch: Partial<PheDuyetDiInRow>) => {
        setRows((prev) => prev.map((row) => (row.id === id ? { ...row, ...patch } : row)));
    }, []);

    const buildPayload = useCallback((): PheDuyetDiInLuuItem[] => {
        return rows.map((row) => ({
            id: row.id,
            YKienDocBanThao: row.YKienDocBanThao ?? "",
            XetDuyetBanThao: row.XetDuyetBanThao ?? false,
        }));
    }, [rows]);

    const handleSave = useCallback(async (closeAfterSave = false) => {
        if (rows.length === 0) {
            window._toastbox("Không có sách để lưu", "danger");
            return;
        }

        setIsSubmitting(true);
        const ok = await HDXBNXBGDVNApi.luuPheDuyetDiIn(buildPayload());
        setIsSubmitting(false);

        if (!ok) {
            return;
        }

        window._toastbox("Lưu phê duyệt đi in thành công", "success");
        if (closeAfterSave) {
            onClose();
            onSuccess?.();
            return;
        }
        onSuccess?.();
    }, [buildPayload, onClose, onSuccess, rows.length]);

    const handleRefresh = useCallback(() => {
        setRows(mapRowsToFormState(items));
    }, [items]);

    const columns: TableProps<PheDuyetDiInRow>["columns"] = useMemo(
        () => [
            {
                title: "STT",
                key: "stt",
                width: 56,
                render: (_v, _r, i) => <span className="text-warning fw-semibold">{i + 1}</span>,
            },
            {
                title: "MÃ SỐ",
                dataIndex: "MaSo",
                key: "MaSo",
                width: 140,
            },
            {
                title: "TÊN SÁCH",
                dataIndex: "TenSach",
                key: "TenSach",
                width: 260,
                ellipsis: true,
            },
            {
                title: "Ý KIẾN ĐỌC DUYỆT",
                key: "YKienDocBanThao",
                render: (_v, record) => (
                    <TextArea
                        rows={4}
                        value={record.YKienDocBanThao ?? ""}
                        onChange={(e) => updateRow(record.id, { YKienDocBanThao: e.target.value })}
                    />
                ),
            },
            {
                title: "KẾT LUẬN",
                key: "XetDuyetBanThao",
                width: 160,
                render: (_v, record) => (
                    <Select
                        style={{ width: "100%" }}
                        value={toKetLuanValue(record.XetDuyetBanThao)}
                        options={PHE_DUYET_DI_IN_KET_LUAN_OPTIONS.map((opt) => ({
                            value: opt.value,
                            label: opt.label,
                        }))}
                        onChange={(value) => updateRow(record.id, { XetDuyetBanThao: fromKetLuanValue(value) })}
                    />
                ),
            },
        ],
        [updateRow],
    );

    return (
        <Modal
            title="Danh sách bản thảo phê duyệt đi in"
            open={open}
            onCancel={onClose}
            width={1100}
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
                    onClick={handleRefresh}
                >
                    Làm mới
                </Button>
            </div>

            <Table<PheDuyetDiInRow>
                rowKey={(record) => String(record.id)}
                columns={columns}
                dataSource={rows}
                pagination={false}
                size="small"
                scroll={{ y: 420 }}
            />
        </Modal>
    );
}

export default React.memo(ModalPheDuyetDiInHDXBNXBGDVN);
