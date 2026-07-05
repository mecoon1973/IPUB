import React, { useCallback, useEffect, useMemo, useState } from "react";
import { Button, Modal, Table, type TableProps } from "antd";
import { ComponentSelectAntObject } from "../../../page/component/componentSelectAnt";
import type { User } from "../../../user/type";
import type { HDXBNXBGDVN } from "../../type";
import { useManageHDXBNXBGDVNStore } from "../../store/HDXBNXBGDVN/manageHDXBNXBGDVN";
import { HDXBNXBGDVNApi } from "../../api/HDXBNXBGDVNApi";

interface ModalPhanCongDocDuyetHDXBNXBGDVNProps {
    listBTV: User[];
    onSuccess?: () => void;
}

function ModalPhanCongDocDuyetHDXBNXBGDVN(props: ModalPhanCongDocDuyetHDXBNXBGDVNProps) {
    const { listBTV, onSuccess } = props;
    const activeModal = useManageHDXBNXBGDVNStore((state) => state.activeModal);
    const setActiveModal = useManageHDXBNXBGDVNStore((state) => state.setActiveModal);
    const phanCongItems = useManageHDXBNXBGDVNStore((state) => state.phanCongItems);
    const setPhanCongItems = useManageHDXBNXBGDVNStore((state) => state.setPhanCongItems);
    const [idCanBo, setIdCanBo] = useState<number>(0);
    const [idDeTaiThem, setIdDeTaiThem] = useState<number>(0);
    const [deTaiChuaPhanCong, setDeTaiChuaPhanCong] = useState<HDXBNXBGDVN[]>([]);
    const [isSubmitting, setIsSubmitting] = useState(false);

    const open = activeModal === "phanCongDocDuyet";

    const nguoiDocDuyetTen = useMemo(() => {
        if (!idCanBo) return "";
        return listBTV.find((u) => Number(u.id) === Number(idCanBo))?.HoTen ?? "";
    }, [idCanBo, listBTV]);

    useEffect(() => {
        if (!open) {
            setIdCanBo(0);
            setIdDeTaiThem(0);
            setIsSubmitting(false);
            return;
        }
        HDXBNXBGDVNApi.getList({ PhanCong: 0 }).then((res) => setDeTaiChuaPhanCong(res));
    }, [open]);

    const handleClose = useCallback(() => {
        setActiveModal(null);
        setPhanCongItems([]);
    }, [setActiveModal, setPhanCongItems]);

    const handleAddDeTai = useCallback(() => {
        if (!idDeTaiThem) return;
        const deTai = deTaiChuaPhanCong.find((item) => item.id === idDeTaiThem);
        if (!deTai) return;
        setPhanCongItems((prev) => {
            if (prev.some((item) => item.id === deTai.id)) {
                window._toastbox("Đề tài đã có trong danh sách", "warning");
                return prev;
            }
            return [...prev, deTai];
        });
        setIdDeTaiThem(0);
    }, [deTaiChuaPhanCong, idDeTaiThem, setPhanCongItems]);

    const handleRemove = useCallback(
        (id: number) => {
            setPhanCongItems((prev) => prev.filter((item) => item.id !== id));
        },
        [setPhanCongItems],
    );

    const handleSubmit = useCallback(async () => {
        if (!idCanBo) {
            window._toastbox("Vui lòng chọn cán bộ phân công đọc duyệt", "danger");
            return;
        }
        if (phanCongItems.length === 0) {
            window._toastbox("Vui lòng chọn ít nhất một đề tài", "danger");
            return;
        }

        setIsSubmitting(true);
        const ok = await HDXBNXBGDVNApi.phanCongDocDuyet(
            phanCongItems.map((item) => item.id),
            idCanBo,
        );
        setIsSubmitting(false);

        if (!ok) return;

        window._toastbox("Phân công đọc duyệt thành công", "success");
        handleClose();
        onSuccess?.();
    }, [handleClose, idCanBo, onSuccess, phanCongItems]);

    const deTaiThemOptions = useMemo(
        () => deTaiChuaPhanCong.filter((item) => !phanCongItems.some((p) => p.id === item.id)),
        [deTaiChuaPhanCong, phanCongItems],
    );

    const columns: TableProps<HDXBNXBGDVN>["columns"] = [
        { title: "TT", key: "stt", width: 56, render: (_v, _r, i) => i + 1 },
        { title: "TÊN ĐỀ TÀI", dataIndex: "TenDeTai", key: "TenDeTai" },
        {
            title: "NGƯỜI ĐỌC DUYỆT",
            key: "NguoiDocDuyet",
            render: () => nguoiDocDuyetTen || "-",
        },
        { title: "ĐƠN VỊ TỔ CHỨC BẢN THẢO", dataIndex: "TenDonVi", key: "TenDonVi" },
        {
            title: "",
            key: "action",
            width: 80,
            render: (_, record) => (
                <Button type="link" danger size="small" className="px-0" onClick={() => handleRemove(record.id)}>
                    Xóa
                </Button>
            ),
        },
    ];

    return (
        <Modal
            title="PHÂN CÔNG ĐỌC DUYỆT ĐỀ TÀI"
            open={open}
            onCancel={handleClose}
            width={960}
            footer={[
                <Button key="cancel" onClick={handleClose}>
                    Hủy
                </Button>,
                <Button key="submit" type="primary" loading={isSubmitting} onClick={handleSubmit}>
                    Lưu
                </Button>,
            ]}
        >
            <div className="row g-3 mb-3">
                <div className="col-md-6">
                    <label className="form-label mb-1">Chọn cán bộ</label>
                    <ComponentSelectAntObject
                        listData={listBTV}
                        keyValue="id"
                        labelValue="HoTen"
                        value={idCanBo || undefined}
                        onChange={(value) => setIdCanBo(Number(value))}
                        placeholder="Chọn cán bộ phân công đọc duyệt"
                        style={{ width: "100%" }}
                        showSearch
                        optionFilterProp="label"
                    />
                </div>
                <div className="col-md-6">
                    <label className="form-label mb-1">Chọn thêm đề tài</label>
                    <div className="d-flex gap-2">
                        <ComponentSelectAntObject
                            listData={deTaiThemOptions}
                            keyValue="id"
                            labelValue="TenDeTai"
                            value={idDeTaiThem || undefined}
                            onChange={(value) => setIdDeTaiThem(Number(value))}
                            placeholder="Chọn thêm đề tài để phân công đọc duyệt"
                            style={{ width: "100%" }}
                            showSearch
                            optionFilterProp="label"
                        />
                        <Button onClick={handleAddDeTai}>Thêm</Button>
                    </div>
                </div>
            </div>
            <Table<HDXBNXBGDVN>
                rowKey={(record) => String(record.id)}
                columns={columns}
                dataSource={phanCongItems}
                pagination={false}
                size="small"
                scroll={{ y: 320 }}
            />
        </Modal>
    );
}

export default React.memo(ModalPhanCongDocDuyetHDXBNXBGDVN);
