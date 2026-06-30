import { Button, Modal, Table } from "antd";
import type { TableProps } from "antd";
import type { Sach } from "../../type";
import React, { useEffect, useState } from "react";
import { SachApi } from "../../api/SachApi";
import DatePicker from "../../../core/utils/DatePicker";
import { convertValueToDayjs, formatDateToIso8601UtcOffset, formatDateToString } from "../../../core/utils/helpersDayjs";
import { KieuBanQuyen } from "../../../topic/type";

interface ModalSachProps {
    sach: Sach| null,
    open: boolean,
    onClose: () => void,
    onSaved?: (sach: Sach) => void,
}

/** Modal đổi mã số sách */
const _ModalChangeKeySach = (props: ModalSachProps) => {
    const { sach, open, onClose, onSaved } = props;

    const [namTBXB, setNamTBXB] = useState<string>("");
    const [maSoMuonCap, setMaSoMuonCap] = useState<string>("");
    const [keyword, setKeyword] = useState<string>("");
    const [listSach, setListSach] = useState<Sach[]>([]);
    const [loadingSearch, setLoadingSearch] = useState<boolean>(false);
    const [saving, setSaving] = useState<boolean>(false);

    useEffect(() => {
        if (open) {
            setNamTBXB(sach?.NamTaiBan || sach?.NamXuatBan || "");
            setMaSoMuonCap("");
            setKeyword("");
            setListSach([]);
        }
    }, [open, sach]);

    const handleSearch = async () => {
        setLoadingSearch(true);
        try {
            const result = await SachApi.getList({ MaSo: keyword, IsDeleted : false });
            setListSach(result);
        } finally {
            setLoadingSearch(false);
        }
    };

    const handleSave = async () => {
        if(!sach) return;
        if (!maSoMuonCap.trim()) {
            window._toastbox("Vui lòng nhập mã số muốn cấp", "danger");
            return;
        }
        setSaving(true);
            await SachApi.upsert({
                id: sach.id,
                MaSo: maSoMuonCap.trim(),
            }).then(res => {
                if(res){
                    window._toastbox("Đổi mã số sách thành công", "success");
                    onSaved?.(res);
                    onClose();
                }
            }).finally(() => {
                setSaving(false);
            })
    };

    const columns: TableProps<Sach>["columns"] = [
        { title: "STT", key: "stt", width: 60, render: (_v, _r, i) => i + 1 },
        { title: "Mã số", dataIndex: "MaSo", key: "MaSo" },
        { title: "Tên đề tài", dataIndex: "TenSach", key: "TenSach" },
        { title: "Tác giả", dataIndex: "TacGia", key: "TacGia" },
        {
            title: "Năm XB/TB",
            dataIndex: "NamXuatBan",
            key: "NamXuatBan",
            align: "right",
            render: (value, record) => value || record.NamTaiBan,
        },
    ];

    return (
        <Modal
            open={open}
            onCancel={onClose}
            title="ĐỔI MÃ SỐ SÁCH"
            width={1000}
            styles={{ body: { minHeight: "60vh" } }}
            footer={[
                <Button key="save" type="primary" loading={saving} onClick={handleSave}>
                    Lưu
                </Button>,
                <Button key="cancel" onClick={onClose}>
                    Hủy
                </Button>,
            ]}
        >
            <div className="row g-3">
                <div className="col-md-3">
                    <label className="form-label mb-1 small text-muted">Năm TBXB</label>
                    <input
                        className="form-control form-control-sm"
                        value={namTBXB}
                        onChange={(e) => setNamTBXB(e.target.value)}
                    />
                </div>
                <div className="col-md-5">
                    <label className="form-label mb-1 small text-muted">Mã số hiện tại</label>
                    <input
                        className="form-control form-control-sm"
                        value={sach?.MaSo ?? ""}
                        readOnly
                    />
                </div>
                <div className="col-md-4">
                    <label className="form-label mb-1 small text-muted">Mã số muốn cấp</label>
                    <input
                        className="form-control form-control-sm"
                        value={maSoMuonCap}
                        onChange={(e) => setMaSoMuonCap(e.target.value)}
                    />
                </div>
            </div>

            <div className="row g-3 align-items-end mt-0">
                <div className="col-md-9">
                    <label className="form-label mb-1 small text-muted">Tìm kiếm sách</label>
                    <input
                        className="form-control form-control-sm"
                        placeholder="Hỗ trợ tìm kiếm mã số dưới dạng ???G??6 hoặc *G??6"
                        value={keyword}
                        onChange={(e) => setKeyword(e.target.value)}
                        onKeyDown={(e) => {
                            if (e.key === "Enter") {
                                handleSearch();
                            }
                        }}
                    />
                </div>
                <div className="col-md-3">
                    <label className="form-label mb-1 small text-muted">Chức năng</label>
                    <div className="d-flex gap-2">
                        <button
                            type="button"
                            className="btn btn-sm btn-secondary"
                            disabled={loadingSearch}
                            onClick={handleSearch}
                        >
                            Tìm kiếm
                        </button>
                    </div>
                </div>
            </div>

            <div className="mt-3">
                <Table<Sach>
                    rowKey="id"
                    columns={columns}
                    dataSource={listSach}
                    loading={loadingSearch}
                    pagination={false}
                    size="small"
                    scroll={{ y: 300 }}
                    onRow={(record) => ({
                        onClick: () => setMaSoMuonCap(record.MaSo),
                    })}
                    locale={{ emptyText: "Nhập mã số và bấm Tìm kiếm" }}
                />
            </div>
        </Modal>
    );
};
_ModalChangeKeySach.displayName = "ModalChangeKeySach";
export const ModalChangeKeySach = React.memo(_ModalChangeKeySach);


