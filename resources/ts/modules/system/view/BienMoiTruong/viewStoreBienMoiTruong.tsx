import {
    mountReactComponentOnReady,
    readRootDataProps,
} from "../../../core/utils/helpers";
import React, { useState } from "react";
import { useCallback } from "react";
import { Col, Form, Input, Row } from "antd";
import { ComponentTitleStore } from "../../../page/component/componentTitleStore";
import type BienMoiTruong from "../../type/BienMoiTruong";
import { LabelReq } from "../../../page/component/componentLable";
import { BienMoiTruongApi } from "../../api/BienMoiTruongApi";

function emptyFormState(): Partial<BienMoiTruong> {
    return {
        id: 0,
        ConfigName: "",
        ConfigNotes: "",
        ConfigValue: "",
    };
}

interface ViewStoreBienMoiTruongProps {
    bienMoiTruong?: BienMoiTruong | null;
}

export const ViewStoreBienMoiTruong = React.memo((props: ViewStoreBienMoiTruongProps) => {
    const { bienMoiTruong } = props;
    const [form, setForm] = useState<Partial<BienMoiTruong>>(() => {
        let dataForm = emptyFormState();
        if (bienMoiTruong && Object.keys(bienMoiTruong).length > 0) {
            dataForm = {
                ...dataForm,
                ...bienMoiTruong,
            };
        }
        return dataForm;
    });
    const [submitting, setSubmitting] = useState(false);

    const setField = useCallback(<K extends keyof BienMoiTruong>(key: K, value: BienMoiTruong[K]) => {
        setForm((prev: Partial<BienMoiTruong>) => ({ ...prev, [key]: value }));
    }, []);

    const handleSubmit = useCallback(() => {
        const mapKeysRequired = {
            ConfigName: "Tên biến",
            ConfigNotes: "Mô tả",
            ConfigValue: "Giá trị",
        };

        const messageRequired = Object.keys(mapKeysRequired)
            .map((key) =>
                !form[key as keyof BienMoiTruong] ? mapKeysRequired[key as keyof typeof mapKeysRequired] : "",
            )
            .filter(Boolean)
            .join(", ");
        if (messageRequired) {
            window._toastbox(`Vui lòng nhập đầy đủ thông tin: ${messageRequired}`, "error");
            return;
        }

        setSubmitting(true);

        BienMoiTruongApi.upsert(form)
            .then((res: BienMoiTruong | null) => {
                if (res) {
                    window._toastbox(`${form.id ? "Cập nhật" : "Thêm mới"} biến mới trường ${form.ConfigName ?? ""} thành công`, "success");
                    setForm((prev: Partial<BienMoiTruong>) => ({ ...prev, ...res }));
                }
            })
            .finally(() => {
                setSubmitting(false);
            });
    }, [form, setForm]);

    return (
        <div className="px-2">
            <ComponentTitleStore
                title={bienMoiTruong ? "Cập nhật biến mới trường" : "Thêm mới biến mới trường"}
                callbackSubmit={handleSubmit}
                disabledSubmit={submitting}
            />
            <Row gutter={12}>
                <Col span={16}>
                    <Form layout="vertical">
                        <Form.Item label={<LabelReq>Tên biến</LabelReq>}>
                            <Input value={form.ConfigName ?? ""} onChange={(e) => setField("ConfigName", e.target.value)} />
                        </Form.Item>
                        <Form.Item label={<LabelReq>Mô tả</LabelReq>}>
                            <Input.TextArea rows={3} value={form.ConfigNotes ?? ""} onChange={(e) => setField("ConfigNotes", e.target.value)} />
                        </Form.Item>
                        <Form.Item label={<LabelReq>Giá trị</LabelReq>}>
                            <Input.TextArea rows={3} value={form.ConfigValue ?? ""} onChange={(e) => setField("ConfigValue", e.target.value)} />
                        </Form.Item>
                    </Form>
                </Col>
            </Row>
        </div>
    );
});

const ROOT_ID = "root-store-bien-moi-truong";
const bladeProps = readRootDataProps<ViewStoreBienMoiTruongProps>(ROOT_ID) ?? {};
mountReactComponentOnReady(ROOT_ID, <ViewStoreBienMoiTruong {...bladeProps} />);
