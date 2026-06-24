import {
    mountReactComponentOnReady,
    readRootDataProps,
} from "../../../core/utils/helpers";
import React, { useState } from "react";
import { useCallback } from "react";
import { Col, Form, Input, Row, Select } from "antd";
import { ComponentTitleStore } from "../../../page/component/componentTitleStore";
import type { LoaiXBP } from "../../type";
import { LoaiXBPApi } from "../../api/LoaiXBPApi";
import { LabelOpt, LabelReq } from "../../../page/component/componentLable";

type LoaiXBPUpsertBody = Pick<LoaiXBP, "id" | "MaLoai" | "TenLoai" | "KiHieu" | "MoTa" | "Type">;

function emptyFormState(): Partial<LoaiXBP> {
    return {
        id: 0,
        TenLoai: "",
        MaLoai: "",
        KiHieu: "",
        MoTa: "",
        Type: 0,
    };
}

function toLoaiXBPUpsertBody(form: Partial<LoaiXBP>): LoaiXBPUpsertBody {
    return {
        id: form.id ?? 0,
        MaLoai: form.MaLoai ?? "",
        TenLoai: form.TenLoai ?? "",
        KiHieu: form.KiHieu ?? "",
        MoTa: form.MoTa ?? "",
        Type: Number(form.Type) || 0,
    };
}

interface LoaiXBPFormFieldsProps {
    form: Partial<LoaiXBP>;
    setField: (key: keyof LoaiXBP, value: LoaiXBP[keyof LoaiXBP]) => void;
}

const LoaiXBPFormFields = React.memo((props: LoaiXBPFormFieldsProps) => {
    const { form, setField } = props;
    return (
        <Form layout="vertical">
            <Form.Item label={<LabelReq>Mã loại xuất bản phẩm</LabelReq>}>
                <Input value={form.MaLoai ?? ""} onChange={(e) => setField("MaLoai", e.target.value)} />
            </Form.Item>
            <Form.Item label={<LabelReq>Tên loại xuất bản phẩm</LabelReq>}>
                <Input.TextArea rows={3} value={form.TenLoai ?? ""} onChange={(e) => setField("TenLoai", e.target.value)} />
            </Form.Item>
            <Form.Item label={<LabelReq>Kí hiệu</LabelReq>}>
                <Input value={form.KiHieu ?? ""} onChange={(e) => setField("KiHieu", e.target.value)} />
            </Form.Item>
            <Form.Item label={<LabelOpt>Kiểu</LabelOpt>}>
                <Select
                    placeholder="— Chọn kiểu —"
                    value={form.Type === 0 || form.Type == null ? undefined : Number(form.Type)}
                    options={[
                        { value: 1, label: "Mẫu MP1" },
                        { value: 2, label: "Mẫu MP2" },
                    ]}
                    allowClear
                    onChange={(v) =>
                        setField(
                            "Type",
                            (v ?? 0) as LoaiXBP["Type"],
                        )
                    }
                />
            </Form.Item>
            <Form.Item label={<LabelOpt>Mô tả</LabelOpt>}>
                <Input.TextArea rows={3} value={form.MoTa ?? ""} onChange={(e) => setField("MoTa", e.target.value)} />
            </Form.Item>
        </Form>
    );
});

interface ViewStoreLoaiXBPPageProps {
    loaiXBP?: LoaiXBP | null;
}

export const ViewStoreLoaiXBP = React.memo((props: ViewStoreLoaiXBPPageProps) => {
    const { loaiXBP } = props;
    const [form, setForm] = useState<Partial<LoaiXBP>>(() => {
        if (loaiXBP) {
            return loaiXBP;
        }
        return { ...emptyFormState() };
    });
    const [submitting, setSubmitting] = useState(false);

    const handleSubmit = useCallback(() => {
        const mapKeysRequired = {
            MaLoai: "Mã loại xuất bản phẩm",
            TenLoai: "Tên loại xuất bản phẩm",
            KiHieu: "Kí hiệu",
        };

        const messageRequired = Object.keys(mapKeysRequired)
            .map((key) =>
                !form[key as keyof Partial<LoaiXBP>] ? mapKeysRequired[key as keyof typeof mapKeysRequired] : "",
            )
            .filter(Boolean)
            .join(", ");
        if (messageRequired) {
            window._toastbox(`Vui lòng nhập đầy đủ thông tin: ${messageRequired}`, "error");
            return;
        }

        setSubmitting(true);

        LoaiXBPApi.upsert(toLoaiXBPUpsertBody(form))
            .then((res: LoaiXBP | null) => {
                if (res) {
                    window._toastbox("Cập nhật loại xuất bản thành công", "success");
                    setForm((prev: Partial<LoaiXBP>) => ({ ...prev, ...res }));
                }
            })
            .finally(() => {
                setSubmitting(false);
            });
    }, [form, setForm]);

    const setField = useCallback(<K extends keyof LoaiXBP>(key: K, value: LoaiXBP[K]) => {
        setForm((prev: Partial<LoaiXBP>) => ({ ...prev, [key]: value }));
    }, []);

    return (
        <div className="px-2">
            <ComponentTitleStore
                title={loaiXBP ? "Cập nhật loại xuất bản" : "Thêm mới loại xuất bản"}
                callbackSubmit={handleSubmit}
                disabledSubmit={submitting}
            />
            <Row gutter={12}>
                <Col span={16}>
                    <LoaiXBPFormFields form={form} setField={setField} />
                </Col>
            </Row>
        </div>
    );
});

const ROOT_ID = "root-store-loai-xbp";
const bladeProps: ViewStoreLoaiXBPPageProps = {
    ...readRootDataProps<ViewStoreLoaiXBPPageProps>(ROOT_ID),
};
mountReactComponentOnReady(ROOT_ID, <ViewStoreLoaiXBP {...bladeProps} />);
