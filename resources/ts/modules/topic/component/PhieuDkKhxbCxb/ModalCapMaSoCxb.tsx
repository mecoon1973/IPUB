import React, { useCallback, useEffect, useMemo, useState } from "react";
import { Button, Input, Modal, Space } from "antd";
import dayjs from "dayjs";
import DatePickerAntd from "../../../core/utils/DatePicker";
import { convertValueToDayjs } from "../../../core/utils/helpersDayjs";
import { PhieuDkKhxbCxbApi } from "../../api/PhieuDkKhxbCxbApi";
import { useManagePhieuDkKhxbCxbStore } from "../../store/PhieuDkKhxbCxb/managePhieuDkKhxbCxbStore";

interface ModalCapMaSoCxbProps {
    onSuccess?: () => void;
}

interface CapMaCxbForm {
    SoCvCxb: string;
    SoCvNxbgd: string;
    NgayCap: Date | null;
    NamCap: string;
    MaSoCxb: string;
}

function emptyForm(): CapMaCxbForm {
    return {
        SoCvCxb: "",
        SoCvNxbgd: "",
        NgayCap: dayjs().startOf("day").toDate(),
        NamCap: String(dayjs().year()),
        MaSoCxb: "",
    };
}

const LABEL_WIDTH = 340;

const FieldRow: React.FC<{ label: React.ReactNode; required?: boolean; children: React.ReactNode }> = ({
    label,
    required,
    children,
}) => (
    <div className="d-flex align-items-center gap-2 mb-3">
        <div style={{ width: LABEL_WIDTH, flexShrink: 0 }}>
            {label}
            {required ? <span className="text-danger"> (*)</span> : null}
        </div>
        <div style={{ flex: 1 }}>{children}</div>
    </div>
);

export const ModalCapMaSoCxb = React.memo(({ onSuccess }: ModalCapMaSoCxbProps) => {
    const open = useManagePhieuDkKhxbCxbStore((state) => state.showModalCapMaCxb);
    const phieu = useManagePhieuDkKhxbCxbStore((state) => state.phieuCapMaCxbContext);
    const setShow = useManagePhieuDkKhxbCxbStore((state) => state.setShowModalCapMaCxb);

    const [form, setForm] = useState<CapMaCxbForm>(emptyForm);
    const [isSubmitting, setIsSubmitting] = useState(false);

    useEffect(() => {
        if (!open) {
            return;
        }
        setIsSubmitting(false);

        const base = emptyForm();
        if (phieu) {
            const ngayCap = phieu.NgayCapPhep ? new Date(phieu.NgayCapPhep) : base.NgayCap;
            setForm({
                SoCvCxb: phieu.PhanDauMaSo ?? "",
                SoCvNxbgd: phieu.SoCvNXBGD ?? "",
                NgayCap: ngayCap,
                NamCap: ngayCap ? String(dayjs(ngayCap).year()) : base.NamCap,
                MaSoCxb: phieu.SoGiayPhep ?? "",
            });
            return;
        }
        setForm(base);
    }, [open, phieu]);

    const setField = useCallback(<K extends keyof CapMaCxbForm>(key: K, value: CapMaCxbForm[K]) => {
        setForm((prev) => ({ ...prev, [key]: value }));
    }, []);

    const middleSegment = useMemo(() => `${form.NamCap}/CXBIPH/...`, [form.NamCap]);
    const suffixSegment = useMemo(() => `-${form.SoCvNxbgd}/GD`, [form.SoCvNxbgd]);
    const maSoHoanChinh = useMemo(
        () => `${form.MaSoCxb}-${form.NamCap}/CXBIPH/xxx-${form.SoCvNxbgd}/GD`,
        [form.MaSoCxb, form.NamCap, form.SoCvNxbgd],
    );

    const handleClose = useCallback(() => setShow(false), [setShow]);

    const handleSave = useCallback(async () => {
        if (!phieu?.id) {
            window._toastbox("Không xác định được phiếu trình CXB", "danger");
            return;
        }
        if (!form.SoCvCxb.trim() || !form.SoCvNxbgd.trim() || !form.NgayCap || !form.MaSoCxb.trim()) {
            window._toastbox("Vui lòng nhập đầy đủ các trường bắt buộc", "danger");
            return;
        }

        setIsSubmitting(true);
        const res = await PhieuDkKhxbCxbApi.capMaSoCxb({
            idPhieu: phieu.id,
            SoCvCxb: form.SoCvCxb.trim(),
            SoCvNxbgd: form.SoCvNxbgd.trim(),
            NgayCap: form.NgayCap,
            NamCap: form.NamCap.trim(),
            MaSoCxb: form.MaSoCxb.trim(),
        });
        setIsSubmitting(false);

        if (!res) {
            return;
        }

        window._toastbox("Cấp mã số CXB thành công", "success");
        handleClose();
        onSuccess?.();
    }, [form, handleClose, onSuccess, phieu]);

    return (
        <Modal
            title="CẤP MÃ CXB"
            open={open}
            onCancel={handleClose}
            width={860}
            footer={[
                <Button key="save" type="primary" loading={isSubmitting} onClick={handleSave}>
                    Lưu
                </Button>,
                <Button key="cancel" onClick={handleClose}>
                    Hủy
                </Button>,
            ]}
        >
            <div className="d-flex gap-4 mt-2">
                <div style={{ flex: 1 }}>
                    <FieldRow label="Số công văn giấy xác nhận đăng kí kế hoạch xuất bản của CXB" required>
                        <Input value={form.SoCvCxb} onChange={(e) => setField("SoCvCxb", e.target.value)} />
                    </FieldRow>
                    <FieldRow label="Số công văn đăng kí kế hoạch xuất bản của NXBGDVN" required>
                        <Input value={form.SoCvNxbgd} onChange={(e) => setField("SoCvNxbgd", e.target.value)} />
                    </FieldRow>
                </div>
                <div style={{ width: 220, flexShrink: 0 }}>
                    <div className="mb-3">
                        <div className="mb-1">
                            Ngày cấp <span className="text-danger">(*)</span>
                        </div>
                        <DatePickerAntd
                            className="w-100"
                            format="DD/MM/YYYY"
                            value={convertValueToDayjs(form.NgayCap)}
                            onChange={(date) => setField("NgayCap", date ? date.toDate() : null)}
                        />
                    </div>
                    <div className="mb-3">
                        <div className="mb-1">
                            Năm cấp <span className="text-danger">(*)</span>
                        </div>
                        <Input value={form.NamCap} onChange={(e) => setField("NamCap", e.target.value)} />
                    </div>
                </div>
            </div>

            <FieldRow label="Mã số CXB" required>
                <Space.Compact className="w-100">
                    <Input
                        style={{ width: 90 }}
                        value={form.MaSoCxb}
                        placeholder="Số"
                        onChange={(e) => setField("MaSoCxb", e.target.value)}
                    />
                    <Input value={middleSegment} readOnly />
                    <Input style={{ width: 130 }} value={suffixSegment} readOnly />
                </Space.Compact>
            </FieldRow>

            <FieldRow label="Mã số CXB hoàn chỉnh">
                <Input value={maSoHoanChinh} readOnly />
            </FieldRow>
        </Modal>
    );
});
