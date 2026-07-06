import {
    mountReactComponentOnReady,
    readRootDataProps,
} from "../../../core/utils/helpers";
import React, { useState } from "react";
import { useCallback } from "react";
import { Col, Form, Input, Row } from "antd";
import { ComponentTitleStore } from "../../../page/component/componentTitleStore";
import type Chuyenmon from "../../type/ChuyenMon";
import { ChuyenmonApi } from "../../api/ChuyenmonApi";
import { LabelOpt, LabelReq } from "../../../page/component/componentLable";

function emptyFormState(): Partial<Chuyenmon> {
    return {
        id: 0,
        TenChuyenMon: "",
        MoTa: "",
    };
}

interface ViewStoreChuyenmonProps {
    chuyenmon?: Chuyenmon | null;
}

export const ViewStoreChuyenmon = React.memo((props: ViewStoreChuyenmonProps) => {
    const { chuyenmon } = props;
    const [form, setForm] = useState<Partial<Chuyenmon>>(() => {
        if (chuyenmon) {
            return chuyenmon;
        }
        return { ...emptyFormState() };
    });
    const [submitting, setSubmitting] = useState(false);

    const setField = useCallback(<K extends keyof Chuyenmon>(key: K, value: Chuyenmon[K]) => {
        setForm((prev: Partial<Chuyenmon>) => ({ ...prev, [key]: value }));
    }, []);

    const handleSubmit = useCallback(() => {
        const mapKeysRequired = {
            TenChuyenMon: "Tên chuyên môn",
        };

        const messageRequired = Object.keys(mapKeysRequired)
            .map((key) =>
                !form[key as keyof Chuyenmon] ? mapKeysRequired[key as keyof typeof mapKeysRequired] : "",
            )
            .filter(Boolean)
            .join(", ");
        if (messageRequired) {
            window._toastbox(`Vui lòng nhập đầy đủ thông tin: ${messageRequired}`, "error");
            return;
        }

        setSubmitting(true);

        ChuyenmonApi.upsert(form)
            .then((res: Chuyenmon | null) => {
                if (res) {
                    window._toastbox(`${form.id ? "Cập nhật" : "Thêm mới"} chuyên môn ${form.TenChuyenMon ?? ""} thành công`, "success");
                    setForm((prev: Partial<Chuyenmon>) => ({ ...prev, ...res }));
                }
            })
            .finally(() => {
                setSubmitting(false);
            });
    }, [form, setForm]);

    return (
        <div className="px-2">
            <ComponentTitleStore
                title={chuyenmon ? "Cập nhật chuyên môn" : "Thêm mới chuyên môn"}
                callbackSubmit={handleSubmit}
                disabledSubmit={submitting}
            />
            <Row gutter={12}>
                <Col span={16}>
                    <Form layout="vertical">
                        <Form.Item label={<LabelReq>Tên chuyên môn</LabelReq>}>
                            <Input value={form.TenChuyenMon ?? ""} onChange={(e) => setField("TenChuyenMon", e.target.value)} />
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

const ROOT_ID = "root-store-chuyenmon";
const bladeProps = readRootDataProps<ViewStoreChuyenmonProps>(ROOT_ID) ?? {};
mountReactComponentOnReady(ROOT_ID, <ViewStoreChuyenmon {...bladeProps} />);
