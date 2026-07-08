import React, { useCallback, useState } from "react";
import { Col, Input, Row } from "antd";
import DatePickerAntd from "../../../core/utils/DatePicker";
import { convertValueToDayjs } from "../../../core/utils/helpersDayjs";
import type { QDIn } from "../../type/QDIn";

interface FormFieldProps {
    form: Partial<QDIn>;
    setField: (key: keyof QDIn, value: Partial<QDIn>[keyof QDIn]) => void;
}

const FormFieldQDIn = React.memo((props: FormFieldProps) => {
    const { form, setField } = props;
    const labelCls = "form-label mb-1 small text-muted fw-semibold";
    return (
        <div className="border rounded-3 p-3 bg-white shadow-sm mb-3">
                <Row gutter={[16, 12]} align="bottom">
                    <Col xs={24} md={8}>
                        <label className={labelCls}>Số QĐ</label>
                        <Input
                            size="small"
                            value={form.SoQD ?? ""}
                            onChange={(e) => setField("SoQD", e.target.value)}
                            placeholder="Số QĐ"
                        />
                    </Col>
                    <Col xs={24} md={8}>
                        <label className={labelCls}>Ngày ra QĐ</label>
                        <DatePickerAntd
                            size="small"
                            style={{ width: "100%" }}
                            format="DD/MM/YYYY"
                            placeholder="Ngày ra QĐ"
                            value={convertValueToDayjs(form.NgayQD)}
                            onChange={(d) => setField("NgayQD", d ? d.toDate() : undefined)}
                        />
                    </Col>
                    <Col xs={24} md={8}>
                        <label className={labelCls}>Năm kế hoạch</label>
                        <Input
                            size="small"
                            value={form.NamKeHoach ?? ""}
                            onChange={(e) => setField("NamKeHoach", e.target.value)}
                            placeholder="Năm"
                        />
                    </Col>

                    <Col span={24}>
                        <label className={labelCls}>Tiêu đề</label>
                        <Input
                            size="small"
                            value={form.TieuDe ?? ""}
                            onChange={(e) => setField("TieuDe", e.target.value)}
                            placeholder="Tiêu đề"
                        />
                    </Col>

                    <Col span={24}>
                        <label className={labelCls}>Căn cứ</label>
                        <Input
                            size="small"
                            value={form.CanCu ?? ""}
                            onChange={(e) => setField("CanCu", e.target.value)}
                            placeholder="Căn cứ"
                        />
                    </Col>

                    <Col span={24}>
                        <label className={labelCls}>Đơn vị ra QĐ</label>
                        <Input
                            size="small"
                            value={form.TenDonViQD ?? ""}
                            onChange={(e) => setField("TenDonViQD", e.target.value)}
                            placeholder="Mã đơn vị - Tên đơn vị"
                        />
                    </Col>

                    <Col span={24}>
                        <label className={labelCls}>Địa phương</label>
                        <Input
                            size="small"
                            value={form.DiaDanh ?? ""}
                            onChange={(e) => setField("DiaDanh", e.target.value)}
                            placeholder="Địa phương"
                        />
                    </Col>

                    <Col span={24}>
                        <label className={labelCls}>Người ký</label>
                        <Input
                            size="small"
                            value={form.TenNguoiKi ?? ""}
                            onChange={(e) => setField("TenNguoiKi", e.target.value)}
                            placeholder="Người ký"
                        />
                    </Col>

                    <Col span={24}>
                        <label className={labelCls}>Chức vụ</label>
                        {/* <Input
                            size="small"
                            value={form.ChucVu ?? ""}
                            onChange={(e) => setField("ChucVu", e.target.value)}
                            placeholder="Chức vụ"
                        /> */}
                    </Col>

                    <Col span={24}>
                        <label className={labelCls}>Nơi nhận</label>
                        <Input.TextArea
                            rows={5}
                            value={form.NoiNhan ?? ""}
                            onChange={(e) => setField("NoiNhan", e.target.value)}
                            placeholder="Nơi nhận"
                        />
                    </Col>
                </Row>
            </div>
    );
});

FormFieldQDIn.displayName = "FormFieldQDIn";

export default FormFieldQDIn;
