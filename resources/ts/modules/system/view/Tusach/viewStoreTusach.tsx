import {
    mountReactComponentOnReady,
    readRootDataProps,
} from "../../../core/utils/helpers";
import React, { useState } from "react";
import { useCallback } from "react";
import { Col, Form, Input, Row } from "antd";
import { ComponentTitleStore } from "../../../page/component/componentTitleStore";
import type { Tusach } from "../../type/TuSach";
import { TusachApi } from "../../api/TusachApi";
import { LabelOpt, LabelReq } from "../../../page/component/componentLable";

function emptyFormState(): Partial<Tusach> {
    return {
        id: 0,
        MaTuSach: "",
        TenTuSach: "",
        MoTa: "",
    };
}

interface ViewStoreTusachProps {
    tusach?: Tusach | null;
}

export const ViewStoreTusach = React.memo((props: ViewStoreTusachProps) => {
    const { tusach } = props;
    const [form, setForm] = useState<Partial<Tusach>>(() => {
        if (tusach) {
            return tusach;
        }
        return emptyFormState();
    });
    const [submitting, setSubmitting] = useState(false);

    const setField = useCallback(<K extends keyof Tusach>(key: K, value: Tusach[K]) => {
        setForm((prev: Partial<Tusach>) => ({ ...prev, [key]: value }));
    }, []);

    const handleSubmit = useCallback(() => {
        const mapKeysRequired = {
            MaTuSach: "Mã tủ sách",
            TenTuSach: "Tên tủ sách",
        };

        const messageRequired = Object.keys(mapKeysRequired)
            .map((key) =>
                !form[key as keyof Tusach] ? mapKeysRequired[key as keyof typeof mapKeysRequired] : "",
            )
            .filter(Boolean)
            .join(", ");
        if (messageRequired) {
            window._toastbox(`Vui lòng nhập đầy đủ thông tin: ${messageRequired}`, "error");
            return;
        }

        setSubmitting(true);

        TusachApi.upsert(form)
            .then((res: Tusach | null) => {
                if (res) {
                    window._toastbox(`${form.id ? "Cập nhật" : "Thêm mới"} tủ sách ${form.MaTuSach ?? ""} thành công`, "success");
                    setForm((prev: Partial<Tusach>) => ({ ...prev, ...res }));
                }
            })
            .finally(() => {
                setSubmitting(false);
            });
    }, [form, setForm]);

    return (
        <div className="px-2">
            <ComponentTitleStore
                title={tusach ? "Cập nhật tủ sách" : "Thêm mới tủ sách"}
                callbackSubmit={handleSubmit}
                disabledSubmit={submitting}
            />
            <Row gutter={12}>
                <Col span={16}>
                    <Form layout="vertical">
                        <Form.Item label={<LabelReq>Mã tủ sách</LabelReq>}>
                            <Input value={form.MaTuSach ?? ""} onChange={(e) => setField("MaTuSach", e.target.value)} />
                        </Form.Item>
                        <Form.Item label={<LabelReq>Tên tủ sách</LabelReq>}>
                            <Input value={form.TenTuSach ?? ""} onChange={(e) => setField("TenTuSach", e.target.value)} />
                        </Form.Item>
                        <Form.Item label={<LabelOpt>Mô tả</LabelOpt>}>
                            <Input.TextArea value={form.MoTa ?? ""} onChange={(e) => setField("MoTa", e.target.value)} />
                        </Form.Item>
                    </Form>
                </Col>
            </Row>
        </div>
    );
});

const ROOT_ID = "root-store-tusach";
const bladeProps = readRootDataProps<ViewStoreTusachProps>(ROOT_ID) ?? {};
mountReactComponentOnReady(ROOT_ID, <ViewStoreTusach {...bladeProps} />);
