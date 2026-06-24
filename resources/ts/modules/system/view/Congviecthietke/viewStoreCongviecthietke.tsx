import {
    mountReactComponentOnReady,
    readRootDataProps,
} from "../../../core/utils/helpers";
import React, { useState } from "react";
import { useCallback } from "react";
import { Col, Form, Input, Row } from "antd";
import { ComponentTitleStore } from "../../../page/component/componentTitleStore";
import type { Congviecthietke } from "../../type";
import { LabelReq } from "../../../page/component/componentLable";
import { CongviecthietkeApi } from "../../api/CongviecthietkeApi";

type CongviecthietkeUpsertBody = Pick<Congviecthietke, "id" | "MaCongViec" | "TenCongViec" | "DVT">;

function emptyFormState(): Partial<Congviecthietke> {
    return {
        id: 0,
        TenCongViec: "",
        MaCongViec: "",
        DVT: "",
    };
}

function toCongviecthietkeUpsertBody(form: Partial<Congviecthietke>): CongviecthietkeUpsertBody {
    return {
        id: form.id ?? 0,
        MaCongViec: form.MaCongViec ?? "",
        TenCongViec: form.TenCongViec ?? "",
        DVT: form.DVT ?? "",
    };
}

interface CongviecthietkeFormFieldsProps {
    form: Partial<Congviecthietke>;
    setField: (key: keyof Congviecthietke, value: Congviecthietke[keyof Congviecthietke]) => void;
}

const CongviecthietkeFormFields = React.memo((props: CongviecthietkeFormFieldsProps) => {
    const { form, setField } = props;
    return (
        <Form layout="vertical">
            <Form.Item label={<LabelReq>Mã công việc</LabelReq>}>
                <Input value={form.MaCongViec ?? ""} onChange={(e) => setField("MaCongViec", e.target.value)} />
            </Form.Item>
            <Form.Item label={<LabelReq>Tên công việc</LabelReq>}>
                <Input.TextArea rows={3} value={form.TenCongViec ?? ""} onChange={(e) => setField("TenCongViec", e.target.value)} />
            </Form.Item>
            <Form.Item label={<LabelReq>Đơn vị tính</LabelReq>}>
                <Input value={form.DVT ?? ""} onChange={(e) => setField("DVT", e.target.value)} />
            </Form.Item>
        </Form>
    );
});

interface ViewStoreCongviecthietkePageProps {
    congviecthietke?: Congviecthietke | null;
}

export const ViewStoreCongviecthietke = React.memo((props: ViewStoreCongviecthietkePageProps) => {
    const { congviecthietke } = props;
    const [form, setForm] = useState<Partial<Congviecthietke>>(() => {
        if (congviecthietke) {
            return congviecthietke;
        }
        return { ...emptyFormState() };
    });
    const [submitting, setSubmitting] = useState(false);

    const handleSubmit = useCallback(() => {
        const mapKeysRequired = {
            MaCongViec: "Mã công việc thiết kế",
            TenCongViec: "Tên công việc thiết kế",
            DVT: "Đơn vị tính",
        };

        const messageRequired = Object.keys(mapKeysRequired)
            .map((key) =>
                !form[key as keyof Partial<Congviecthietke>] ? mapKeysRequired[key as keyof typeof mapKeysRequired] : "",
            )
            .filter(Boolean)
            .join(", ");
        if (messageRequired) {
            window._toastbox(`Vui lòng nhập đầy đủ thông tin: ${messageRequired}`, "error");
            return;
        }

        setSubmitting(true);

        CongviecthietkeApi.upsert(toCongviecthietkeUpsertBody(form))
            .then((res: Congviecthietke | null) => {
                if (res) {
                    window._toastbox("Cập nhật công việc thiết kế thành công", "success");
                    setForm((prev: Partial<Congviecthietke>) => ({ ...prev, ...res }));
                }
            })
            .finally(() => {
                setSubmitting(false);
            });
    }, [form, setForm]);

    const setField = useCallback(<K extends keyof Congviecthietke>(key: K, value: Congviecthietke[K]) => {
        setForm((prev: Partial<Congviecthietke>) => ({ ...prev, [key]: value }));
    }, []);

    return (
        <div className="px-2">
            <ComponentTitleStore
                title={congviecthietke ? "Cập nhật công việc thiết kế" : "Thêm mới công việc thiết kế"}
                callbackSubmit={handleSubmit}
                disabledSubmit={submitting}
            />
            <Row gutter={12}>
                <Col span={16}>
                    <CongviecthietkeFormFields form={form} setField={setField} />
                </Col>
            </Row>
        </div>
    );
});

const ROOT_ID = "root-store-congviecthietke";
const bladeProps: ViewStoreCongviecthietkePageProps = {
    ...readRootDataProps<ViewStoreCongviecthietkePageProps>(ROOT_ID),
};
mountReactComponentOnReady(ROOT_ID, <ViewStoreCongviecthietke {...bladeProps} />);
