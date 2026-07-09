import React, { useCallback, useEffect, useState } from "react";
import { Button, Modal, Table, type TableProps } from "antd";
import { PhieuDkKhxbCxbApi } from "../../api/PhieuDkKhxbCxbApi";
import { useManagePhieuDkKhxbCxbStore } from "../../store/PhieuDkKhxbCxb/managePhieuDkKhxbCxbStore";
import type { PhieuDkDetai } from "../../type";

interface ModalKetChuyenThanhSachProps {
    onSuccess?: () => void;
}

export const ModalKetChuyenThanhSach = React.memo(({ onSuccess }: ModalKetChuyenThanhSachProps) => {
    const open = useManagePhieuDkKhxbCxbStore((state) => state.showModalKetChuyen);
    const phieu = useManagePhieuDkKhxbCxbStore((state) => state.phieuKetChuyenContext);
    const setShow = useManagePhieuDkKhxbCxbStore((state) => state.setShowModalKetChuyen);

    const [listDeTai, setListDeTai] = useState<PhieuDkDetai[]>([]);
    const [selectedIds, setSelectedIds] = useState<number[]>([]);
    const [isLoading, setIsLoading] = useState(false);
    const [isSubmitting, setIsSubmitting] = useState(false);

    useEffect(() => {
        if (!open || !phieu?.id) {
            return;
        }
        setSelectedIds([]);
        setIsSubmitting(false);
        setIsLoading(true);
        void PhieuDkKhxbCxbApi.getDetail(phieu.id).then((res) => {
            setListDeTai(res?.listDeTai ?? []);
            setIsLoading(false);
        });
    }, [open, phieu?.id]);

    const handleClose = useCallback(() => setShow(false), [setShow]);

    const handleSave = useCallback(async () => {
        if (!phieu?.id) {
            window._toastbox("Không xác định được phiếu trình CXB", "danger");
            return;
        }
        if (selectedIds.length === 0) {
            window._toastbox("Vui lòng chọn ít nhất một đề tài để kết chuyển", "danger");
            return;
        }

        setIsSubmitting(true);
        const res = await PhieuDkKhxbCxbApi.ketChuyenThanhSach({
            idPhieu: phieu.id,
            listIdDeTai: selectedIds,
        });
        setIsSubmitting(false);

        if (!res) {
            return;
        }

        window._toastbox(`Kết chuyển ${res.countKetChuyen} đề tài thành sách thành công`, "success");
        handleClose();
        onSuccess?.();
    }, [handleClose, onSuccess, phieu, selectedIds]);

    const columns: TableProps<PhieuDkDetai>["columns"] = [
        { title: "Mã số", dataIndex: "MaSo", key: "MaSo", width: 140 },
        { title: "Mã số CXB", dataIndex: "MaSoCXB", key: "MaSoCXB", width: 260 },
        { title: "Tên đề tài", dataIndex: "TenDeTai", key: "TenDeTai" },
    ];

    return (
        <Modal
            title="Kết chuyển đề tài thành sách"
            open={open}
            onCancel={handleClose}
            width={900}
            footer={[
                <Button key="save" type="primary" loading={isSubmitting} onClick={handleSave}>
                    Kết chuyển
                </Button>,
                <Button key="cancel" onClick={handleClose}>
                    Hủy
                </Button>,
            ]}
        >
            <Table<PhieuDkDetai>
                rowKey={(record) => String(record.id)}
                columns={columns}
                dataSource={listDeTai}
                loading={isLoading}
                pagination={false}
                size="small"
                scroll={{ y: 360 }}
                rowSelection={{
                    selectedRowKeys: selectedIds.map(String),
                    onChange: (keys) => setSelectedIds(keys.map((k) => Number(k))),
                }}
                locale={{ emptyText: "Phiếu chưa có đề tài" }}
            />
        </Modal>
    );
});
