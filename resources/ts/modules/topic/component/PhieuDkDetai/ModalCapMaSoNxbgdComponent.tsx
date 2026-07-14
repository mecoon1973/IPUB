import React, { useCallback, useEffect, useMemo, useState } from "react";
import { Button, Input, Modal, Table, type TableProps } from "antd";
import { ReloadOutlined, SearchOutlined } from "@ant-design/icons";
import { useManagePhieuDkDetaiStore } from "../../store/PhieuDkDetai/managePhieuDkDetaiStore";
import { PhieuDkDetaiApi } from "../../api/PhieuDkDetaiApi";
import type { PhieuDkDetai } from "../../type";

interface ModalCapMaSoNxbgdComponentProps {
    onSuccess?: () => void;
}

export const ModalCapMaSoNxbgdComponent = React.memo((props: ModalCapMaSoNxbgdComponentProps) => {
    const { onSuccess } = props;
    const showModalCapMaSoNxbgd = useManagePhieuDkDetaiStore((state) => state.showModalCapMaSoNxbgd);
    const setShowModalCapMaSoNxbgd = useManagePhieuDkDetaiStore((state) => state.setShowModalCapMaSoNxbgd);
    const phieuDkDetaiContext = useManagePhieuDkDetaiStore((state) => state.PhieuDkDetaiContext);
    const setListPhieuDkDetai = useManagePhieuDkDetaiStore((state) => state.setListPhieuDkDetai);

    const [maSoMuonCap, setMaSoMuonCap] = useState("");
    const [keyword, setKeyword] = useState("");
    const [searchRows, setSearchRows] = useState<PhieuDkDetai[]>([]);
    const [isLoadingPreview, setIsLoadingPreview] = useState(false);
    const [isLoadingSearch, setIsLoadingSearch] = useState(false);
    const [isSubmitting, setIsSubmitting] = useState(false);

    const loadPreviewMaSo = useCallback(async (id: number) => {
        setIsLoadingPreview(true);
        const maSo = await PhieuDkDetaiApi.previewMaSoNxbgd(id, true);
        if (maSo) {
            setMaSoMuonCap(maSo);
        } else {
            setMaSoMuonCap("");
        }
        setIsLoadingPreview(false);
        return maSo;
    }, []);

    useEffect(() => {
        if (!showModalCapMaSoNxbgd || !phieuDkDetaiContext?.id) {
            return;
        }

        setKeyword("");
        setSearchRows([]);
        setIsSubmitting(false);

        const existingMaSo = (phieuDkDetaiContext.MaSo ?? "").trim();
        if (existingMaSo.length === 12) {
            setMaSoMuonCap(existingMaSo);
            return;
        }

        setMaSoMuonCap("");
        void loadPreviewMaSo(phieuDkDetaiContext.id);
    }, [showModalCapMaSoNxbgd, phieuDkDetaiContext?.id, phieuDkDetaiContext?.MaSo, loadPreviewMaSo]);

    const handleClose = useCallback(() => {
        setShowModalCapMaSoNxbgd(false);
    }, [setShowModalCapMaSoNxbgd]);

    const handleCapLai = useCallback(() => {
        if (!phieuDkDetaiContext?.id) return;
        loadPreviewMaSo(phieuDkDetaiContext.id);
    }, [loadPreviewMaSo, phieuDkDetaiContext?.id]);

    const handleSearch = useCallback(async () => {
        if (!phieuDkDetaiContext?.ID_DonVi) {
            window._toastbox("Không xác định được đơn vị của đề tài", "danger");
            return;
        }
        if (!keyword.trim()) {
            window._toastbox("Vui lòng nhập mã số cần tìm", "danger");
            return;
        }

        setIsLoadingSearch(true);
        const list = await PhieuDkDetaiApi.getList({
            MaSo: keyword.trim(),
            ID_DonVi: phieuDkDetaiContext.ID_DonVi,
            IsDeleted: false,
        });
        setSearchRows(list.filter((item) => item.MaSo));
        setIsLoadingSearch(false);
    }, [keyword, phieuDkDetaiContext]);

    const handleSave = useCallback(async () => {
        if (!phieuDkDetaiContext?.id) {
            window._toastbox("Không tìm thấy đề tài cần cấp mã", "danger");
            return;
        }
        if (!maSoMuonCap.trim()) {
            window._toastbox("Vui lòng nhập mã số muốn cấp", "danger");
            return;
        }

        setIsSubmitting(true);
        const result = await PhieuDkDetaiApi.capMaSoNxbgd(
            phieuDkDetaiContext.id,
            maSoMuonCap.trim(),
            true,
        );
        setIsSubmitting(false);

        if (!result) return;

        window._toastbox("Cấp mã số NXBGD thành công", "success");
        setListPhieuDkDetai((prev) =>
            prev.map((item) => (item.id === result.id ? { ...item, ...result } : item)),
        );
        handleClose();
        onSuccess?.();
    }, [handleClose, maSoMuonCap, onSuccess, phieuDkDetaiContext, setListPhieuDkDetai]);

    const columns: TableProps<PhieuDkDetai>["columns"] = useMemo(
        () => [
            { title: "STT", key: "stt", width: 56, render: (_v, _r, i) => i + 1 },
            { title: "Mã số", dataIndex: "MaSo", key: "MaSo", width: 140 },
            { title: "Tên đề tài", dataIndex: "TenDeTai", key: "TenDeTai" },
            { title: "Tác giả", dataIndex: "TacGia", key: "TacGia", width: 120 },
            {
                title: "Năm XB/TB",
                key: "NamXB",
                width: 100,
                align: "right",
                render: (_v, record) => record.NamXuatBan || record.NamTaiBan || "",
            },
        ],
        [],
    );

    if (!phieuDkDetaiContext) {
        return null;
    }

    return (
        <Modal
            title="CẤP MÃ SỐ NXBGD"
            open={showModalCapMaSoNxbgd}
            onCancel={handleClose}
            width={960}
            footer={[
                <Button key="cancel" onClick={handleClose}>
                    Hủy
                </Button>,
                <Button
                    key="save"
                    type="primary"
                    loading={isSubmitting}
                    disabled={isLoadingPreview || !maSoMuonCap.trim()}
                    onClick={handleSave}
                >
                    Lưu
                </Button>,
            ]}
        >
            <div className="mb-3">
                <div className="fw-semibold mb-1">
                    Định dạng mã số muốn cấp <span className="text-danger">*</span>
                </div>
                <div className="text-muted">
                    Mã 12 kí tự — mã được sinh tự động khi mở, bấm <strong>Lưu</strong> để xác nhận cấp mã.
                </div>
            </div>

            <div className="row g-2 align-items-end mb-3">
                <div className="col-md-8">
                    <label className="form-label mb-1 fw-semibold">
                        Mã số muốn cấp <span className="text-danger">*</span>
                    </label>
                    <Input
                        value={maSoMuonCap}
                        placeholder={isLoadingPreview ? "Đang sinh mã số..." : "Mã số 12 ký tự"}
                        maxLength={12}
                        readOnly
                    />
                </div>
                <div className="col-md-4">
                    <Button icon={<ReloadOutlined />} loading={isLoadingPreview} onClick={handleCapLai} block>
                        Cấp lại
                    </Button>
                </div>
            </div>

            <div className="row g-2 align-items-end mb-3">
                <div className="col-md-9">
                    <Input
                        value={keyword}
                        onChange={(e) => setKeyword(e.target.value)}
                        placeholder="Hỗ trợ tìm kiếm mã số dưới dạng ???G??6 hoặc *G??6"
                        onPressEnter={handleSearch}
                    />
                </div>
                <div className="col-md-3">
                    <Button type="primary" icon={<SearchOutlined />} loading={isLoadingSearch} onClick={handleSearch} block>
                        Tìm kiếm
                    </Button>
                </div>
            </div>

            <Table<PhieuDkDetai>
                rowKey={(record) => String(record.id)}
                columns={columns}
                dataSource={searchRows}
                loading={isLoadingSearch}
                pagination={false}
                size="small"
                scroll={{ y: 280 }}
                locale={{ emptyText: "Nhập mã số và bấm Tìm kiếm" }}
                onRow={(record) => ({
                    onClick: () => setMaSoMuonCap(record.MaSo),
                    style: { cursor: "pointer" },
                })}
            />
        </Modal>
    );
});
