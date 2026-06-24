// import { Button, Col, Input, Modal, Row } from "antd";
// import React, { useCallback, useEffect, useState } from "react";
// import type { CT_QD_In } from "../../type";

// function emptyCT_QD_InForm(): CT_QD_In {
//     const y = String(new Date().getFullYear());
//     return {
//         id: 0,
//         ID_QD_IN: 0,
//         ID_Sach: 0,
//         ID_QDXB: 0,
//         THBS: "",
//         SoLuongSauDieuChinh: 0,
//         SoLuongIn: 0,
//         DaGui: false,
//         KhoaGuiNhan: "",
//         GiayInRout: "",
//         GiayInBia: "",
//         HDXB: false,
//         LanTaiBan: 0,
//         SoTrang: 0,
//         KhoSach: "",
//         GiayBia: "",
//         MauInRuot: "",
//         MauInBia: "",
//         BienTapVien: "",
//         MaSoSach: "",
//         TenSach: "",
//         MaSoCXB: "",
//         MaDonViIn: "",
//         TacGia: "",
//         IDCT_VMS: "",
//         TinhTrangXuatBan: false,
//         DiaChiDonViIn: "",
//     };
// }

// const labelCls = "form-label mb-1 small text-muted fw-semibold";

// interface ModalChooseBookProps {
//     visible: boolean;
//     onClose: () => void;
//     onChoose: (book: CT_QD_In) => void;
// }

// const ModalChooseBook = React.memo((props: ModalChooseBookProps) => {
//     const { visible, onClose, onChoose } = props;
//     const [detail, setDetail] = useState<CT_QD_In>(emptyCT_QD_InForm);

//     useEffect(() => {
//         if (visible) {
//             setDetail(emptyCT_QD_InForm());
//         }
//     }, [visible]);

//     const setField = useCallback(<K extends keyof CT_QD_In>(key: K, value: CT_QD_In[K]) => {
//         setDetail((prev) => ({ ...prev, [key]: value }));
//     }, []);

//     const handleLuu = useCallback(() => {
//         onChoose(detail);
//         onClose();
//     }, [detail, onChoose, onClose]);

//     return (
//         <Modal
//             title="THÊM MỚI CHI TIẾT QUYẾT ĐỊNH IN"
//             open={visible}
//             onCancel={onClose}
//             width={980}
//             destroyOnHidden
//             footer={
//                 <div className="d-flex justify-content-end gap-2">
//                     <Button onClick={onClose}>Hủy</Button>
//                     <Button type="primary" onClick={handleLuu}>
//                         Lưu
//                     </Button>
//                 </div>
//             }
//             styles={{ body: { maxHeight: "75vh", overflowY: "auto", paddingTop: 12 } }}
//         >
//             <div className="bg-light rounded-2 p-3">
//                 <Row gutter={[12, 10]} align="bottom">
//                     <Col span={24}>
//                         <label className={labelCls}>Năm XB/TB</label>
//                         <Input
//                             size="small"
//                             style={{ maxWidth: 120 }}
//                             value={detail.NamXuatBan}
//                             onChange={(e) => setField("NamXuatBan", e.target.value)}
//                         />
//                     </Col>
//                     <Col span={24}>
//                         <label className={labelCls}>Tên sách</label>
//                         <Input
//                             size="small"
//                             allowClear
//                             value={detail.TenSach}
//                             onChange={(e) => setField("TenSach", e.target.value)}
//                             placeholder="Tên sách"
//                         />
//                     </Col>
//                     <Col span={24}>
//                         <label className={labelCls}>Biên tập viên</label>
//                         <Input
//                             size="small"
//                             value={detail.BienTapVien}
//                             onChange={(e) => setField("BienTapVien", e.target.value)}
//                             placeholder="Gõ * để tìm kiếm tất cả"
//                         />
//                     </Col>
//                     <Col span={24}>
//                         <label className={labelCls}>Tác giả</label>
//                         <Input
//                             size="small"
//                             value={detail.TacGia}
//                             onChange={(e) => setField("TacGia", e.target.value)}
//                             placeholder="Tác giả"
//                         />
//                     </Col>

//                     <Col xs={12} sm={6}>
//                         <label className={labelCls}>SL in</label>
//                         <Input size="small" value={detail.SoLuong} onChange={(e) => setField("SoLuong", e.target.value)} />
//                     </Col>
//                     <Col xs={12} sm={6}>
//                         <label className={labelCls}>SL đăng ký</label>
//                         <Input size="small" value={detail.SoLuongConLai} onChange={(e) => setField("SoLuongConLai", e.target.value)} />
//                     </Col>
//                     <Col xs={12} sm={6}>
//                         <label className={labelCls}>SL đã cấp</label>
//                         <Input size="small" value={detail.SoLuong} onChange={(e) => setField("SoLuong", e.target.value)} />
//                     </Col>
//                     <Col xs={12} sm={6}>
//                         <label className={labelCls}>SL còn lại</label>
//                         <Input size="small" value={detail.SoLuongConLai} onChange={(e) => setField("SoLuongConLai", e.target.value)} />
//                     </Col>

