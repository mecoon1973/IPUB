import React, { useCallback, useState } from "react";
import { Button, Modal } from "antd";
import { useManagePhieuDkDetaiStore } from "../../store/PhieuDkDetai/managePhieuDkDetaiStore";
import { PhieuDkDetaiApi } from "../../api/PhieuDkDetaiApi";
import type { PhieuDkDetai } from "../../type";

interface ModalXetDuyetNxbgdvnComponentProps {
    onSuccess?: () => void;
}

export const ModalXetDuyetNxbgdvnComponent = React.memo((props: ModalXetDuyetNxbgdvnComponentProps) => {
    const { onSuccess } = props;
    const showModalXetDuyetNxbgdvn = useManagePhieuDkDetaiStore((state) => state.showModalXetDuyetNxbgdvn);
    const setShowModalXetDuyetNxbgdvn = useManagePhieuDkDetaiStore((state) => state.setShowModalXetDuyetNxbgdvn);
    const phieuDkDetaiContext = useManagePhieuDkDetaiStore((state) => state.PhieuDkDetaiContext);
    const setListPhieuDkDetai = useManagePhieuDkDetaiStore((state) => state.setListPhieuDkDetai);
    const [isSubmitting, setIsSubmitting] = useState(false);

    const handleClose = useCallback(() => {
        setShowModalXetDuyetNxbgdvn(false);
    }, [setShowModalXetDuyetNxbgdvn]);

    const handleSubmit = useCallback(async () => {
        if (!phieuDkDetaiContext?.id) {
            window._toastbox("Không tìm thấy đề tài cần xét duyệt", "danger");
            return;
        }

        setIsSubmitting(true);
        const result = await PhieuDkDetaiApi.xetDuyetNxbgdvn(phieuDkDetaiContext.id);
        setIsSubmitting(false);

        if (!result) {
            return;
        }

        window._toastbox("Xét duyệt NXBGDVN thành công", "success");
        setListPhieuDkDetai((prev: PhieuDkDetai[]) =>
            prev.map((item) => (item.id === result.id ? { ...item, ...result } : item)),
        );
        handleClose();
        onSuccess?.();
    }, [phieuDkDetaiContext, setListPhieuDkDetai, handleClose, onSuccess]);

    if (!phieuDkDetaiContext) {
        return null;
    }

    return (
        <Modal
            title="NXBGDVN XÉT DUYỆT"
            open={showModalXetDuyetNxbgdvn}
            onCancel={handleClose}
            width={640}
            footer={[
                <Button key="cancel" onClick={handleClose}>
                    Hủy
                </Button>,
                <Button key="submit" type="primary" loading={isSubmitting} onClick={handleSubmit}>
                    Xác nhận
                </Button>,
            ]}
        >
            <div className="mb-3">
                <span className="text-muted">Tên đề tài: </span>
                <span className="fw-semibold">{phieuDkDetaiContext.TenDeTai}</span>
            </div>
            <p className="text-muted mb-0">
                Xác nhận chuyển đề tài sang trạng thái HĐXB NXBGDVN đang xét duyệt. Người duyệt là tài khoản đang đăng nhập.
            </p>
        </Modal>
    );
});