/** Modal cập nhật SL cấp phép */
const _ModalUpdateCountSach = (props: ModalSachProps) => {
    const { sach, open, onClose, onSaved } = props;

    const [soLuongBoSung, setSoLuongBoSung] = useState<number>(0);
    const [tamDung, setTamDung] = useState<boolean>(false);
    const [ghiChu, setGhiChu] = useState<string>("");
    const [saving, setSaving] = useState<boolean>(false);

    useEffect(() => {
        if (open) {
            setSoLuongBoSung(0);
            setTamDung(false);
            setGhiChu("");
        }
    }, [open, sach]);

    const handleSave = async () => {
        if (!sach) {
            return;
        }
        setSaving(true);
        await SachApi.upsert({
                id: sach.id,
                SoLuong: Number(sach.SoLuong || 0) + Number(soLuongBoSung || 0),
                LyDoThayDoiSoLuong: ghiChu,
                InUsed: !tamDung,
        }).then(res => {
            if (res) {
                window._toastbox("Cập nhật số lượng cấp phép thành công", "success");
                onSaved?.(res);
                onClose();
            }
        }).finally(() => {
            setSaving(false);
        })
    };

    return (
        <Modal
            open={open}
            onCancel={onClose}
            title="CẬP NHẬT SỐ LƯỢNG CẤP PHÉP"
            width={560}
            footer={[
                <Button key="save" type="primary" loading={saving} onClick={handleSave}>
                    Lưu
                </Button>,
                <Button key="cancel" onClick={onClose}>
                    Hủy
                </Button>,
            ]}
        >
            <div className="row g-3 align-items-center">
                <label className="col-sm-4 col-form-label col-form-label-sm text-muted">
                    Số lượng cũ
                </label>
                <div className="col-sm-8">
                    <input
                        className="form-control form-control-sm"
                        value={sach?.SoLuong ?? 0}
                        readOnly
                    />
                </div>

                <label className="col-sm-4 col-form-label col-form-label-sm text-muted">
                    Số lượng xin bổ sung
                </label>
                <div className="col-sm-8">
                    <input
                        type="number"
                        min={0}
                        className="form-control form-control-sm"
                        value={soLuongBoSung}
                        onChange={(e) => setSoLuongBoSung(Number(e.target.value))}
                    />
                </div>

                <label className="col-sm-4 col-form-label col-form-label-sm text-muted">
                    Tạm dừng cấp phép
                </label>
                <div className="col-sm-8">
                    <input
                        type="checkbox"
                        className="form-check-input"
                        checked={tamDung}
                        onChange={(e) => setTamDung(e.target.checked)}
                    />
                </div>

                <label className="col-sm-4 col-form-label col-form-label-sm text-muted">
                    Ghi chú
                </label>
                <div className="col-sm-8">
                    <textarea
                        className="form-control form-control-sm"
                        rows={4}
                        placeholder="Ghi chú"
                        value={ghiChu}
                        onChange={(e) => setGhiChu(e.target.value)}
                    />
                </div>
            </div>
        </Modal>
    );
};
_ModalUpdateCountSach.displayName = "ModalUpdateCountSach";
export const ModalUpdateCountSach = React.memo(_ModalUpdateCountSach);

