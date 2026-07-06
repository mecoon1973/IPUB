import {
    mountReactComponentOnReady,
    readRootDataProps,
} from "../../../core/utils/helpers";
import React, { useState } from "react";
import { useCallback } from "react";
import { Col, Form, Input, InputNumber, Row } from "antd";
import { ComponentTitleStore } from "../../../page/component/componentTitleStore";
import type { Mangsach } from "../../type/MangSach";
import { LabelOpt, LabelReq } from "../../../page/component/componentLable";
import { MangsachApi } from "../../api/MangsachApi";
import { useGetMangsach } from "../../hooks/Mangsach/useGetMangsach";
import { ModalTree } from "../../../page/component/componentModalTree";

interface MangsachFormFieldsProps {
    form: Partial<Mangsach>;
    setField: (key: keyof Mangsach, value: Mangsach[keyof Mangsach]) => void;
    listMangsach: Mangsach[];
}

const MangsachFormFields = React.memo((props: MangsachFormFieldsProps) => {
    const { form, setField, listMangsach } = props;
    const [showModalChooseMangsach, setShowModalChooseMangsach] = useState(false);
    const onShowModalChooseMangsach = useCallback(() => setShowModalChooseMangsach(true), []);
    const onHideModalChooseMangsach = useCallback(() => setShowModalChooseMangsach(false), []);
    const handlerChooseMangsach = useCallback(
        (mangsach: Mangsach) => {
            setField("ParentID", mangsach.id);
        },
        [setField],
    );

    return (
        <>
            <Form layout="vertical">
                <Form.Item label={<LabelReq>Mã mảng sách</LabelReq>}>
                    <Input value={form.MaMang ?? ""} onChange={(e) => setField("MaMang", e.target.value)} />
                </Form.Item>
                <Form.Item label={<LabelReq>Tên mảng sách</LabelReq>}>
                    <Input.TextArea rows={3} value={form.TenMang ?? ""} onChange={(e) => setField("TenMang", e.target.value)} />
                </Form.Item>
                <Form.Item label={<LabelReq>Kí hiệu</LabelReq>}>
                    <Input value={form.KiHieu ?? ""} onChange={(e) => setField("KiHieu", e.target.value)} />
                </Form.Item>
                <Form.Item label={<LabelOpt>Mảng cha</LabelOpt>}>
                    <Input
                        readOnly
                        value={form.ParentID ? listMangsach.find((m) => m.id === form.ParentID)?.TenMang ?? "" : ""}
                        onClick={onShowModalChooseMangsach}
                    />
                </Form.Item>
                <Form.Item label={<LabelReq>Tỉ lệ thu phí quản lý xuất bản</LabelReq>}>
                    <InputNumber className="w-100" value={form.VAT ?? 0} onChange={(v) => setField("VAT", (v ?? 0) as Mangsach["VAT"])} />
                </Form.Item>
                <Form.Item label={<LabelReq>Thứ tự hiển thị</LabelReq>}>
                    <InputNumber className="w-100" value={form.iOrder ?? 0} onChange={(v) => setField("iOrder", (v ?? 0) as Mangsach["iOrder"])} />
                </Form.Item>
                <Form.Item label={<LabelOpt>Mô tả</LabelOpt>}>
                    <Input.TextArea rows={3} value={form.MoTa ?? ""} onChange={(e) => setField("MoTa", e.target.value)} />
                </Form.Item>
            </Form>
            <ModalTree
                title="Danh sách Mảng sách"
                show={showModalChooseMangsach}
                onHide={onHideModalChooseMangsach}
                listData={listMangsach}
                handlerChoose={(data: Mangsach) => handlerChooseMangsach(data)}
                getLabel={(data: Mangsach) => data.TenMang + " (" + data.MaMang + ")"}
                usingselectChoose={true}
                size="lg"
                selectedIdDefault={form.ParentID ?? 0}
            />
        </>
    );
});

function emptyFormState(): Partial<Mangsach> {
    return {
        id: 0,
        MaMang: "",
        TenMang: "",
        MoTa: "",
        KiHieu: "",
        ParentID: 0,
        VAT: 0,
        iOrder: 0,
    };
}

interface ViewStoreMangsachPageProps {
    mangsach?: Mangsach | null;
    parentId?: number;
}

export const ViewStoreMangsach = React.memo((props: ViewStoreMangsachPageProps) => {
    const { mangsach, parentId } = props;
    const [form, setForm] = useState<Partial<Mangsach>>(() => {
        if (mangsach) {
            return mangsach;
        }
        return {
            ...emptyFormState(),
            ParentID: parentId ?? 0,
        };
    });
    const [submitting, setSubmitting] = useState(false);
    const { listMangsach } = useGetMangsach();

    const handleSubmit = useCallback(() => {
        const mapKeysRequired = {
            MaMang: "Mã mảng sách",
            TenMang: "Tên mảng sách",
            KiHieu: "Kí hiệu",
            iOrder: "Thứ tự hiển thị",
        };

        const messageRequired = Object.keys(mapKeysRequired)
            .map((key) =>
                !form[key as keyof Partial<Mangsach>] ? mapKeysRequired[key as keyof typeof mapKeysRequired] : "",
            )
            .filter(Boolean)
            .join(", ");
        if (messageRequired) {
            window._toastbox(`Vui lòng nhập đầy đủ thông tin: ${messageRequired}`, "error");
            return;
        }

        setSubmitting(true);

        MangsachApi.upsert(form)
            .then((res: Mangsach | null) => {
                if (res) {
                    setForm((prev: Partial<Mangsach>) => ({ ...prev, ...res }));
                    window._toastbox("Cập nhật mảng sách thành công", "success");
                }
            })
            .finally(() => {
                setSubmitting(false);
            });
    }, [form, setForm]);

    const setField = useCallback(<K extends keyof Mangsach>(key: K, value: Mangsach[K]) => {
        setForm((prev: Partial<Mangsach>) => ({ ...prev, [key]: value }));
    }, []);

    return (
        <div className="px-2">
            <ComponentTitleStore
                title={mangsach ? "Cập nhật mảng sách" : "Thêm mới mảng sách"}
                callbackSubmit={handleSubmit}
                disabledSubmit={submitting}
            />
            <Row gutter={12}>
                <Col span={16}>
                    <MangsachFormFields form={form} setField={setField} listMangsach={listMangsach} />
                </Col>
            </Row>
        </div>
    );
});

const ROOT_ID = "root-store-mangsach";
const bladeProps: ViewStoreMangsachPageProps = {
    ...readRootDataProps<ViewStoreMangsachPageProps>(ROOT_ID),
};
mountReactComponentOnReady(ROOT_ID, <ViewStoreMangsach {...bladeProps} />);
