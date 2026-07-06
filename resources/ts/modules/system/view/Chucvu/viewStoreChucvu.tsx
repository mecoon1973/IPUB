import {
    mountReactComponentOnReady,
    readRootDataProps,
} from "../../../core/utils/helpers";
import React, { useState } from "react";
import { useCallback } from "react";
import { Col, Form, Input, Row } from "antd";
import { ComponentTitleStore } from "../../../page/component/componentTitleStore";
import type Chucvu from "../../type/ChucVu";
import { ChucvuApi } from "../../api/ChucvuApi";
import { LabelOpt, LabelReq } from "../../../page/component/componentLable";

function emptyFormState(): Partial<Chucvu> {
    return {
        id: 0,
        MaChucVu: "",
        TenChucVu: "",
        MoTa: "",
    };
}

interface ViewStoreChucvuProps {
    chucvu?: Chucvu | null;
}

export const ViewStoreChucvu = React.memo((props: ViewStoreChucvuProps) => {
    const { chucvu } = props;
    const [form, setForm] = useState<Partial<Chucvu>>(() => {
        if (chucvu) {
            return chucvu;
        }
        return emptyFormState();
    });
    const [submitting, setSubmitting] = useState(false);

    const setField = useCallback(<K extends keyof Chucvu>(key: K, value: Chucvu[K]) => {
        setForm((prev: Partial<Chucvu>) => ({ ...prev, [key]: value }));
    }, []);

    const handleSubmit = useCallback(() => {
        const mapKeysRequired = {
            MaChucVu: "Mã chức vụ",
            TenChucVu: "Tên chức vụ",
        };

        const messageRequired = Object.keys(mapKeysRequired)
            .map((key) =>
                !form[key as keyof Chucvu] ? mapKeysRequired[key as keyof typeof mapKeysRequired] : "",
            )
            .filter(Boolean)
            .join(", ");
        if (messageRequired) {
            window._toastbox(`Vui lòng nhập đầy đủ thông tin: ${messageRequired}`, "error");
            return;
        }

        setSubmitting(true);

        ChucvuApi.upsert(form)
            .then((res: Chucvu | null) => {
                if (res) {
                    window._toastbox(`${form.id ? "Cập nhật" : "Thêm mới"} chức vụ ${form.MaChucVu ?? ""} thành công`, "success");
                    setForm((prev: Partial<Chucvu>) => ({ ...prev, ...res }));
                }
            })
            .finally(() => {
                setSubmitting(false);
            });
    }, [form, setForm]);

    return (
        <div className="px-2">
            <ComponentTitleStore
                title={chucvu ? "Cập nhật chức vụ" : "Thêm mới chức vụ"}
                callbackSubmit={handleSubmit}
                disabledSubmit={submitting}
            />
            <Row gutter={12}>
                <Col span={16}>
                    <Form layout="vertical">
                        <Form.Item label={<LabelReq>Mã chức vụ</LabelReq>}>
                            <Input value={form.MaChucVu ?? ""} onChange={(e) => setField("MaChucVu", e.target.value)} />
                        </Form.Item>
                        <Form.Item label={<LabelReq>Tên chức vụ</LabelReq>}>
                            <Input value={form.TenChucVu ?? ""} onChange={(e) => setField("TenChucVu", e.target.value)} />
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

const ROOT_ID = "root-store-chucvu";
const bladeProps = readRootDataProps<ViewStoreChucvuProps>(ROOT_ID) ?? {};
mountReactComponentOnReady(ROOT_ID, <ViewStoreChucvu {...bladeProps} />);
