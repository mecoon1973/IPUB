import {
    mountReactComponentOnReady,
    readRootDataProps,
} from "../../../core/utils/helpers";
import React, { useState } from "react";
import { useCallback } from "react";
import { Col, Form, Input, Row } from "antd";
import { ComponentTitleStore } from "../../../page/component/componentTitleStore";
import type Congviecchebanin from "../../type/CongViecCheBanIn";
import { LabelReq } from "../../../page/component/componentLable";
import { CongviecchebaninApi } from "../../api/CongviecchebaninApi";

type CongviecchebaninUpsertBody = Pick<Congviecchebanin, "id" | "MaCongViec" | "TenCongViec">;

function emptyFormState(): Partial<Congviecchebanin> {
    return {
        id: 0,
        TenCongViec: "",
        MaCongViec: "",
    };
}

function toCongviecchebaninUpsertBody(form: Partial<Congviecchebanin>): CongviecchebaninUpsertBody {
    return {
        id: form.id ?? 0,
        MaCongViec: form.MaCongViec ?? "",
        TenCongViec: form.TenCongViec ?? "",
    };
}

interface CongviecchebaninFormFieldsProps {
    form: Partial<Congviecchebanin>;
    setField: (key: keyof Congviecchebanin, value: Congviecchebanin[keyof Congviecchebanin]) => void;
}

const CongviecchebaninFormFields = React.memo((props: CongviecchebaninFormFieldsProps) => {
    const { form, setField } = props;
    return (
        <Form layout="vertical">
            <Form.Item label={<LabelReq>Mã công việc</LabelReq>}>
                <Input value={form.MaCongViec ?? ""} onChange={(e) => setField("MaCongViec", e.target.value)} />
            </Form.Item>
            <Form.Item label={<LabelReq>Tên công việc</LabelReq>}>
                <Input.TextArea rows={3} value={form.TenCongViec ?? ""} onChange={(e) => setField("TenCongViec", e.target.value)} />
            </Form.Item>
        </Form>
    );
});

interface ViewStoreCongviecchebaninPageProps {
    congviecchebanin?: Congviecchebanin | null;
}

export const ViewStoreCongviecchebanin = React.memo((props: ViewStoreCongviecchebaninPageProps) => {
    const { congviecchebanin } = props;
    const [form, setForm] = useState<Partial<Congviecchebanin>>(() => {
        if (congviecchebanin) {
            return congviecchebanin;
        }
        return { ...emptyFormState() };
    });
    const [submitting, setSubmitting] = useState(false);

    const handleSubmit = useCallback(() => {
        const mapKeysRequired = {
            MaCongViec: "Mã công việc che ban in",
            TenCongViec: "Tên công việc che ban in",
        };

        const messageRequired = Object.keys(mapKeysRequired)
            .map((key) =>
                !form[key as keyof Partial<Congviecchebanin>] ? mapKeysRequired[key as keyof typeof mapKeysRequired] : "",
            )
            .filter(Boolean)
            .join(", ");
        if (messageRequired) {
            window._toastbox(`Vui lòng nhập đầy đủ thông tin: ${messageRequired}`, "error");
            return;
        }

        setSubmitting(true);

        CongviecchebaninApi.upsert(toCongviecchebaninUpsertBody(form))
            .then((res: Congviecchebanin | null) => {
                if (res) {
                    window._toastbox("Cập nhật công việc che ban in thành công", "success");
                    setForm((prev: Partial<Congviecchebanin>) => ({ ...prev, ...res }));
                }
            })
            .finally(() => {
                setSubmitting(false);
            });
    }, [form, setForm]);

    const setField = useCallback(<K extends keyof Congviecchebanin>(key: K, value: Congviecchebanin[K]) => {
        setForm((prev: Partial<Congviecchebanin>) => ({ ...prev, [key]: value }));
    }, []);

    return (
        <div className="px-2">
            <ComponentTitleStore
                title={congviecchebanin ? "Cập nhật công việc che ban in" : "Thêm mới công việc che ban in"}
                callbackSubmit={handleSubmit}
                disabledSubmit={submitting}
            />
            <Row gutter={12}>
                <Col span={16}>
                    <CongviecchebaninFormFields form={form} setField={setField} />
                </Col>
            </Row>
        </div>
    );
});

const ROOT_ID = "root-store-congviecchebanin";
const bladeProps: ViewStoreCongviecchebaninPageProps = {
    ...readRootDataProps<ViewStoreCongviecchebaninPageProps>(ROOT_ID),
};
mountReactComponentOnReady(ROOT_ID, <ViewStoreCongviecchebanin {...bladeProps} />);
