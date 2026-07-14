import React, { useCallback, useEffect, useState } from "react";
import { Button, Flex, Typography } from "antd";
import { CloseCircleOutlined, ReloadOutlined, SaveOutlined } from "@ant-design/icons";
import dayjs from "dayjs";
import {
    mountReactComponentOnReady,
    readRootDataProps,
} from "../../../core/utils/helpers";
import type { DonVi, User } from "../../../user/type";
import type { Mangsach } from "../../../system/type";
import { PhieuDkKhxbCxbApi } from "../../api/PhieuDkKhxbCxbApi";
import { FormFieldPhieuDkKhxbCxb } from "../../component/PhieuDkKhxbCxb/FormFieldPhieuDkKhxbCxb";
import { TableDeTaiPhieuDkKhxbCxb } from "../../component/PhieuDkKhxbCxb/TableDeTaiPhieuDkKhxbCxb";
import { ModalChooseDeTaiPhieuDkKhxbCxb } from "../../component/PhieuDkKhxbCxb/ModalChooseDeTaiPhieuDkKhxbCxb";
import type { PhieuDkDetai, PhieuDkKhxbCxb } from "../../type";

const DEFAULT_NOI_NHAN = "- Như trên\r\n- HC, QLXB";

function emptyFormState(): Partial<PhieuDkKhxbCxb> {
    return {
        id: 0,
        MaSo: "",
        TieuDe: "",
        NoiDung: "",
        NoiNhan2: DEFAULT_NOI_NHAN,
        NgayDK: dayjs().startOf("day").toDate(),
        ID_NguoiKi: null,
        KiThay: false,
    };
}

interface ViewStorePhieuDkKhxbCxbProps {
    listUsers: User[];
    mapTrangThai: Record<number, string>;
    listDonvi: DonVi[];
    listMangsach: Mangsach[];
    phieuDkKhxbCxb?: PhieuDkKhxbCxb | null;
    listDeTai?: PhieuDkDetai[];
}

