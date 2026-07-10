import React, { useEffect, useState } from "react";
import { Button, Modal, Spin } from "antd";
import { SachApi } from "../../../book/api/SachApi";
import type { Sach } from "../../../book/type/Sach";
import { formatDateToString } from "../../../core/utils/helpersDayjs";
import type { Lop } from "../../../system/type/Lop";
import { KieuBanQuyen } from "../../type/PhieuDkDetai";
import type { PheDuyetDiInRow } from "../../type";

function InfoItem({ label, value }: { label: string; value?: React.ReactNode }) {
    return (
        <div className="mb-2">
            <span className="text-muted">{label}: </span>
            <span className="fw-bold">{value ?? ""}</span>
        </div>
    );
}

function formatLuaTuoiDisplay(sach: Sach, listLop: Lop[]): string {
    const luaTuoi = sach.LuaTuoi?.trim() ?? "";
    if (luaTuoi.includes("Cấp, Lớp")) {
        return luaTuoi;
    }

    const tenLop = listLop.find((item) => item.id === sach.ID_Lop)?.TenLop ?? "";
    if (luaTuoi && tenLop) {
        return `${luaTuoi}; sách Cấp, Lớp: ${tenLop}`;
    }
    if (luaTuoi) {
        return luaTuoi;
    }
    if (tenLop) {
        return `sách Cấp, Lớp: ${tenLop}`;
    }
    return "";
}

function formatBanQuyenTuNgay(sach: Sach): string {
    const tuNgay = formatDateToString(sach.BanQuyenTuNgay);
    if (!tuNgay) {
        return "";
    }
    if (sach.KieuBanQuyen === KieuBanQuyen.VO_THOI_HAN) {
        return tuNgay;
    }
    const denNgay = formatDateToString(sach.BanQuyenDenNgay);
    return denNgay ? `${tuNgay} đến ngày ${denNgay}` : tuNgay;
}

interface ModalChiTietPheDuyetDiInProps {
    open: boolean;
    row: PheDuyetDiInRow | null;
    listLop?: Lop[];
    onClose: () => void;
}

function ModalChiTietPheDuyetDiIn({ open, row, listLop = [], onClose }: ModalChiTietPheDuyetDiInProps) {
    const [sach, setSach] = useState<Sach | null>(null);
    const [isLoading, setIsLoading] = useState(false);

    useEffect(() => {
        if (!open || !row) {
            setSach(null);
            setIsLoading(false);
            return;
        }

        let cancelled = false;
        setIsLoading(true);
        SachApi.getById(row.id).then((result) => {
            if (cancelled) {
                return;
            }
            setSach(result);
        }).finally(() => {
            if (!cancelled) {
                setIsLoading(false);
            }
        });

        return () => {
            cancelled = true;
        };
    }, [open, row]);

    const displaySach = sach;
    const tenDonVi = displaySach?.don_vi?.TenDonVi ?? row?.TenDonVi ?? "";

    return (
        <Modal
            title="THÔNG TIN CHI TIẾT BẢN THẢO"
            open={open}
            onCancel={onClose}
            width={1024}
            footer={[
                <Button key="close" onClick={onClose}>
                    Đóng
                </Button>,
            ]}
            destroyOnClose
            styles={{ body: { maxHeight: "70vh", overflowY: "auto" } }}
        >
            {isLoading ? (
                <div className="d-flex justify-content-center py-5">
                    <Spin />
                </div>
            ) : displaySach ? (
                <>
                    <InfoItem label="Mã sách" value={displaySach.MaSo} />
                    <InfoItem label="Tên sách" value={displaySach.TenSach} />
                    <InfoItem label="Tác giả" value={displaySach.TacGia} />
                    <InfoItem label="Lứa tuổi" value={formatLuaTuoiDisplay(displaySach, listLop)} />
                    <InfoItem label="Biên tập viên" value={displaySach.BienTapVien || displaySach.BienTapBien} />

                    <div className="row">
                        <div className="col-md-4">
                            <InfoItem label="Mã CXB" value={displaySach.MaSoCXB} />
                        </div>
                        <div className="col-md-4">
                            <InfoItem label="Mã ISBN" value={displaySach.ISBNCode} />
                        </div>
                        <div className="col-md-4">
                            <InfoItem label="Số GPXB" value={displaySach.SoGPXB} />
                        </div>

                        <div className="col-md-4">
                            <InfoItem label="Số trang" value={displaySach.SoTrang} />
                        </div>
                        <div className="col-md-4">
                            <InfoItem label="Khổ sách" value={`${displaySach.Rong ?? ""} x ${displaySach.Dai ?? ""}`} />
                        </div>
                        <div className="col-md-4">
                            <InfoItem label="Hình thức xuất bản" value={displaySach.HTXB ? "Mới" : "Tái bản"} />
                        </div>

                        <div className="col-md-4">
                            <InfoItem label="Năm xuất bản" value={displaySach.NamXuatBan} />
                        </div>
                        <div className="col-md-4">
                            <InfoItem label="Lần tái bản" value={displaySach.LanTaiBan} />
                        </div>
                        <div className="col-md-4">
                            <InfoItem label="Năm tái bản" value={displaySach.NamTaiBan} />
                        </div>

                        <div className="col-md-6">
                            <InfoItem
                                label="Kiểu bản quyền"
                                value={displaySach.KieuBanQuyen === KieuBanQuyen.VO_THOI_HAN ? "Vô thời hạn" : "Có thời hạn"}
                            />
                        </div>
                        <div className="col-md-6">
                            <InfoItem label="Bản quyền từ ngày" value={formatBanQuyenTuNgay(displaySach)} />
                        </div>

                        <div className="col-md-6">
                            <InfoItem label="Thông tin bản quyền" value={displaySach.ThongTinBanQuyen} />
                        </div>
                        <div className="col-md-6">
                            <InfoItem label="Sở hữu bản quyền" value={displaySach.SoHuuBanQuyen} />
                        </div>
                    </div>

                    <InfoItem label="Đơn vị đăng ký" value={tenDonVi} />
                </>
            ) : row ? (
                <>
                    <InfoItem label="Mã sách" value={row.MaSo} />
                    <InfoItem label="Tên sách" value={row.TenSach} />
                    <InfoItem label="Đơn vị đăng ký" value={row.TenDonVi} />
                </>
            ) : null}
        </Modal>
    );
}

export default React.memo(ModalChiTietPheDuyetDiIn);
