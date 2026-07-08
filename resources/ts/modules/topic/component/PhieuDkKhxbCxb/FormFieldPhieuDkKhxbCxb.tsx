import React from "react";
import { Checkbox, Col, Form, Input, Row } from "antd";
import DatePickerAntd from "../../../core/utils/DatePicker";
import SelectAntd from "../../../core/utils/SelectAntd";
import { convertValueToDayjs } from "../../../core/utils/helpersDayjs";
import { LabelReq } from "../../../page/component/componentLable";
import type { PhieuDkKhxbCxb } from "../../type";
import type { User } from "../../../user/type";

interface FormFieldPhieuDkKhxbCxbProps {
    form: Partial<PhieuDkKhxbCxb>;
    setField: <K extends keyof PhieuDkKhxbCxb>(key: K, value: PhieuDkKhxbCxb[K]) => void;
    listSigners: User[];
    isMaSoReadOnly?: boolean;
}

export const FormFieldPhieuDkKhxbCxb = React.memo(({ form, setField, listSigners, isMaSoReadOnly = true }: FormFieldPhieuDkKhxbCxbProps) => {
    const signerOptions = listSigners
        .filter((user) => user.NguoiKi)
        .map((user) => ({
            value: user.id,
            label: user.HoTen || user.UserName,
        }));

    return (
        <Form layout="vertical" className="mt-2">
            <Row gutter={16}>
                <Col xs={24} md={12} lg={8}>
                    <Form.Item label="Mã số phiếu">
                        <Input
                            value={form.MaSo ?? ""}
                            readOnly={isMaSoReadOnly}
                            disabled={isMaSoReadOnly}
                        />
                    </Form.Item>
                </Col>
                <Col xs={24} md={12} lg={8}>
                    <Form.Item label="Ngày đăng ký">
                        <DatePickerAntd
                            className="w-100"
                            format="DD/MM/YYYY"
                            value={convertValueToDayjs(form.NgayDK)}
                            onChange={(date) => setField("NgayDK", date ? date.toDate() : (null as unknown as Date))}
                        />
                    </Form.Item>
                </Col>
            </Row>

            <Form.Item label={<LabelReq>Tiêu đề</LabelReq>}>
                <Input
                    value={form.TieuDe ?? ""}
                    onChange={(e) => setField("TieuDe", e.target.value)}
                />
            </Form.Item>

            <Form.Item label={<LabelReq>Nội dung</LabelReq>}>
                <Input
                    value={form.NoiDung ?? ""}
                    onChange={(e) => setField("NoiDung", e.target.value)}
                />
            </Form.Item>

            <Form.Item label="Nơi nhận">
                <Input.TextArea
                    rows={3}
                    value={form.NoiNhan2 ?? ""}
                    onChange={(e) => setField("NoiNhan2", e.target.value)}
                />
            </Form.Item>

            <Row gutter={16} align="middle">
                <Col xs={24} md={8}>
                    <Form.Item>
                        <Checkbox
                            checked={!!form.KiThay}
                            onChange={(e) => setField("KiThay", e.target.checked)}
                        >
                            Là kí thay
                        </Checkbox>
                    </Form.Item>
                </Col>
                <Col xs={24} md={16}>
                    <Form.Item label="Người kí">
                        <SelectAntd<number>
                            className="w-100"
                            allowClear
                            showSearch
                            placeholder="Chọn người kí"
                            value={form.ID_NguoiKi && form.ID_NguoiKi > 0 ? form.ID_NguoiKi : null}
                            options={signerOptions}
                            onChange={(value) => setField("ID_NguoiKi", value ?? null)}
                            optionFilterProp="label"
                        />
                    </Form.Item>
                </Col>
            </Row>
        </Form>
    );
});
