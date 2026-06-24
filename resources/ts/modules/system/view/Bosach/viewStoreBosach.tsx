import {
    mountReactComponentOnReady,
    readRootDataProps,
} from "../../../core/utils/helpers";
import React, { useState } from "react";
import { useCallback } from "react";
import { Col, Form, Input, Row } from "antd";
import { ComponentTitleStore } from "../../../page/component/componentTitleStore";
import type { Bosach } from "../../type";
import { BosachApi } from "../../api/BosachApi";
import { LabelOpt, LabelReq } from "../../../page/component/componentLable";

function emptyFormState(): Partial<Bosach> {
    return {
        id: 0,
        MaBo: "",
        TenBo: "",
        MoTa: "",
        KiHieu: "",
    };
}

interface ViewStoreBosachProps {
    bosach?: Bosach | null;
}

export const ViewStoreBosach = React.memo((props: ViewStoreBosachProps) => {
    const { bosach } = props;
    const [form, setForm] = useState<Partial<Bosach>>(() => {
        let dataForm = emptyFormState();
        if (bosach && Object.keys(bosach).length > 0) {
            dataForm = {
                ...dataForm,
                ...bosach,
            };
        }
        return dataForm;
    });
    const [submitting, setSubmitting] = useState(false);

    const setField = useCallback(<K extends keyof Bosach>(key: K, value: Bosach[K]) => {
        setForm((prev: Partial<Bosach>) => ({ ...prev, [key]: value }));
    }, []);

    const handleSubmit = useCallback(() => {
        const mapKeysRequired = {
            MaBo: "Mã bộ sách",
            TenBo: "Tên bộ sách",
            MoTa: "Mô tả",
            KiHieu: "Kí hiệu",
        };

        const messageRequired = Object.keys(mapKeysRequired)
            .map((key) =>
                !form[key as keyof Bosach] ? mapKeysRequired[key as keyof typeof mapKeysRequired] : "",
            )
            .filter(Boolean)
            .join(", ");
        if (messageRequired) {
            window._toastbox(`Vui lòng nhập đầy đủ thông tin: ${messageRequired}`, "error");
            return;
        }

        setSubmitting(true);

        BosachApi.upsert(form)
            .then((res: Bosach | null) => {
                if (res) {
                    window._toastbox(`${form.id ? "Cập nhật" : "Thêm mới"} bộ sách ${form.MaBo ?? ""} thành công`, "success");
                    setForm((prev: Partial<Bosach>) => ({ ...prev, ...res }));
                }
            })
            .finally(() => {
                setSubmitting(false);
            });
    }, [form, setForm]);

    return (
        <div className="px-2">
            <ComponentTitleStore
                title={bosach ? "Cập nhật bộ sách" : "Thêm mới bộ sách"}
                callbackSubmit={handleSubmit}
                disabledSubmit={submitting}
            />
            <Row gutter={12}>
                <Col span={16}>
                    <Form layout="vertical">
                        <Form.Item label={<LabelReq>Mã bộ sách</LabelReq>}>
                            <Input value={form.MaBo ?? ""} onChange={(e) => setField("MaBo", e.target.value)} />
                        </Form.Item>
                        <Form.Item label={<LabelReq>Tên bộ sách</LabelReq>}>
                            <Input.TextArea rows={3} value={form.TenBo ?? ""} onChange={(e) => setField("TenBo", e.target.value)} />
                        </Form.Item>
                        <Form.Item label={<LabelReq>Kí hiệu</LabelReq>}>
                            <Input value={form.KiHieu ?? ""} onChange={(e) => setField("KiHieu", e.target.value)} />
                        </Form.Item>
                        <Form.Item label={<LabelOpt>Mô tả</LabelOpt>}>
                            <Input.TextArea rows={3} value={form.MoTa ?? ""} onChange={(e) => setField("MoTa", e.target.value)} />
                        </Form.Item>
                    </Form>
                </Col>
            </Row>
        </div>
    );
});

const ROOT_ID = "root-store-bosach";
const bladeProps = readRootDataProps<ViewStoreBosachProps>(ROOT_ID) ?? {};
mountReactComponentOnReady(ROOT_ID, <ViewStoreBosach {...bladeProps} />);
