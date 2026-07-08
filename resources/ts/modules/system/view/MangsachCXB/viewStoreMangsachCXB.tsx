import {
    mountReactComponentOnReady,
    readRootDataProps,
} from "../../../core/utils/helpers";
import React, { useState } from "react";
import { useCallback } from "react";
import { Col, Form, Input, Row } from "antd";
import { ComponentTitleStore } from "../../../page/component/componentTitleStore";
import type { MangsachCXB } from "../../type/MangSachCXB";
import { LabelOpt, LabelReq } from "../../../page/component/componentLable";
import { MangsachCXBApi } from "../../api/MangsachCXBApi";

type MangsachCXBPUpsertBody = Pick<MangsachCXB, "id" | "MaMang" | "TenMang" | "MoTa">;

function emptyFormState(): Partial<MangsachCXB> {
    return {
        id: 0,
        TenMang: "",
        MaMang: "",
        MoTa: "",
    };
}

function toMangsachCXBPUpsertBody(form: Partial<MangsachCXB>): MangsachCXBPUpsertBody {
    return {
        id: form.id ?? 0,
        MaMang: form.MaMang ?? "",
        TenMang: form.TenMang ?? "",
        MoTa: form.MoTa ?? "",
    };
}

interface MangsachCXBFormFieldsProps {
    form: Partial<MangsachCXB>;
    setField: (key: keyof MangsachCXB, value: MangsachCXB[keyof MangsachCXB]) => void;
}

const MangsachCXBFormFields = React.memo((props: MangsachCXBFormFieldsProps) => {
    const { form, setField } = props;
    return (
        <Form layout="vertical">
            <Form.Item label={<LabelReq>Mã mảng sách CXB</LabelReq>}>
                <Input value={form.MaMang ?? ""} onChange={(e) => setField("MaMang", e.target.value)} />
            </Form.Item>
            <Form.Item label={<LabelReq>Tên mảng sách CXB</LabelReq>}>
                <Input.TextArea rows={3} value={form.TenMang ?? ""} onChange={(e) => setField("TenMang", e.target.value)} />
            </Form.Item>
            <Form.Item label={<LabelOpt>Mô tả</LabelOpt>}>
                <Input.TextArea rows={3} value={form.MoTa ?? ""} onChange={(e) => setField("MoTa", e.target.value)} />
            </Form.Item>
        </Form>
    );
});

interface ViewStoreMangsachCXBPageProps {
    mangsachCXB?: MangsachCXB | null;
}

export const ViewStoreMangsachCXB = React.memo((props: ViewStoreMangsachCXBPageProps) => {
    const { mangsachCXB } = props;
    const [form, setForm] = useState<Partial<MangsachCXB>>(() => {
        if (mangsachCXB) {
            return mangsachCXB;
        }
        return { ...emptyFormState() };
    });
    const [submitting, setSubmitting] = useState(false);

    const handleSubmit = useCallback(() => {
        const mapKeysRequired = {
            MaMang: "Mã mảng sách CXB",
            TenMang: "Tên mảng sách CXB",
        };

        const messageRequired = Object.keys(mapKeysRequired)
            .map((key) =>
                !form[key as keyof Partial<MangsachCXB>] ? mapKeysRequired[key as keyof typeof mapKeysRequired] : "",
            )
            .filter(Boolean)
            .join(", ");
        if (messageRequired) {
            window._toastbox(`Vui lòng nhập đầy đủ thông tin: ${messageRequired}`, "error");
            return;
        }

        setSubmitting(true);

        MangsachCXBApi.upsert(toMangsachCXBPUpsertBody(form))
            .then((res: MangsachCXB | null) => {
                if (res) {
                    window._toastbox("Cập nhật mảng sách CXB thành công", "success");
                    setForm((prev: Partial<MangsachCXB>) => ({ ...prev, ...res }));
                }
            })
            .finally(() => {
                setSubmitting(false);
            });
    }, [form, setForm]);

    const setField = useCallback(<K extends keyof MangsachCXB>(key: K, value: MangsachCXB[K]) => {
        setForm((prev: Partial<MangsachCXB>) => ({ ...prev, [key]: value }));
    }, []);

    return (
        <div className="px-2">
            <ComponentTitleStore
                title={mangsachCXB ? "Cập nhật mảng sách CXB" : "Thêm mới mảng sách CXB"}
                callbackSubmit={handleSubmit}
                disabledSubmit={submitting}
            />
            <Row gutter={12}>
                <Col span={16}>
                    <MangsachCXBFormFields form={form} setField={setField} />
                </Col>
            </Row>
        </div>
    );
});

const ROOT_ID = "root-store-mangsach-cxb";
const bladeProps: ViewStoreMangsachCXBPageProps = {
    ...readRootDataProps<ViewStoreMangsachCXBPageProps>(ROOT_ID),
};
mountReactComponentOnReady(ROOT_ID, <ViewStoreMangsachCXB {...bladeProps} />);
