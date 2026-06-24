import {
    mountReactComponentOnReady,
    readRootDataProps,
} from "../../../core/utils/helpers";
import React, { useCallback, useState } from "react";
import { Col, Row } from "antd";
import { ComponentTitleStore } from "../../../page/component/componentTitleStore";
import type { PhieuNhapLC } from "../../type";
import { PhieuNhapLCApi } from "../../api/PhieuNhapLCApi";
import { FormFieldPhieuNhapLC, type PhieuNhapLCFormState } from "../../component/PhieuNhapLC/FormFieldPhieuNhapLC";
import dayjs from "dayjs";

function emptyFormState(): PhieuNhapLCFormState {
    return {
        id: 0,
        NgayNhap: new Date(),
        SoPhieu: 0,
        SoTap: 1,
        LaInNoiBan: false,
        LoaiSach: false,
        Sach3Mien: false,
        SachCanNguoiLonHD: false,
        NamXBTB: String(dayjs().year()),
        ID_LoaiSachLC: 1,
        IsDeleted: false,
    };
}

interface ViewStorePhieuNhapLCProps {
    phieuNhapLC?: PhieuNhapLC | null;
}

export const ViewStorePhieuNhapLC = React.memo((props: ViewStorePhieuNhapLCProps) => {
    const { phieuNhapLC } = props;
    const [form, setForm] = useState<PhieuNhapLCFormState>(() => {
        let dataForm = emptyFormState();
        if (phieuNhapLC && Object.keys(phieuNhapLC).length > 0) {
            dataForm = { ...dataForm, ...phieuNhapLC };
        }
        return dataForm;
    });
    const [submitting, setSubmitting] = useState(false);

    const setField = useCallback(<K extends keyof PhieuNhapLCFormState>(key: K, value: PhieuNhapLCFormState[K]) => {
        setForm((prev) => ({ ...prev, [key]: value }));
    }, []);

    const setNum = useCallback((key: keyof PhieuNhapLC, raw: string) => {
        const n = raw === "" ? 0 : Number(raw);
        setField(key, (Number.isNaN(n) ? 0 : n) as PhieuNhapLCFormState[typeof key]);
    }, [setField]);

    const handleSubmit = useCallback(() => {
        const mapKeysRequired: Partial<Record<keyof PhieuNhapLCFormState, string>> = {
            SoLuongIn: "Số lượng in",
        };

        const messageRequired = Object.keys(mapKeysRequired)
            .map((key) =>
                !form[key as keyof PhieuNhapLCFormState]
                    ? mapKeysRequired[key as keyof typeof mapKeysRequired]
                    : "",
            )
            .filter(Boolean)
            .join(", ");
        if (messageRequired) {
            window._toastbox(`Vui lòng nhập đầy đủ thông tin: ${messageRequired}`, "error");
            return;
        }

        setSubmitting(true);

        PhieuNhapLCApi.upsert(form as Partial<PhieuNhapLC>)
            .then((res: PhieuNhapLC | null) => {
                if (res) {
                    window._toastbox(
                        `${form.id ? "Cập nhật" : "Thêm mới"} phiếu nhập LC ${form.SoPhieu ?? ""} thành công`,
                        "success",
                    );
                    setForm((prev) => ({ ...prev, ...res }));
                }
            })
            .finally(() => {
                setSubmitting(false);
            });
    }, [form]);

    return (
        <div className="px-2">
            <ComponentTitleStore
                title={phieuNhapLC ? "Cập nhật phiếu nhập LC" : "Thêm mới phiếu nhập LC"}
                callbackSubmit={handleSubmit}
                disabledSubmit={submitting}
            />
            <Row gutter={12}>
                <Col span={24}>
                    <FormFieldPhieuNhapLC form={form} setField={setField} setNum={setNum} />
                </Col>
            </Row>
        </div>
    );
});

const ROOT_ID = "root-store-phieu-nhap-lc";
const bladeProps = readRootDataProps<ViewStorePhieuNhapLCProps>(ROOT_ID) ?? {};
mountReactComponentOnReady(ROOT_ID, <ViewStorePhieuNhapLC {...bladeProps} />);
