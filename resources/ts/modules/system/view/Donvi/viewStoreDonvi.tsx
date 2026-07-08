import {
    mountReactComponentOnReady,
    readRootDataProps,
} from "../../../core/utils/helpers";
import React, { useState } from "react";
import type { DonVi } from "../../../user/type/DonVi";
import { useCallback } from "react";
import { DonviFormFields } from "../../component/Donvi/DonviFormFields";
import { DonviApi } from "../../api/DonviApi";
import { useGetDonVi } from "../../hooks/Donvi/useGetDonVi";
import { useGetHDXB } from "../../hooks/HDXB/useGetHDXB";
import { ComponentTitleStore } from "../../../page/component/componentTitleStore";

function emptyFormState(): Partial<DonVi> {
    return {
        id: 0,
        MaDonVi: "",
        TenDonVi: "",
        MaSoPhu: "",
        ParentID: 0,
        Email: "",
        SoDienThoai: "",
        Website: "",
        SoFax: "",
        DiaChi: "",
        TinhThanh: "",
        MaTinh: "",
        ThuTu: 0,
        MST: "",
        TaiNganHang: "",
        SoTaiKhoan: "",
        NhaIn: false,
        DauThau: false,
        BienTap: false,
        LienKet: false,
        NoiBo: false,
        IsCreateQDXB: false,
        KiHieuMoi: "",
        KiHieuTaiBan: "",
        KhoaGuiNhan: "",
    };
}

interface ViewStoreDonviPageProps {
    donvi?: DonVi | null;
    parentId?: number;
}

export const ViewStoreDonvi = React.memo((props: ViewStoreDonviPageProps) => {
    const { donvi, parentId } = props;
    const [form, setForm] = useState<Partial<DonVi>>(() => {
        if (donvi) {
            return donvi;
        }
        return {
            ...emptyFormState(),
            ParentID: parentId ?? 0,
        };
    });
    const [submitting, setSubmitting] = useState(false);

    const { listDonvi } = useGetDonVi();
    const { listHDXB } = useGetHDXB();

    const handleSubmit = useCallback(() => {

        const mapKeysRequired = {
            "MaDonVi": "Mã đơn vị",
            "TenDonVi": "Tên đơn vị",
            "KiHieuMoi": "Kí hiệu mới",
            "KiHieuTaiBan": "Kí hiệu tái bản",
        };

        const messageRequired = Object.keys(mapKeysRequired).map(key => !form[key as keyof DonVi] ? mapKeysRequired[key as keyof typeof mapKeysRequired] : "").filter(Boolean).join(", ");
        if (messageRequired) {
            window._toastbox(`Vui lòng nhập đầy đủ thông tin: ${messageRequired}`, "error");
            return;
        }

        setSubmitting(true);

        DonviApi.upsert(form).then((res: DonVi | null) => {
            if (res) {
                window._toastbox("Cập nhật đơn vị thành công", "success");
                setForm( (prev: Partial<DonVi>) => ({ ...prev, ...res}));
            }
        }).finally(() => {
            setSubmitting(false);
        });
    },[form, setForm]);

    const setField = useCallback(<K extends keyof DonVi>(key: K, value: DonVi[K]) => {
            setForm((prev: Partial<DonVi>) => ({ ...prev, [key]: value }));
        }, []);

    return (
        <div className="px-1">
            <ComponentTitleStore title={donvi ? "Cập nhật đơn vị" : "Thêm mới đơn vị"} callbackSubmit={handleSubmit} disabledSubmit={submitting} />
            <DonviFormFields form={form} setField={setField} listDonvi={listDonvi} listHDXB={listHDXB} />
        </div>
    );
});

const ROOT_ID = "root-store-donvi";
const bladeProps = readRootDataProps<ViewStoreDonviPageProps>(ROOT_ID) ?? {};
mountReactComponentOnReady(ROOT_ID, <ViewStoreDonvi {...bladeProps} />);
