import {
    mountReactComponentOnReady,
    readRootDataProps,
} from "../../../core/utils/helpers";
import React, { useState } from "react";
import { useCallback } from "react";
import { Col, Form, Input, Row } from "antd";
import { ComponentTitleStore } from "../../../page/component/componentTitleStore";
import type Monhoc from "../../type/MonHoc";
import { MonhocApi } from "../../api/MonhocApi";
import { LabelOpt, LabelReq } from "../../../page/component/componentLable";

interface MonhocFormFieldsProps {
    form: Partial<Monhoc>;
    setField: (key: keyof Monhoc, value: Monhoc[keyof Monhoc]) => void;
}

const MonhocFormFields = React.memo((props: MonhocFormFieldsProps) => {
    const { form, setField } = props;
    return (
        <Form layout="vertical">
            <Form.Item label={<LabelReq>Mã môn học</LabelReq>}>
                <Input value={form.MaMonHoc ?? ""} onChange={(e) => setField("MaMonHoc", e.target.value)} />
            </Form.Item>
            <Form.Item label={<LabelReq>Tên môn học</LabelReq>}>
                <Input.TextArea rows={3} value={form.TenMonHoc ?? ""} onChange={(e) => setField("TenMonHoc", e.target.value)} />
            </Form.Item>
            <Form.Item label={<LabelReq>Kí hiệu</LabelReq>}>
                <Input value={form.KiHieu ?? ""} onChange={(e) => setField("KiHieu", e.target.value)} />
            </Form.Item>
            <Form.Item label={<LabelOpt>Mô tả</LabelOpt>}>
                <Input.TextArea rows={3} value={form.MoTa ?? ""} onChange={(e) => setField("MoTa", e.target.value)} />
            </Form.Item>
        </Form>
    );
});

function emptyFormState(): Partial<Monhoc> {
    return {
        id: 0,
        MaMonHoc: "",
        TenMonHoc: "",
        MoTa: "",
        KiHieu: "",
    };
}

interface ViewStoreMonhocPageProps {
    monhoc?: Monhoc | null;
}

export const ViewStoreMonhoc = React.memo((props: ViewStoreMonhocPageProps) => {
    const { monhoc } = props;
    const [form, setForm] = useState<Partial<Monhoc>>(() => {
        if (monhoc) {
            return monhoc;
        }
        return { ...emptyFormState() };
    });
    const [submitting, setSubmitting] = useState(false);

    const handleSubmit = useCallback(() => {
        const mapKeysRequired = {
            MaMonHoc: "Mã môn học",
            TenMonHoc: "Tên môn học",
            KiHieu: "Kí hiệu",
        };

        const messageRequired = Object.keys(mapKeysRequired)
            .map((key) =>
                !form[key as keyof Partial<Monhoc>] ? mapKeysRequired[key as keyof typeof mapKeysRequired] : "",
            )
            .filter(Boolean)
            .join(", ");
        if (messageRequired) {
            window._toastbox(`Vui lòng nhập đầy đủ thông tin: ${messageRequired}`, "error");
            return;
        }

        setSubmitting(true);

        MonhocApi.upsert(form)
            .then((res: Monhoc | null) => {
                if (res) {
                    setForm((prev: Partial<Monhoc>) => ({ ...prev, ...res }));
                    window._toastbox("Cập nhật môn học thành công", "success");
                }
            })
            .finally(() => {
                setSubmitting(false);
            });
    }, [form, setForm]);

    const setField = useCallback(<K extends keyof Monhoc>(key: K, value: Monhoc[K]) => {
        setForm((prev: Partial<Monhoc>) => ({ ...prev, [key]: value }));
    }, []);

    return (
        <div className="px-2">
            <ComponentTitleStore
                title={monhoc ? "Cập nhật môn học" : "Thêm mới môn học"}
                callbackSubmit={handleSubmit}
                disabledSubmit={submitting}
            />
            <Row gutter={12}>
                <Col span={16}>
                    <MonhocFormFields form={form} setField={setField} />
                </Col>
            </Row>
        </div>
    );
});

const ROOT_ID = "root-store-monhoc";
const bladeProps: ViewStoreMonhocPageProps = {
    ...readRootDataProps<ViewStoreMonhocPageProps>(ROOT_ID),
};
mountReactComponentOnReady(ROOT_ID, <ViewStoreMonhoc {...bladeProps} />);
