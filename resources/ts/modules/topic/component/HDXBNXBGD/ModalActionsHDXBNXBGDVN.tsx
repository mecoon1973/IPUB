import React, { useCallback, useMemo, useState } from "react";
import { Button, Modal, Table, type TableProps } from "antd";
import { useManageHDXBNXBGDVNStore, type HDXBNXBGDVNModalKey } from "../../store/HDXBNXBGDVN/manageHDXBNXBGDVN";
import type { HDXBNXBGDVN } from "../../type";

interface ModalConfig {
    key: HDXBNXBGDVNModalKey;
    title: string;
    description: string;
    confirmLabel: string;
    onConfirm?: (items: HDXBNXBGDVN[]) => Promise<boolean>;
}

function ModalActionsHDXBNXBGDVN({ onSuccess }: { onSuccess?: () => void }) {
    const activeModal = useManageHDXBNXBGDVNStore((state) => state.activeModal);
    const setActiveModal = useManageHDXBNXBGDVNStore((state) => state.setActiveModal);
    const listHDXBNXBGD = useManageHDXBNXBGDVNStore((state) => state.listHDXBNXBGD);
    const selectedRowKeys = useManageHDXBNXBGDVNStore((state) => state.selectedRowKeys);
    const [isSubmitting, setIsSubmitting] = useState(false);

    const selectedItems = useMemo(
        () => listHDXBNXBGD.filter((item) => selectedRowKeys.includes(String(item.id))),
        [listHDXBNXBGD, selectedRowKeys],
    );

    const modalConfig = useMemo<ModalConfig | null>(() => {
        switch (activeModal) {
            case "docDuyet":
                return {
                    key: "docDuyet",
                    title: "ĐỌC DUYỆT ĐỀ TÀI",
                    description: "Xác nhận chuyển các đề tài đã chọn sang bước đọc duyệt.",
                    confirmLabel: "Xác nhận",
                    onConfirm: async () => {
                        window._toastbox("Chức năng đọc duyệt đang được hoàn thiện", "info");
                        return true;
                    },
                };
            case "inPhieuTrinh":
                return {
                    key: "inPhieuTrinh",
                    title: "IN PHIẾU TRÌNH HĐXB NXBGDVN",
                    description: "In phiếu trình cho các đề tài đã chọn.",
                    confirmLabel: "In phiếu",
                    onConfirm: async () => {
                        window._toastbox("Chức năng in phiếu trình đang được hoàn thiện", "info");
                        return true;
                    },
                };
            case "pheDuyetDiIn":
                return {
                    key: "pheDuyetDiIn",
                    title: "PHÊ DUYỆT ĐI IN",
                    description: "Xác nhận phê duyệt đi in cho các đề tài đã chọn.",
                    confirmLabel: "Phê duyệt",
                    onConfirm: async () => {
                        window._toastbox("Chức năng phê duyệt đi in đang được hoàn thiện", "info");
                        return true;
                    },
                };
            default:
                return null;
        }
    }, [activeModal]);

    const handleClose = useCallback(() => {
        setActiveModal(null);
    }, [setActiveModal]);

    const handleSubmit = useCallback(async () => {
        if (!modalConfig || selectedItems.length === 0) return;

        setIsSubmitting(true);
        const ok = modalConfig.onConfirm ? await modalConfig.onConfirm(selectedItems) : true;
        setIsSubmitting(false);

        if (!ok) return;

        handleClose();
        onSuccess?.();
    }, [handleClose, modalConfig, onSuccess, selectedItems]);

    const columns: TableProps<HDXBNXBGDVN>["columns"] = [
        { title: "TT", key: "stt", width: 56, render: (_v, _r, i) => i + 1 },
        { title: "TÊN ĐỀ TÀI", dataIndex: "TenDeTai", key: "TenDeTai" },
        { title: "NGƯỜI ĐỌC DUYỆT", dataIndex: "NguoiDocDuyet", key: "NguoiDocDuyet", render: (v, r) => v || r.BienTapVien || "-" },
        { title: "ĐƠN VỊ TỔ CHỨC BẢN THẢO", dataIndex: "TenDonVi", key: "TenDonVi" },
    ];

    if (!modalConfig) return null;

    return (
        <Modal
            title={modalConfig.title}
            open={activeModal === modalConfig.key}
            onCancel={handleClose}
            width={900}
            footer={[
                <Button key="cancel" onClick={handleClose}>
                    Hủy
                </Button>,
                <Button key="submit" type="primary" loading={isSubmitting} onClick={handleSubmit}>
                    {modalConfig.confirmLabel}
                </Button>,
            ]}
        >
            <p className="text-muted mb-3">{modalConfig.description}</p>
            <Table<HDXBNXBGDVN>
                rowKey={(record) => String(record.id)}
                columns={columns}
                dataSource={selectedItems}
                pagination={false}
                size="small"
                scroll={{ y: 280 }}
            />
        </Modal>
    );
}

export default React.memo(ModalActionsHDXBNXBGDVN);
