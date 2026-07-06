import React, { useCallback, useState } from "react";
import { Checkbox, Col, Form, Input, Row } from "antd";
import type { DonVi } from "../../../user/type/DonVi";
import { ModalTreeDonvi } from "./ModalTreeDonvi";
import type { HDXB } from "../../type/HDXB";
import { LabelOpt, LabelReq } from "../../../page/component/componentLable";

type SetField = <K extends keyof DonVi>(key: K, value: DonVi[K]) => void;

export function DonviFormFields({
    form,
    setField,
    listDonvi,
    listHDXB,
}: {
    form: Partial<DonVi>;
    setField: SetField;
    listDonvi: DonVi[];
    listHDXB: HDXB[];
}) {
    const [showModalChooseDonvi, setShowModalChooseDonvi] = useState(false);
    const onShowModalChooseDonvi = useCallback(() => setShowModalChooseDonvi(true), []);
    const onHideModalChooseDonvi = useCallback(() => setShowModalChooseDonvi(false), []);

    const handlerChooseDonvi = useCallback(
        (donvi: DonVi) => {
            setField("ParentID", donvi.id);
        },
        [setField]
    );

    return (
        <>
            <Form layout="vertical">
                <Row gutter={8}>
                    <Col span={12}>
                        <Form.Item label={<LabelReq>Mã đơn vị</LabelReq>}>
                            <Input value={form.MaDonVi ?? ""} onChange={(e) => setField("MaDonVi", e.target.value)} />
                        </Form.Item>
                    </Col>
                    <Col span={12}>
                        <Form.Item label={<LabelOpt>Mã số phụ</LabelOpt>}>
                            <Input value={form.MaSoPhu ?? ""} onChange={(e) => setField("MaSoPhu", e.target.value)} />
                        </Form.Item>
                    </Col>
                </Row>

                <Form.Item label={<LabelReq>Tên đơn vị</LabelReq>}>
                    <Input value={form.TenDonVi ?? ""} onChange={(e) => setField("TenDonVi", e.target.value)} />
                </Form.Item>

                <Row gutter={8}>
                    <Col span={12}>
                        <Form.Item label={<LabelReq>Kí hiệu - Xuất bản mới</LabelReq>}>
                            <Input
                                value={form.KiHieuMoi ?? ""}
                                onChange={(e) => setField("KiHieuMoi", e.target.value)}
                                placeholder="Kí hiệu xuất bản mới"
                            />
                        </Form.Item>
                    </Col>
                    <Col span={12}>
                        <Form.Item label={<LabelReq>Kí hiệu - Tái bản</LabelReq>}>
                            <Input
                                value={form.KiHieuTaiBan ?? ""}
                                onChange={(e) => setField("KiHieuTaiBan", e.target.value)}
                                placeholder="Kí hiệu tái bản"
                            />
                        </Form.Item>
                    </Col>
                </Row>

                <Form.Item label={<LabelOpt>Đơn vị cấp trên</LabelOpt>}>
                    <Input
                        value={form.ParentID ? listDonvi.find((d) => d.id === form.ParentID)?.TenDonVi ?? "" : ""}
                        placeholder="Chọn đơn vị cấp trên"
                        readOnly
                        onClick={onShowModalChooseDonvi}
                    />
                </Form.Item>

                <Row gutter={8}>
                    <Col span={12}>
                        <Form.Item label={<LabelOpt>Email</LabelOpt>}>
                            <Input type="email" value={form.Email ?? ""} onChange={(e) => setField("Email", e.target.value)} />
                        </Form.Item>
                    </Col>
                    <Col span={12}>
                        <Form.Item label={<LabelOpt>Số điện thoại</LabelOpt>}>
                            <Input value={form.SoDienThoai ?? ""} onChange={(e) => setField("SoDienThoai", e.target.value)} />
                        </Form.Item>
                    </Col>
                </Row>

                <Row gutter={8}>
                    <Col span={12}>
                        <Form.Item label={<LabelOpt>Website</LabelOpt>}>
                            <Input value={form.Website ?? ""} onChange={(e) => setField("Website", e.target.value)} />
                        </Form.Item>
                    </Col>
                    <Col span={12}>
                        <Form.Item label={<LabelOpt>Số Fax</LabelOpt>}>
                            <Input value={form.SoFax ?? ""} onChange={(e) => setField("SoFax", e.target.value)} />
                        </Form.Item>
                    </Col>
                </Row>

                <Form.Item label={<LabelOpt>Địa chỉ</LabelOpt>}>
                    <Input value={form.DiaChi ?? ""} onChange={(e) => setField("DiaChi", e.target.value)} />
                </Form.Item>

                <Row gutter={8}>
                    <Col span={12}>
                        <Form.Item label={<LabelOpt>Tỉnh thành</LabelOpt>}>
                            <Input value={form.TinhThanh ?? ""} onChange={(e) => setField("TinhThanh", e.target.value)} />
                        </Form.Item>
                    </Col>
                    <Col span={12}>
                        <Form.Item label={<LabelOpt>Mã tỉnh</LabelOpt>}>
                            <Input value={form.MaTinh ?? ""} onChange={(e) => setField("MaTinh", e.target.value)} />
                        </Form.Item>
                    </Col>
                </Row>

                <Row gutter={8}>
                    <Col span={12}><Form.Item><Checkbox checked={!!form.NhaIn} onChange={(e) => setField("NhaIn", e.target.checked)}>Nhà in</Checkbox></Form.Item></Col>
                    <Col span={12}><Form.Item><Checkbox checked={!!form.DauThau} onChange={(e) => setField("DauThau", e.target.checked)}>Đấu thầu</Checkbox></Form.Item></Col>
                    <Col span={12}><Form.Item><Checkbox checked={!!form.BienTap} onChange={(e) => setField("BienTap", e.target.checked)}>Biên tập</Checkbox></Form.Item></Col>
                    <Col span={12}><Form.Item><Checkbox checked={!!form.LienKet} onChange={(e) => setField("LienKet", e.target.checked)}>Liên kết</Checkbox></Form.Item></Col>
                    <Col span={12}><Form.Item><Checkbox checked={!!form.NoiBo} onChange={(e) => setField("NoiBo", e.target.checked)}>Nội bộ</Checkbox></Form.Item></Col>
                    <Col span={12}><Form.Item><Checkbox checked={!!form.IsCreateQDXB} onChange={(e) => setField("IsCreateQDXB", e.target.checked)}>Cấp QĐXB</Checkbox></Form.Item></Col>
                </Row>

                <Row gutter={8}>
                    <Col span={12}>
                        <Form.Item label={<LabelOpt>Thứ tự</LabelOpt>}>
                            <Input
                                type="number"
                                value={form.ThuTu ?? 0}
                                onChange={(e) => {
                                    if (Number(e.target.value) < 0) return;
                                    setField("ThuTu", e.target.value === "" ? 0 : Number(e.target.value));
                                }}
                            />
                        </Form.Item>
                    </Col>
                </Row>

                <Form.Item label={<LabelOpt>Mã số thuế</LabelOpt>}>
                    <Input value={form.MST ?? ""} onChange={(e) => setField("MST", e.target.value)} />
                </Form.Item>

                <Row gutter={8}>
                    <Col span={12}>
                        <Form.Item label={<LabelOpt>Ngân hàng</LabelOpt>}>
                            <Input value={form.TaiNganHang ?? ""} onChange={(e) => setField("TaiNganHang", e.target.value)} />
                        </Form.Item>
                    </Col>
                    <Col span={12}>
                        <Form.Item label={<LabelOpt>Số tài khoản</LabelOpt>}>
                            <Input value={form.SoTaiKhoan ?? ""} onChange={(e) => setField("SoTaiKhoan", e.target.value)} />
                        </Form.Item>
                    </Col>
                </Row>
            </Form>
            <ModalTreeDonvi
                show={showModalChooseDonvi}
                onHide={onHideModalChooseDonvi}
                listDonvi={listDonvi}
                handlerChooseDonvi={handlerChooseDonvi}
                usingselectChoose={true}
            />
        </>
    );
}