/** Modal cập nhật bản quyền */
const _ModalUpdateLicenseSach = (props: ModalSachProps) => {
    const { sach, open, onClose, onSaved } = props;

    const [kieuBanQuyen, setKieuBanQuyen] = useState<KieuBanQuyen>(KieuBanQuyen.CO_THOI_HAN);
    const [tuNgay, setTuNgay] = useState<string>("");
    const [denNgay, setDenNgay] = useState<string>("");
    const [thongTinBanQuyen, setThongTinBanQuyen] = useState<string>("");
    const [soHuuBanQuyen, setSoHuuBanQuyen] = useState<string>("");
    const [saving, setSaving] = useState<boolean>(false);

    useEffect(() => {
        if (open) {
            setKieuBanQuyen(sach?.KieuBanQuyen || KieuBanQuyen.CO_THOI_HAN);
            setTuNgay(sach?.BanQuyenTuNgay || "");
            setDenNgay(sach?.BanQuyenDenNgay || "");
            setThongTinBanQuyen(sach?.ThongTinBanQuyen || "");
            setSoHuuBanQuyen(sach?.SoHuuBanQuyen || "");
        }
    }, [open, sach]);

    const coThoiHan = kieuBanQuyen === KieuBanQuyen.CO_THOI_HAN;

    const handleSave = async () => {
        if (!sach) {
            return;
        }
        if (coThoiHan && (!tuNgay || !denNgay)) {
            window._toastbox("Vui lòng nhập thời hạn bản quyền", "danger");
            return;
        }
        if (!thongTinBanQuyen.trim() || !soHuuBanQuyen.trim()) {
            window._toastbox("Vui lòng nhập đầy đủ thông tin bản quyền", "danger");
            return;
        }
        setSaving(true);
        const payload: Partial<Sach> = {
            id: sach.id,
            BanQuyen: true,
            KieuBanQuyen: kieuBanQuyen,
            ThongTinBanQuyen: thongTinBanQuyen,
            SoHuuBanQuyen: soHuuBanQuyen,
        };
        if (coThoiHan) {
            payload.BanQuyenTuNgay = formatDateToIso8601UtcOffset(tuNgay) ?? "";
            payload.BanQuyenDenNgay = formatDateToIso8601UtcOffset(denNgay) ?? "";
        }
        await SachApi.upsert(payload).then(res => {
            if (res) {
                window._toastbox("Cập nhật bản quyền thành công", "success");
                onSaved?.(res);
                onClose();
            }
        }).finally(() => {
            setSaving(false);
        })
    };

    return (
        <Modal
            open={open}
            onCancel={onClose}
            title="CẬP NHẬT BẢN QUYỀN"
            width={640}
            footer={[
                <Button key="save" type="primary" loading={saving} onClick={handleSave}>
                    Lưu
                </Button>,
                <Button key="cancel" onClick={onClose}>
                    Hủy
                </Button>,
            ]}
        >
            <div className="row g-3 align-items-center">
                <label className="col-sm-4 col-form-label col-form-label-sm text-muted">
                    Kiểu bản quyền <span className="text-danger">(*)</span>
                </label>
                <div className="col-sm-8">
                    <select
                        className="form-select form-select-sm"
                        value={kieuBanQuyen}
                        onChange={(e) => setKieuBanQuyen(e.target.value as unknown as KieuBanQuyen)}
                    >
                        <option value={KieuBanQuyen.CO_THOI_HAN}>Có thời hạn</option>
                        <option value={KieuBanQuyen.VO_THOI_HAN}>Vô thời hạn</option>
                    </select>
                </div>

                {coThoiHan && (
                    <React.Fragment>
                        <label className="col-sm-4 col-form-label col-form-label-sm text-muted">
                            Bản quyền từ ngày <span className="text-danger">(*)</span>
                        </label>
                        <div className="col-sm-8">
                            <div className="d-flex align-items-center gap-2">
                                <DatePicker
                                    style={{ width: "100%" }}
                                    format="DD/MM/YYYY"
                                    placeholder="Từ ngày"
                                    value={convertValueToDayjs(tuNgay) ?? null}
                                    onChange={(date) => setTuNgay(date ? date.toISOString() : "")}
                                />
                                <span className="text-nowrap text-muted">đến ngày</span>
                                <DatePicker
                                    style={{ width: "100%" }}
                                    format="DD/MM/YYYY"
                                    placeholder="Đến ngày"
                                    value={convertValueToDayjs(denNgay) ?? null}
                                    onChange={(date) => setDenNgay(date ? date.toISOString() : "")}
                                />
                            </div>
                        </div>
                    </React.Fragment>
                )}

                <label className="col-sm-4 col-form-label col-form-label-sm text-muted">
                    Thông tin bản quyền <span className="text-danger">(*)</span>
                </label>
                <div className="col-sm-8">
                    <textarea
                        className="form-control form-control-sm"
                        rows={3}
                        placeholder="Thông tin bản quyền"
                        value={thongTinBanQuyen}
                        onChange={(e) => setThongTinBanQuyen(e.target.value)}
                    />
                </div>

                <label className="col-sm-4 col-form-label col-form-label-sm text-muted">
                    Sở hữu bản quyền <span className="text-danger">(*)</span>
                </label>
                <div className="col-sm-8">
                    <textarea
                        className="form-control form-control-sm"
                        rows={3}
                        placeholder="Sở hữu bản quyền"
                        value={soHuuBanQuyen}
                        onChange={(e) => setSoHuuBanQuyen(e.target.value)}
                    />
                </div>
            </div>
        </Modal>
    );
};
_ModalUpdateLicenseSach.displayName = "ModalUpdateLicenseSach";
export const ModalUpdateLicenseSach = React.memo(_ModalUpdateLicenseSach);

