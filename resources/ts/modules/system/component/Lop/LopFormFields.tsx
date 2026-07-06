import React from "react";
import { Form, Input } from "antd";
import { LabelReq } from "../../../page/component/componentLable";
import type Lop from "../../type/Lop";

interface LopFormFieldsProps {
    form: Partial<Lop>;
    setField: (key: keyof Lop, value: Lop[keyof Lop]) => void;
}

export const LopFormFields = React.memo((props: LopFormFieldsProps) => {
    const { form, setField } = props;
    return (
        <Form layout="vertical" size="middle">
            <Form.Item label={<LabelReq>Mã lớp</LabelReq>}>
                <Input
                    value={form.MaLop ?? ""}
                    onChange={(e) => setField("MaLop", e.target.value)}
                />
            </Form.Item>
            <Form.Item label={<LabelReq>Tên lớp</LabelReq>}>
                <Input.TextArea
                    rows={3}
                    value={form.TenLop ?? ""}
                    onChange={(e) => setField("TenLop", e.target.value)}
                />
            </Form.Item>
            <Form.Item label={<LabelReq>Kí hiệu</LabelReq>}>
                <Input
                    value={form.KiHieu ?? ""}
                    onChange={(e) => setField("KiHieu", e.target.value)}
                />
            </Form.Item>
        </Form>
    );
});

LopFormFields.displayName = "LopFormFields";
