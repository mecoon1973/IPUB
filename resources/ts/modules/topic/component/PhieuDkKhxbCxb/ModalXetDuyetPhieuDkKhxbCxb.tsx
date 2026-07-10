import React, { useCallback, useEffect, useMemo, useState } from "react";
import { Button, Modal, Select, Space, Table, Typography, type TableProps } from "antd";
import { ReloadOutlined, SaveOutlined } from "@ant-design/icons";
import { formatDateToString } from "../../../core/utils/helpersDayjs";
import { PhieuDkKhxbCxbApi } from "../../api/PhieuDkKhxbCxbApi";
import { buildTrangThaiOptionsForRow, normalizeMapTrangThai, normalizeTrangThaiXetDuyetCxb } from "../../constants/phieuDkKhxbCxb";
import { useManagePhieuDkKhxbCxbStore } from "../../store/PhieuDkKhxbCxb/managePhieuDkKhxbCxbStore";
import type { PhieuDkKhxbCxb, XetDuyetPhieuDkKhxbCxbRow } from "../../type";

interface ModalXetDuyetPhieuDkKhxbCxbProps {
    mapTrangThai: Record<number, string>;
    onSuccess?: () => void;
}

export const ModalXetDuyetPhieuDkKhxbCxb = React.memo(({ mapTrangThai, onSuccess }: ModalXetDuyetPhieuDkKhxbCxbProps) => {
    const open = useManagePhieuDkKhxbCxbStore((state) => state.showModalXetDuyet);
    const phieuContext = useManagePhieuDkKhxbCxbStore((state) => state.phieuXetDuyetContext);
    const setShow = useManagePhieuDkKhxbCxbStore((state) => state.setShowModalXetDuyet);

    const [phieu, setPhieu] = useState<PhieuDkKhxbCxb | null>(null);
    const [rows, setRows] = useState<XetDuyetPhieuDkKhxbCxbRow[]>([]);
    const [isLoading, setIsLoading] = useState(false);
    const [isSubmitting, setIsSubmitting] = useState(false);

    const normalizedMapTrangThai = useMemo(
        () => normalizeMapTrangThai(mapTrangThai),
        [mapTrangThai],
    );

    const loadData = useCallback(async (idPhieu: number) => {
        setIsLoading(true);
        const res = await PhieuDkKhxbCxbApi.getXetDuyet(idPhieu);
        if (res) {
            setPhieu(res.phieu);
            setRows(
                res.listDeTai.map((row) => ({
                    ...row,
                    TrangThai: normalizeTrangThaiXetDuyetCxb(row.TrangThai),
                })),
            );
        } else {
            setPhieu(null);
            setRows([]);
        }
        setIsLoading(false);
    }, []);

    useEffect(() => {
        if (!open || !phieuContext?.id) {
            setPhieu(null);
            setRows([]);
            setIsSubmitting(false);
            return;
        }

        void loadData(phieuContext.id);
    }, [loadData, open, phieuContext?.id]);

    const handleClose = useCallback(() => setShow(false), [setShow]);

    const handleRefresh = useCallback(() => {
        if (!phieuContext?.id) {
            return;
        }
        void loadData(phieuContext.id);
    }, [loadData, phieuContext?.id]);

    const updateRow = useCallback((id: number, trangThai: number) => {
        setRows((prev) => prev.map((row) => (row.id === id ? { ...row, TrangThai: trangThai } : row)));
    }, []);

    const handleSave = useCallback(async () => {
        if (!phieuContext?.id) {
            window._toastbox("Không xác định được phiếu trình CXB", "danger");
            return;
        }
        if (rows.length === 0) {
            window._toastbox("Phiếu chưa có đề tài để xét duyệt", "danger");
            return;
        }

        setIsSubmitting(true);
        const res = await PhieuDkKhxbCxbApi.luuXetDuyet({
            idPhieu: phieuContext.id,
            items: rows.map((row) => ({
                idDeTai: row.id,
                TrangThai: row.TrangThai,
            })),
        });
        setIsSubmitting(false);

        if (!res) {
            return;
        }

        window._toastbox(`Lưu xét duyệt ${res.count} đề tài thành công`, "success");
        handleClose();
        onSuccess?.();
    }, [handleClose, onSuccess, phieuContext?.id, rows]);

    const columns: TableProps<XetDuyetPhieuDkKhxbCxbRow>["columns"] = useMemo(
        () => [
            { title: "STT", key: "stt", width: 56, render: (_v, _r, i) => i + 1 },
            { title: "Tên đề tài", dataIndex: "TenDeTai", key: "TenDeTai" },
            { title: "Đơn vị ĐK", dataIndex: "TenDonVi", key: "TenDonVi", width: 260 },
            {
                title: "Trạng thái",
                key: "TrangThai",
                width: 260,
                render: (_, record) => {
                    const trangThai = normalizeTrangThaiXetDuyetCxb(record.TrangThai);
                    return (
                        <Select
                            size="small"
                            className="w-100"
                            value={trangThai}
                            options={buildTrangThaiOptionsForRow(trangThai, normalizedMapTrangThai)}
                            onChange={(value) => updateRow(record.id, value)}
                        />
                    );
                },
            },
        ],
        [normalizedMapTrangThai, updateRow],
    );

    return (
        <Modal
            title="Xét duyệt phiếu trình CXB"
            open={open}
            onCancel={handleClose}
            width={980}
            footer={null}
            destroyOnClose
        >
            <div className="d-flex justify-content-end mb-3">
                <Space>
                    <Button
                        type="primary"
                        icon={<SaveOutlined />}
                        loading={isSubmitting}
                        onClick={handleSave}
                    >
                        Lưu
                    </Button>
                    <Button icon={<ReloadOutlined />} onClick={handleRefresh} disabled={isLoading}>
                        Làm mới
                    </Button>
                </Space>
            </div>

            <Typography.Text strong className="d-block mb-2">
                1. Thông tin phiếu ĐKKH đề tài
            </Typography.Text>
            <div className="mb-3 ps-2">
                <div className="mb-1">
                    <span className="text-muted">Mã số phiếu: </span>
                    <span className="text-danger fw-semibold">{phieu?.MaSo ?? phieuContext?.MaSo ?? "-"}</span>
                </div>
                <div className="mb-1">
                    <span className="text-muted">Tiêu đề: </span>
                    <span>{phieu?.TieuDe ?? phieuContext?.TieuDe ?? "-"}</span>
                </div>
                <div>
                    <span className="text-muted">Ngày đăng ký: </span>
                    <span>{formatDateToString(phieu?.NgayDK ?? phieuContext?.NgayDK)}</span>
                </div>
            </div>

            <Typography.Text strong className="d-block mb-2">
                2. Thông tin xét duyệt đề tài thuộc phiếu
            </Typography.Text>
            <Table<XetDuyetPhieuDkKhxbCxbRow>
                rowKey={(record) => String(record.id)}
                columns={columns}
                dataSource={rows}
                loading={isLoading}
                pagination={false}
                size="small"
                scroll={{ y: 360 }}
                locale={{ emptyText: "Phiếu chưa có đề tài" }}
            />
        </Modal>
    );
});
