import React from "react";
import { Form, Input } from "antd";
import type { Nhom } from "../../type/Nhom";
import { LabelReq } from "../../../page/component/componentLable";

interface NhomFormFieldsProps {
    form: Partial<Nhom>;
    setField: (key: keyof Nhom, value: Nhom[keyof Nhom]) => void;
}

export const NhomFormFields = React.memo((props: NhomFormFieldsProps) => {
    const { form, setField } = props;
    return (
        <Form layout="vertical">
            <Form.Item label={<LabelReq>Mã nhóm</LabelReq>}>
                <Input value={form.MaNhomNSD ?? ""} onChange={(e) => setField("MaNhomNSD", e.target.value)} />
            </Form.Item>
            <Form.Item label={<LabelReq>Tên nhóm</LabelReq>}>
                <Input.TextArea rows={3} value={form.TenNhomNSD ?? ""} onChange={(e) => setField("TenNhomNSD", e.target.value)} />
            </Form.Item>
        </Form>
    );
});
