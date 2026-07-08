import {
    mountReactComponentOnReady,
    readRootDataProps,
} from "../../../core/utils/helpers";
import React, { useState } from "react";
import { useCallback } from "react";
import { Col, Row } from "antd";
import { ComponentTitleStore } from "../../../page/component/componentTitleStore";
import type { Lop } from "../../type/Lop";
import { LopApi } from "../../api/LopApi";
import { LopFormFields } from "../../component/Lop/LopFormFields";

function emptyFormState(): Partial<Lop> {
    return {
        id: 0,
        MaLop: "",
        TenLop: "",
        KiHieu: "",
    };
}

interface ViewStoreLopPageProps {
    lop?: Lop | null;
}

export const ViewStoreLop = React.memo((props: ViewStoreLopPageProps) => {
    const { lop } = props;
    const [form, setForm] = useState<Partial<Lop>>(() => {
        if (lop) {
            return lop;
        }
        return {
            ...emptyFormState(),
        };
    });
    const [submitting, setSubmitting] = useState(false);

    const handleSubmit = useCallback(() => {

        const mapKeysRequired = {
            "MaLop": "Mã lớp",
            "TenLop": "Tên lớp",
            "KiHieu": "Kí hiệu",
        };

        const messageRequired = Object.keys(mapKeysRequired).map(key => !form[key as keyof Partial<Lop>] ? mapKeysRequired[key as keyof typeof mapKeysRequired] : "").filter(Boolean).join(", ");
        if (messageRequired) {
            window._toastbox(`Vui lòng nhập đầy đủ thông tin: ${messageRequired}`, "error");
            return;
        }

        setSubmitting(true);

        LopApi.upsert(form).then((res: Lop | null) => {
            if (res) {
                window._toastbox("Cập nhật lớp thành công", "success");
                setForm( (prev: Partial<Lop>) => ({ ...prev, ...res}));
            }
        }).finally(() => {
            setSubmitting(false);
        });
    },[form, setForm]);

    const setField = useCallback(<K extends keyof Lop>(key: K, value: Lop[K]) => {
            setForm((prev: Partial<Lop>) => ({ ...prev, [key]: value }));
    }, []);

    return (
        <div className="px-2">
            <ComponentTitleStore title={lop ? "Cập nhật lớp" : "Thêm mới lớp"} callbackSubmit={handleSubmit} disabledSubmit={submitting} />
            <Row>
                <Col span={16}>
                    <LopFormFields form={form} setField={setField} />
                </Col>
            </Row>
        </div>
    );
});

const ROOT_ID = "root-store-lop";
const bladeProps: ViewStoreLopPageProps = {
    ...readRootDataProps<ViewStoreLopPageProps>(ROOT_ID),
};
mountReactComponentOnReady(ROOT_ID, <ViewStoreLop {...bladeProps} />);
