import React from "react";
import { Button, Col, Modal, Row } from "antd";
import { useManagePhieuDkDetaiStore } from "../../store/PhieuDkDetai/managePhieuDkDetaiStore";
import { formatDateToString } from "../../../core/utils/helpersDayjs";
import { useDataViewStore } from "../../../system/store/useDataViewStore";

function InfoLine({ label, value }: { label: string; value: React.ReactNode }) {
    return (
        <div className="mb-3">
            <span className="text-muted">{label}: </span>
            <span className="fw-semibold">{value || "-"}</span>
        </div>
    );
}

export const ModalInfoPhieuDkDetaiComponent = React.memo(() => {
    const showModalInfoPhieuDkDetai = useManagePhieuDkDetaiStore((state) => state.showModalInfoPhieuDkDetai);
    const setShowModalInfoPhieuDkDetai = useManagePhieuDkDetaiStore((state) => state.setShowModalInfoPhieuDkDetai);
    const PhieuDkDetaiContext = useManagePhieuDkDetaiStore((state) => state.PhieuDkDetaiContext);
    const listMangsach = useDataViewStore((state) => state.listMangsach);
    const listDoituong = useDataViewStore((state) => state.listDoituong);
    if (PhieuDkDetaiContext == null) {
        return null;
    }

    const tenMangSach = listMangsach.find((item) => item.id === PhieuDkDetaiContext.ID_MangSach)?.TenMang ?? "";
    const kieuBanQuyen = PhieuDkDetaiContext.KieuBanQuyen === 1 ? "Có thời hạn" : "Không thời hạn";

    return (
        <Modal
            title="THÔNG TIN CHI TIẾT PHIẾU ĐĂNG KÝ ĐỀ TÀI"
            open={showModalInfoPhieuDkDetai}
            onCancel={() => setShowModalInfoPhieuDkDetai(false)}
            width={1140}
            footer={[
                <Button key="close" onClick={() => setShowModalInfoPhieuDkDetai(false)}>
                    Đóng
                </Button>,
            ]}
            styles={{ body: { maxHeight: "70vh", overflowY: "auto" } }}
        >
            <Row gutter={[16, 0]}>
                <Col xs={24} md={8}>
                    <InfoLine label="Mã số" value={PhieuDkDetaiContext.MaSo} />
                </Col>
                <Col xs={24} md={8}>
                    <InfoLine label="Ngày đăng ký" value={formatDateToString(PhieuDkDetaiContext.NgayDK)} />
                </Col>
            </Row>
            <InfoLine label="Tên đề tài" value={PhieuDkDetaiContext.TenDeTai} />
            <InfoLine label="Tác giả" value={PhieuDkDetaiContext.TacGia} />
            <InfoLine
                label="Lứa tuổi"
                value={
                    PhieuDkDetaiContext.TypeLuaTuoi
                        ? listDoituong.find((item) => item.id === PhieuDkDetaiContext.TypeLuaTuoi)?.TenDoiTuong ?? ""
                        : ""
                }
            />
            <InfoLine label="Địa chỉ" value={PhieuDkDetaiContext.DiaChi} />
            <InfoLine label="Biên tập viên" value={PhieuDkDetaiContext.BienTapVien} />

            <Row gutter={[16, 0]}>
                <Col xs={24} md={8}>
                    <InfoLine label="Ngữ xuất bản" value={PhieuDkDetaiContext.NguXuatBan} />
                    <InfoLine label="Lần tái bản" value={PhieuDkDetaiContext.LanTaiBan} />
                    <InfoLine label="Thời điểm có đủ bản thảo" value={PhieuDkDetaiContext.ThoiDiemCoDuBT} />
                    <InfoLine label="Loại XBP" value={PhieuDkDetaiContext.ID_LoaiXBP} />
                </Col>
                <Col xs={24} md={8}>
                    <InfoLine label="Hình thức xuất bản" value={PhieuDkDetaiContext.HTXB ? "Mới" : "Tái bản"} />
                    <InfoLine label="Khổ sách" value={`${PhieuDkDetaiContext.Rong} x ${PhieuDkDetaiContext.Dai}`} />
                    <InfoLine label="Thời điểm ra sách" value={PhieuDkDetaiContext.ThoiDiemRaSach} />
                    <InfoLine label="Giá bìa" value={PhieuDkDetaiContext.GiaBia} />
                </Col>
                <Col xs={24} md={8}>
                    <InfoLine label="Năm tái bản" value={PhieuDkDetaiContext.NamTaiBan} />
                    <InfoLine label="Mảng sách" value={tenMangSach} />
                    <InfoLine label="Số màu in bìa" value={PhieuDkDetaiContext.MauInBia} />
                </Col>
            </Row>

            <Row gutter={[16, 0]}>
                <Col xs={24} md={12}>
                    <InfoLine label="Sở hữu bản quyền" value={PhieuDkDetaiContext.SoHuuBanQuyen} />
                </Col>
                <Col xs={24} md={12}>
                    <InfoLine label="Kiểu bản quyền" value={kieuBanQuyen} />
                    <InfoLine
                        label="Bản quyền từ ngày"
                        value={`${formatDateToString(PhieuDkDetaiContext.BanQuyenTuNgay)} đến ngày ${formatDateToString(
                            PhieuDkDetaiContext.BanQuyenDenNgay,
                        )}`}
                    />
                </Col>
            </Row>
        </Modal>
    );
});