/** Modal cập nhật giá bìa */
const _ModalUpdatePriceSach = (props: ModalSachProps) => {
    const { sach, open, onClose, onSaved } = props;

    const [giaBia, setGiaBia] = useState<number>(0);
    const [saving, setSaving] = useState<boolean>(false);

    useEffect(() => {
        if (open) {
            setGiaBia(sach?.GiaBia || 0);
        }
    }, [open, sach]);

    const handleSave = async () => {
        if (!sach) {
            return;
        }
        if (!giaBia || giaBia <= 0) {
            window._toastbox("Vui lòng nhập giá bìa", "danger");
            return;
        }
        setSaving(true);
        await SachApi.upsert({
            id: sach.id,
            GiaBia: Number(giaBia),
        }).then(res => {
            if (res) {
                window._toastbox("Cập nhật giá bìa thành công", "success");
                onSaved?.(res);
                onClose();
            }
        }).finally(() => {
            setSaving(false);
        })
    };

    return (
        <Modal
            open={open}
            onCancel={onClose}
            title="CẬP NHẬT GIÁ BÌA"
            width={560}
            footer={[
                <Button key="save" type="primary" loading={saving} onClick={handleSave}>
                    Lưu
                </Button>,
                <Button key="cancel" onClick={onClose}>
                    Hủy
                </Button>,
            ]}
        >
            <div className="row g-3 align-items-center">
                <label className="col-sm-4 col-form-label col-form-label-sm text-muted">
                    Giá bìa <span className="text-danger">(*)</span>
                </label>
                <div className="col-sm-8">
                    <input
                        type="number"
                        min={0}
                        className="form-control form-control-sm"
                        value={giaBia}
                        onChange={(e) => setGiaBia(Number(e.target.value))}
                        onKeyDown={(e) => {
                            if (e.key === "Enter") {
                                handleSave();
                            }
                        }}
                    />
                </div>
            </div>
        </Modal>
    );
};
_ModalUpdatePriceSach.displayName = "ModalUpdatePriceSach";
export const ModalUpdatePriceSach = React.memo(_ModalUpdatePriceSach);

/** Hiển thị một dòng thông tin dạng "Nhãn: Giá trị" */
const InfoItem = (props: { label: string; value?: React.ReactNode }) => (
    <div className="mb-2">
        <span className="text-muted">{props.label}: </span>
        <span className="fw-bold">{props.value ?? ""}</span>
    </div>
);

const formatNumber = (value?: number | null): string => {
    if (value === null || value === undefined) {
        return "";
    }
    return Number(value).toLocaleString("vi-VN");
};

