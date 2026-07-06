import {
    mountReactComponentOnReady,
    readRootDataProps,
} from "../../../core/utils/helpers";
import React, { useState } from "react";
import { useCallback } from "react";
import { Col, Form, Input, Row } from "antd";
import { ComponentTitleStore } from "../../../page/component/componentTitleStore";
import type Doituong from "../../type/DoiTuong";
import { DoituongApi } from "../../api/DoituongApi";
import { LabelOpt, LabelReq } from "../../../page/component/componentLable";

interface DoituongFormFieldsProps {
    form: Partial<Doituong>;
    setField: (key: keyof Doituong, value: Doituong[keyof Doituong]) => void;
}

export const DoituongFormFields = React.memo((props: DoituongFormFieldsProps) => {
    const { form, setField } = props;
    return (
        <Form layout="vertical">
            <Form.Item label={<LabelReq>Mã đối tượng</LabelReq>}>
                <Input value={form.MaDoiTuong ?? ""} onChange={(e) => setField("MaDoiTuong", e.target.value)} />
            </Form.Item>
            <Form.Item label={<LabelReq>Tên đối tượng</LabelReq>}>
                <Input.TextArea rows={3} value={form.TenDoiTuong ?? ""} onChange={(e) => setField("TenDoiTuong", e.target.value)} />
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

function emptyFormState(): Partial<Doituong> {
    return {
        id: 0,
        MaDoiTuong: "",
        TenDoiTuong: "",
        KiHieu: "",
        MoTa: "",
    };
}

interface ViewStoreDoituongPageProps {
    doituong?: Doituong | null;
}

export const ViewStoreDoituong = React.memo((props: ViewStoreDoituongPageProps) => {
    const { doituong } = props;
    const [form, setForm] = useState<Partial<Doituong>>(() => {
        if (doituong) {
            return doituong;
        }
        return { ...emptyFormState() };
    });
    const [submitting, setSubmitting] = useState(false);

    const handleSubmit = useCallback(() => {
        const mapKeysRequired = {
            MaDoiTuong: "Mã đối tượng",
            TenDoiTuong: "Tên đối tượng",
            KiHieu: "Kí hiệu",
        };

        const messageRequired = Object.keys(mapKeysRequired)
            .map((key) =>
                !form[key as keyof Partial<Doituong>] ? mapKeysRequired[key as keyof typeof mapKeysRequired] : "",
            )
            .filter(Boolean)
            .join(", ");
        if (messageRequired) {
            window._toastbox(`Vui lòng nhập đầy đủ thông tin: ${messageRequired}`, "error");
            return;
        }

        setSubmitting(true);

        DoituongApi.upsert(form)
            .then((res: Doituong | null) => {
                if (res) {
                    window._toastbox("Cập nhật chức năng thành công", "success");
                    setForm((prev: Partial<Doituong>) => ({ ...prev, ...res }));
                }
            })
            .finally(() => {
                setSubmitting(false);
            });
    }, [form, setForm]);

    const setField = useCallback(<K extends keyof Doituong>(key: K, value: Doituong[K]) => {
        setForm((prev: Partial<Doituong>) => ({ ...prev, [key]: value }));
    }, []);

    return (
        <div className="px-1">
            <ComponentTitleStore
                title={doituong ? "Cập nhật đối tượng" : "Thêm mới đối tượng"}
                callbackSubmit={handleSubmit}
                disabledSubmit={submitting}
            />
            <DoituongFormFields form={form} setField={setField} />
        </div>
    );
});

const ROOT_ID = "root-store-doituong";
const bladeProps: ViewStoreDoituongPageProps = {
    ...readRootDataProps<ViewStoreDoituongPageProps>(ROOT_ID),
};
mountReactComponentOnReady(ROOT_ID, <ViewStoreDoituong {...bladeProps} />);