export const ViewStorePhieuDkKhxbCxb = React.memo((props: ViewStorePhieuDkKhxbCxbProps) => {
    const {
        listUsers,
        mapTrangThai,
        listDonvi,
        listMangsach,
        phieuDkKhxbCxb,
        listDeTai: initialListDeTai = [],
    } = props;

    const isEdit = !!(phieuDkKhxbCxb && phieuDkKhxbCxb.id);

    const [form, setForm] = useState<Partial<PhieuDkKhxbCxb>>(() => {
        if (phieuDkKhxbCxb) {
            return { ...phieuDkKhxbCxb };
        }
        return emptyFormState();
    });
    const [listDeTai, setListDeTai] = useState<PhieuDkDetai[]>(initialListDeTai);
    const [submitting, setSubmitting] = useState(false);
    const [showModalChooseDeTai, setShowModalChooseDeTai] = useState(false);

    const loadPreviewMaSo = useCallback(() => {
        PhieuDkKhxbCxbApi.previewMaSo().then((maSo) => {
            if (maSo) {
                setForm((prev) => ({ ...prev, MaSo: maSo }));
            }
        });
    }, []);

    useEffect(() => {
        if (!isEdit && !(form.MaSo ?? "").trim()) {
            loadPreviewMaSo();
        }
    }, [form.MaSo, isEdit, loadPreviewMaSo]);

    const setField = useCallback(<K extends keyof PhieuDkKhxbCxb>(key: K, value: PhieuDkKhxbCxb[K]) => {
        setForm((prev) => ({ ...prev, [key]: value }));
    }, []);

    const handleReset = useCallback(() => {
        if (isEdit && phieuDkKhxbCxb) {
            setForm({ ...phieuDkKhxbCxb });
            setListDeTai(initialListDeTai);
            return;
        }
        setForm(emptyFormState());
        setListDeTai([]);
        loadPreviewMaSo();
    }, [initialListDeTai, isEdit, loadPreviewMaSo, phieuDkKhxbCxb]);

    const handleSave = useCallback(async (closeAfterSave = false) => {
        const tieuDe = (form.TieuDe ?? "").trim();
        const noiDung = (form.NoiDung ?? "").trim();
        if (!tieuDe || !noiDung) {
            window._toastbox("Vui lòng nhập đầy đủ Tiêu đề và Nội dung", "error");
            return;
        }

        setSubmitting(true);
        const res = await PhieuDkKhxbCxbApi.store({
            id: form.id ?? 0,
            ...((form.id ?? 0) > 0 ? { MaSo: form.MaSo ?? "" } : {}),
            TieuDe: tieuDe,
            NoiDung: noiDung,
            NoiNhan2: form.NoiNhan2 ?? "",
            NgayDK: form.NgayDK ?? null,
            ID_NguoiKi: form.ID_NguoiKi ?? null,
            KiThay: !!form.KiThay,
            listIdDeTai: listDeTai.map((item) => item.id),
        });
        setSubmitting(false);

        if (!res) {
            return;
        }

        window._toastbox("Lưu phiếu trình CXB thành công", "success");
        setForm({ ...res.phieu });
        setListDeTai(res.listDeTai ?? []);

        if (closeAfterSave) {
            window.location.href = "/phieu-dk-khxb-cxb/quan-ly";
            return;
        }

        if (!isEdit && res.phieu?.id) {
            window.history.replaceState(null, "", `/phieu-dk-khxb-cxb/cap-nhat/${res.phieu.id}`);
        }
    }, [form, isEdit, listDeTai]);

    const handleAddDeTai = useCallback((items: PhieuDkDetai[]) => {
        setListDeTai((prev) => {
            const existing = new Set(prev.map((item) => item.id));
            const toAdd = items.filter((item) => !existing.has(item.id));
            return [...prev, ...toAdd];
        });
    }, []);

    const handleRemoveDeTai = useCallback((id: number) => {
        setListDeTai((prev) => prev.filter((item) => item.id !== id));
    }, []);

    return (
        <div className="px-2 py-2">
            <Typography.Title level={4} className="mb-2 border-bottom pb-2">
                {isEdit ? "Cập nhật phiếu trình CXB" : "Thêm mới phiếu trình CXB"}
            </Typography.Title>

            <Flex gap={8} className="mb-3 border-bottom pb-2">
                <Button
                    type="text"
                    icon={<SaveOutlined />}
                    title="Lưu"
                    onClick={() => handleSave(false)}
                    loading={submitting}
                />
                <Button
                    type="text"
                    icon={<CloseCircleOutlined />}
                    title="Lưu và đóng"
                    onClick={() => handleSave(true)}
                    loading={submitting}
                />
                <Button
                    type="text"
                    icon={<ReloadOutlined />}
                    title="Làm mới"
                    onClick={handleReset}
                    disabled={submitting}
                />
            </Flex>

            <FormFieldPhieuDkKhxbCxb
                form={form}
                setField={setField}
                listSigners={listUsers}
                isMaSoReadOnly
            />

            <div className="mb-2 mt-3">
                <button
                    type="button"
                    className="btn btn-link text-success text-decoration-none p-0 fw-semibold"
                    onClick={() => setShowModalChooseDeTai(true)}
                >
                    + Chọn đề tài
                </button>
            </div>

            <TableDeTaiPhieuDkKhxbCxb
                listDeTai={listDeTai}
                mapTrangThai={mapTrangThai}
                listUsers={listUsers}
                onRemove={handleRemoveDeTai}
            />

            <ModalChooseDeTaiPhieuDkKhxbCxb
                open={showModalChooseDeTai}
                onClose={() => setShowModalChooseDeTai(false)}
                onConfirm={handleAddDeTai}
                excludedIds={listDeTai.map((item) => item.id)}
                listDonvi={listDonvi}
                listMangsach={listMangsach}
            />
        </div>
    );
});

const ROOT_ID = "root-store-phieu-dk-khxb-cxb";
const bladeProps: ViewStorePhieuDkKhxbCxbProps = {
    listUsers: [] as User[],
    mapTrangThai: {} as Record<number, string>,
    listDonvi: [] as DonVi[],
    listMangsach: [] as Mangsach[],
    phieuDkKhxbCxb: null,
    listDeTai: [],
    ...readRootDataProps<ViewStorePhieuDkKhxbCxbProps>(ROOT_ID),
};
mountReactComponentOnReady(ROOT_ID, <ViewStorePhieuDkKhxbCxb {...bladeProps} />);