/** Modal thông tin chi tiết của sách */
const _ModalInfoSach = (props: ModalSachProps) => {
    const { sach, open, onClose } = props;

    if (!sach) {
        return null;
    }

    const tuNgay = formatDateToString(sach.BanQuyenTuNgay);
    const denNgay = formatDateToString(sach.BanQuyenDenNgay);

    return (
        <Modal
            open={open}
            onCancel={onClose}
            title="THÔNG TIN CHI TIẾT DANH MỤC SÁCH"
            width={1024}
            styles={{ body: { maxHeight: "70vh", overflow: "auto" } }}
            footer={[
                <Button key="close" onClick={onClose}>
                    Đóng
                </Button>,
            ]}
        >
            <InfoItem label="Mã sách" value={sach.MaSo} />
            <InfoItem label="Tên sách" value={sach.TenSach} />
            <InfoItem label="Tác giả" value={sach.TacGia} />

            <div className="row">
                <div className="col-md-4">
                    <InfoItem label="Lứa tuổi" value={sach.LuaTuoi} />
                </div>
                <div className="col-md-4">
                    <InfoItem label="Mã CXB" value={sach.MaSoCXB} />
                </div>
                <div className="col-md-4">
                    <InfoItem label="Mã ISBN" value={sach.ISBNCode} />
                </div>

                <div className="col-md-4">
                    <InfoItem label="Số GPXB" value={sach.SoGPXB} />
                </div>
                <div className="col-md-4">
                    <InfoItem label="Số trang" value={sach.SoTrang} />
                </div>
                <div className="col-md-4">
                    <InfoItem label="Khổ sách" value={`${sach.Rong ?? ""} x ${sach.Dai ?? ""}`} />
                </div>

                <div className="col-md-4">
                    <InfoItem label="Hình thức xuất bản" value={sach.HTXB ? "Mới" : "Tái bản"} />
                </div>
                <div className="col-md-4">
                    <InfoItem label="Năm xuất bản" value={sach.NamXuatBan} />
                </div>
                <div className="col-md-4">
                    <InfoItem label="Màu in ruột" value={sach.MauInRuot} />
                </div>

                <div className="col-md-4">
                    <InfoItem label="Màu in bìa" value={sach.MauInBia} />
                </div>
                <div className="col-md-4">
                    <InfoItem label="Lần tái bản" value={sach.LanTaiBan} />
                </div>
                <div className="col-md-4">
                    <InfoItem label="Năm tái bản" value={sach.NamTaiBan} />
                </div>

                <div className="col-md-4">
                    <InfoItem label="Tổng số lượng" value={formatNumber(sach.SoLuong)} />
                </div>
                <div className="col-md-4">
                    <InfoItem
                        label="Tổng số lượng QĐXB đã cấp"
                        value={formatNumber((sach.SoLuong ?? 0) - (sach.SoLuongConLai ?? 0))}
                    />
                </div>
                <div className="col-md-4">
                    <InfoItem label="Số lượng còn lại" value={formatNumber(sach.SoLuongConLai)} />
                </div>

                <div className="col-md-4">
                    <InfoItem
                        label="Kiểu bản quyền"
                        value={sach.KieuBanQuyen === KieuBanQuyen.VO_THOI_HAN ? "Vô thời hạn" : "Có thời hạn"}
                    />
                </div>
                <div className="col-md-8">
                    <InfoItem
                        label="Bản quyền từ ngày"
                        value={tuNgay ? `${tuNgay} đến ngày ${denNgay}` : ""}
                    />
                </div>

                <div className="col-md-6">
                    <InfoItem label="Thông tin bản quyền" value={sach.ThongTinBanQuyen} />
                </div>
                <div className="col-md-6">
                    <InfoItem label="Sở hữu bản quyền" value={sach.SoHuuBanQuyen} />
                </div>

                <div className="col-md-6">
                    <InfoItem label="Đơn vị đăng ký" value={sach.don_vi?.TenDonVi} />
                </div>
                <div className="col-md-6">
                    <InfoItem label="Đơn vị in phát hành" value={sach.don_vi?.TenDonVi} />
                </div>
            </div>

            <InfoItem
                label="Kết quả đọc duyệt bản thảo"
                value={sach.TrangThaiDocBanThao || "Chưa xét duyệt"}
            />
        </Modal>
    );
};
_ModalInfoSach.displayName = "ModalInfoSach";
export const ModalInfoSach = React.memo(_ModalInfoSach);