//                     <Col xs={12} sm={6}>
//                         <label className={labelCls}>Mã số CXB</label>
//                         <Input size="small" value={detail.ISBNCode} onChange={(e) => setField("ISBNCode", e.target.value)} />
//                     </Col>
//                     <Col xs={12} sm={6}>
//                         <label className={labelCls}>HTXB</label>
//                         <Input size="small" value={detail.HTXB ? "Có" : "Không"} onChange={(e) => setField("HTXB", e.target.value === "Có" ? true : false)} />
//                     </Col>
//                     <Col xs={12} sm={6}>
//                         <label className={labelCls}>Lần TB</label>
//                         <Input size="small" value={detail.LanTaiBan} onChange={(e) => setField("LanTaiBan", e.target.value)} />
//                     </Col>
//                     <Col xs={12} sm={6}>
//                         <label className={labelCls}>Số trang</label>
//                         <Input size="small" value={detail.SoTrang} onChange={(e) => setField("SoTrang", e.target.value)} />
//                     </Col>

//                     <Col xs={12} sm={6}>
//                         <label className={labelCls}>Khổ sách</label>
//                         <Input size="small" value={detail.KhoSach} onChange={(e) => setField("KhoSach", e.target.value)} />
//                     </Col>
//                     <Col xs={12} sm={6}>
//                         <label className={labelCls}>Màu in ruột</label>
//                         <Input size="small" value={detail.MauInRuot} onChange={(e) => setField("MauInRuot", e.target.value)} />
//                     </Col>
//                     <Col xs={12} sm={6}>
//                         <label className={labelCls}>Màu in bìa</label>
//                         <Input size="small" value={detail.MauInBia} onChange={(e) => setField("MauInBia", e.target.value)} />
//                     </Col>
//                     <Col xs={12} sm={6}>
//                         <label className={labelCls}>Giá bìa</label>
//                         <Input size="small" value={detail.GiaBia} onChange={(e) => setField("GiaBia", e.target.value)} />
//                     </Col>

//                     <Col xs={24} md={12}>
//                         <label className={labelCls}>THBS</label>
//                         <Input size="small" value={detail.THBS} onChange={(e) => setField("THBS", e.target.value)} />
//                     </Col>
//                     <Col xs={12} md={6}>
//                         <label className={labelCls}>Giấy in ruột</label>
//                         <Input size="small" value={detail.GiayInRuot} onChange={(e) => setField("GiayInRuot", e.target.value)} />
//                     </Col>
//                     <Col xs={12} md={6}>
//                         <label className={labelCls}>Giấy in bìa</label>
//                         <Input size="small" value={detail.GiayInBia} onChange={(e) => setField("GiayInBia", e.target.value)} />
//                     </Col>

//                     <Col xs={12} sm={6}>
//                         <label className={labelCls}>Là sách điện tử</label>
//                         <div className="pt-1">
//                             <Checkbox checked={detail.LaSachDienTu} onChange={(e) => setField("LaSachDienTu", e.target.checked)} />
//                         </div>
//                     </Col>
//                     <Col xs={12} sm={6}>
//                         <label className={labelCls}>Số byte</label>
//                         <Input size="small" value={detail.SoByte} onChange={(e) => setField("SoByte", e.target.value)} />
//                     </Col>
//                     <Col xs={12} sm={6}>
//                         <label className={labelCls}>Định dạng tệp</label>
//                         <Input size="small" value={detail.DinhDangTep} onChange={(e) => setField("DinhDangTep", e.target.value)} />
//                     </Col>
//                     <Col xs={12} sm={6}>
//                         <label className={labelCls}>Lần nối bản</label>
//                         <Input size="small" value={detail.LanNoiBan} onChange={(e) => setField("LanNoiBan", e.target.value)} />
//                     </Col>

//                     <Col xs={24} md={8}>
//                         <label className={labelCls}>TH nhập kho</label>
//                         <DatePickerAntd
//                             size="small"
//                             style={{ width: "100%" }}
//                             format="DD/MM/YYYY"
//                             placeholder="__/__/____"
//                             allowClear
//                             value={convertValueToDayjs(detail.THNhapKho ?? undefined)}
//                             onChange={(d) => setField("THNhapKho", d ? d.toDate() : null)}
//                         />
//                     </Col>
//                     <Col xs={24} md={16}>
//                         <label className={labelCls}>Địa chỉ cung cấp</label>
//                         <Input size="small" value={detail.DiaChiCungCap} onChange={(e) => setField("DiaChiCungCap", e.target.value)} />
//                     </Col>

//                     <Col span={24}>
//                         <label className={labelCls}>Đơn vị in</label>
//                         <Input size="small" value={detail.DonViIn} onChange={(e) => setField("DonViIn", e.target.value)} />
//                     </Col>
//                     <Col span={24}>
//                         <label className={labelCls}>Địa chỉ đơn vị in</label>
//                         <Input size="small" value={detail.DiaChiDonViIn} onChange={(e) => setField("DiaChiDonViIn", e.target.value)} />
//                     </Col>
//                     <Col span={24}>
//                         <label className={labelCls}>Cơ sở in</label>
//                         <Input size="small" value={detail.CoSoIn} onChange={(e) => setField("CoSoIn", e.target.value)} />
//                     </Col>
//                     <Col span={24}>
//                         <label className={labelCls}>Ghi chú</label>
//                         <Input.TextArea rows={4} value={detail.GhiChu} onChange={(e) => setField("GhiChu", e.target.value)} placeholder="Ghi chú" />
//                     </Col>
//                 </Row>
//             </div>
//         </Modal>
//     );
// });

// ModalChooseBook.displayName = "ModalChooseBook";
// export default ModalChooseBook;
