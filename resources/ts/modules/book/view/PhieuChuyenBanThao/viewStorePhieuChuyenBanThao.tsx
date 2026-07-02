import {
    mountReactComponentOnReady,
    readRootDataProps,
} from "../../../core/utils/helpers";
import React, { useCallback, useState } from "react";
import type { PhieuChuyenBanThao } from "../../type";
import { ComponentTitleStore } from "../../../page/component/componentTitleStore";
import { PhieuChuyenBanThaoApi } from "../../api/PhieuChuyenBanThaoApi";
import { FormFieldPhieuChuyenBanThao } from "../../component/PhieuChuyenBanThao/FormFieldPhieuChuyenBanThao";



function emptyFormState(): Partial<PhieuChuyenBanThao> {
    return {
        id: 0,
        BienTapVien: "",
        TacGia: "",
        MaDVIN: "",
        LoaiPhieu: false,
        LanIn: 1,
        Rong: 0,
        Dai: 0,
        SoTrang: 0,
        MauInRout: 0,
        MauInBia: 0,
        SoMauInBia: 0,
        SoBoBanThao: 0,
        SoBoPhimBia: 0,
        SoBoBiaMau: 0,
        SoTrangRuotSach: 0,
        SoBo: 0,
        SoTrangPhuBan: 0,
        IsSachDienTu: false,
        DungLuongTep: "",
        DinhDangTep: "",
        DiaChiCungCap: "",
        LoaiBia: false,
        CoAoBoc: false,
        NguoiGiao: "",
        NguoiNhan: "",
        GhiChu: "",
    };
};

interface ViewStorePhieuChuyenBanThaoProps {
    phieuChuyenBanThao?: PhieuChuyenBanThao | null;
}

export const ViewStorePhieuChuyenBanThao = React.memo((props: ViewStorePhieuChuyenBanThaoProps) => {
    const { phieuChuyenBanThao } = props;
    const [form, setForm] = useState<Partial<PhieuChuyenBanThao>>(() => {
        if (phieuChuyenBanThao) {
            return phieuChuyenBanThao;
        }
        return { ...emptyFormState() };
    });
    const [submitting, setSubmitting] = useState(false);

    const setField = useCallback(<K extends keyof PhieuChuyenBanThao>(key: K, value: PhieuChuyenBanThao[K]) => {
        setForm((prev: Partial<PhieuChuyenBanThao>) => ({ ...prev, [key]: value }));
    }, []);

    const handleSubmit = useCallback(() => {
        setSubmitting(true);
        PhieuChuyenBanThaoApi.upsert(form)
            .then((res: PhieuChuyenBanThao | null) => {
                if (res) {
                    window._toastbox(`${form.id ? "Cập nhật" : "Thêm mới"} phiếu chuyển bản thảo ${form.id ?? ""} thành công`, "success");
                    setForm((prev: Partial<PhieuChuyenBanThao>) => ({ ...prev, ...res }));
                }
            })
            .finally(() => {
                setSubmitting(false);
            });
    }, [form, setForm]);

    return (
        <div className="px-2 py-2">
            <ComponentTitleStore
                title={phieuChuyenBanThao ? "Cập nhật phiếu chuyển bản thảo" : "Thêm mới phiếu chuyển bản thảo"}
                callbackSubmit={handleSubmit}
                disabledSubmit={submitting}
            />
            <FormFieldPhieuChuyenBanThao form={form} setField={setField} />
        </div>
    );
});


const ROOT_ID = "root-store-phieu-chuyen-ban-thao";
const bladeProps: ViewStorePhieuChuyenBanThaoProps = {
    phieuChuyenBanThao: null as PhieuChuyenBanThao | null,
    ...readRootDataProps<ViewStorePhieuChuyenBanThaoProps>(ROOT_ID)
};
mountReactComponentOnReady(ROOT_ID, <ViewStorePhieuChuyenBanThao {...bladeProps} />);
