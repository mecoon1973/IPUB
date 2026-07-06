import {
    mountReactComponentOnReady,
    readRootDataProps,
} from "../../../core/utils/helpers";
import React, { useState } from "react";
import { useCallback } from "react";
import { Col, Form, Input, InputNumber, Row } from "antd";
import { ComponentTitleStore } from "../../../page/component/componentTitleStore";
import type Ngoaingu from "../../type/NgoaiNgu";
import { LabelOpt, LabelReq } from "../../../page/component/componentLable";
import { NgoainguApi } from "../../api/NgoainguApi";

type NgoainguUpsertBody = Pick<Ngoaingu, "id" | "MaNgoaiNgu" | "TenNgoaiNgu" | "ThuTu">;

function emptyFormState(): Partial<Ngoaingu> {
    return {
        id: 0,
        TenNgoaiNgu: "",
        MaNgoaiNgu: "",
        ThuTu: 0,
    };
}

function toNgoainguUpsertBody(form: Partial<Ngoaingu>): NgoainguUpsertBody {
    return {
        id: form.id ?? 0,
        MaNgoaiNgu: form.MaNgoaiNgu ?? "",
        TenNgoaiNgu: form.TenNgoaiNgu ?? "",
        ThuTu: form.ThuTu ?? 0,
    };
}

interface NgoainguFormFieldsProps {
    form: Partial<Ngoaingu>;
    setField: (key: keyof Ngoaingu, value: Ngoaingu[keyof Ngoaingu]) => void;
}

const NgoainguFormFields = React.memo((props: NgoainguFormFieldsProps) => {
    const { form, setField } = props;
    return (
        <Form layout="vertical">
            <Form.Item label={<LabelReq>Mã Ngoại ngữ</LabelReq>}>
                <Input value={form.MaNgoaiNgu ?? ""} onChange={(e) => setField("MaNgoaiNgu", e.target.value)} />
            </Form.Item>
            <Form.Item label={<LabelReq>Tên Ngoại ngữ</LabelReq>}>
                <Input.TextArea rows={3} value={form.TenNgoaiNgu ?? ""} onChange={(e) => setField("TenNgoaiNgu", e.target.value)} />
            </Form.Item>
            <Form.Item label={<LabelOpt>Thứ tự</LabelOpt>}>
                <InputNumber className="w-100" value={form.ThuTu ?? 0} onChange={(v) => setField("ThuTu", (v ?? 0) as Ngoaingu["ThuTu"])} />
            </Form.Item>
        </Form>
    );
});

interface ViewStoreNgoainguPageProps {
    ngoaingu?: Ngoaingu | null;
}

export const ViewStoreNgoaingu = React.memo((props: ViewStoreNgoainguPageProps) => {
    const { ngoaingu } = props;
    const [form, setForm] = useState<Partial<Ngoaingu>>(() => {
        if (ngoaingu) {
            return ngoaingu;
        }
        return { ...emptyFormState() };
    });
    const [submitting, setSubmitting] = useState(false);

    const handleSubmit = useCallback(() => {
        const mapKeysRequired = {
            MaNgoaiNgu: "Mã Ngoại ngữ",
            TenNgoaiNgu: "Tên Ngoại ngữ",
        };

        const messageRequired = Object.keys(mapKeysRequired)
            .map((key) =>
                !form[key as keyof Partial<Ngoaingu>] ? mapKeysRequired[key as keyof typeof mapKeysRequired] : "",
            )
            .filter(Boolean)
            .join(", ");
        if (messageRequired) {
            window._toastbox(`Vui lòng nhập đầy đủ thông tin: ${messageRequired}`, "error");
            return;
        }

        setSubmitting(true);

        NgoainguApi.upsert(toNgoainguUpsertBody(form))
            .then((res: Ngoaingu | null) => {
                if (res) {
                    window._toastbox("Cập nhật Ngoại ngữ thành công", "success");
                    setForm((prev: Partial<Ngoaingu>) => ({ ...prev, ...res }));
                }
            })
            .finally(() => {
                setSubmitting(false);
            });
    }, [form, setForm]);

    const setField = useCallback(<K extends keyof Ngoaingu>(key: K, value: Ngoaingu[K]) => {
        setForm((prev: Partial<Ngoaingu>) => ({ ...prev, [key]: value }));
    }, []);

    return (
        <div className="px-2">
            <ComponentTitleStore
                title={ngoaingu ? "Cập nhật Ngoại ngữ" : "Thêm mới Ngoại ngữ"}
                callbackSubmit={handleSubmit}
                disabledSubmit={submitting}
            />
            <Row gutter={12}>
                <Col span={16}>
                    <NgoainguFormFields form={form} setField={setField} />
                </Col>
            </Row>
        </div>
    );
});

const ROOT_ID = "root-store-ngoaingu";
const bladeProps: ViewStoreNgoainguPageProps = {
    ...readRootDataProps<ViewStoreNgoainguPageProps>(ROOT_ID),
};
mountReactComponentOnReady(ROOT_ID, <ViewStoreNgoaingu {...bladeProps} />);
