import React, { useCallback, useState } from "react";
import {
    mountReactComponentOnReady,
    readRootDataProps,
} from "../../../core/utils/helpers";
import { ComponentTitleStore } from "../../../page/component/componentTitleStore";
import type { Mangsach } from "../../../system/type";
import type { DonVi, User } from "../../../user/type";
import type { Sach } from "../../../book/type/Sach";
import { PhieuChuyenBanThaoApi } from "../../api/PhieuChuyenBanThaoApi";
import { FormFieldPhieuChuyenBanThao } from "../../component/PhieuChuyenBanThao/FormFieldPhieuChuyenBanThao";
import type { PhieuChuyenBanThao } from "../../type/PhieuChuyenBanThao";
import { parseIdListBienTapVien } from "../../type/PhieuChuyenBanThao";

interface ViewStorePhieuChuyenBanThaoProps {
    PhieuChuyenBanThao?: PhieuChuyenBanThao | null;
    listDonvi?: DonVi[];
    listMangsach?: Mangsach[];
    listBTV?: User[];
}

function mapSachToForm(sach: Sach): Partial<PhieuChuyenBanThao> {
    return {
        ID_Sach: sach.id,
        ID_DeTai: sach.ID_DeTai || null,
        ID_MangSach: sach.ID_MangSach || null,
        ID_DV: sach.ID_DonVi || null,
        TacGia: sach.TacGia || "",
        BienTapVien: (sach as Sach & { BienTapVien?: string }).BienTapVien || "",
        SoTrang: Number(sach.SoTrang) || 0,
        Rong: Number(sach.Rong) || 0,
        Dai: Number(sach.Dai) || 0,
        MauInBia: Number(sach.MauInBia) || 0,
        MauInRout: Number(sach.MauInRuot) || 0,
        IsSachDienTu: !!sach.IsSachDienTu,
        DinhDangTep: sach.DinhDangTep || ".exe, .pdf, .epub",
        DungLuongTep: sach.DungLuongTep || "",
        DiaChiCungCap: sach.DiaChiCungCap || "",
        sach: {
            id: sach.id,
            MaSo: sach.MaSo,
            TenSach: sach.TenSach,
            MaSoCXB: sach.MaSoCXB,
            ISBNCode: sach.ISBNCode,
            NamXuatBan: sach.NamXuatBan,
            NamTaiBan: sach.NamTaiBan,
            ID_MangSach: sach.ID_MangSach,
        },
    };
}

function emptyFormState(): Partial<PhieuChuyenBanThao> {
    return {
        id: 0,
        ID_Sach: null,
        ID_DeTai: null,
        ID_DV: null,
        ID_MangSach: null,
        ID_BTVNhan: null,
        ID_ListBienTapVien: "",
        idListBTV: [],
        ID_NguoiKy: null,
        NgayGiao: new Date(),
        NgayNhan: null,
        NguoiGiao: "",
        NguoiNhan: "",
        TacGia: "",
        BienTapVien: "",
        SoTrang: 0,
        SoTrangRuotSach: 0,
        SoTrangPhuBan: 0,
        SoBo: 0,
        SoBoBanThao: 0,
        SoBoBiaMau: 0,
        SoBoPhimBia: 0,
        Dai: 0,
        Rong: 0,
        MauInBia: 0,
        MauInRout: 0,
        SoMauInBia: 0,
        LanIn: 1,
        CheBanCan: false,
        CoAoBoc: false,
        LoaiBia: false,
        LoaiPhieu: false,
        IsSachDienTu: false,
        MaDVIN: "",
        DiaChiCungCap: "",
        DinhDangTep: ".exe, .pdf, .epub",
        DungLuongTep: "",
        GhiChu: "",
    };
}

function initFormState(phieu?: PhieuChuyenBanThao | null): Partial<PhieuChuyenBanThao> {
    if (!phieu) {
        return { ...emptyFormState() };
    }
    const idListBTV = phieu.idListBTV ?? parseIdListBienTapVien(phieu.ID_ListBienTapVien);
    return { ...emptyFormState(), ...phieu, idListBTV };
}

export const ViewStorePhieuChuyenBanThao = React.memo((props: ViewStorePhieuChuyenBanThaoProps) => {
    const { PhieuChuyenBanThao: initialPhieu, listDonvi = [], listMangsach = [], listBTV = [] } = props;
    const isEdit = !!(initialPhieu && initialPhieu.id);

    const [form, setForm] = useState<Partial<PhieuChuyenBanThao>>(() => initFormState(initialPhieu));
    const [submitting, setSubmitting] = useState(false);

    const setField = useCallback(<K extends keyof PhieuChuyenBanThao>(key: K, value: PhieuChuyenBanThao[K]) => {
        setForm((prev) => ({ ...prev, [key]: value }));
    }, []);

    const handleChooseSach = useCallback((sach: Sach) => {
        setForm((prev) => ({ ...prev, ...mapSachToForm(sach) }));
    }, []);

    const handleClearSach = useCallback(() => {
        setForm((prev) => ({
            ...prev,
            ID_Sach: null,
            ID_DeTai: null,
            sach: null,
        }));
    }, []);

    const handleSubmit = useCallback(async () => {
        if (!(form.ID_Sach ?? 0)) {
            window._toastbox("Vui lòng chọn sách trước khi lưu phiếu", "warning");
            return;
        }

        setSubmitting(true);
        const { sach, donvi, nguoiKy, idListBTV, ...payload } = form;
        const res = await PhieuChuyenBanThaoApi.store({
            ...payload,
            id: form.id ?? 0,
        });
        setSubmitting(false);
        if (!res) return;

        window._toastbox(`${isEdit ? "Cập nhật" : "Thêm mới"} phiếu chuyển bản thảo thành công`, "success");
        if (!isEdit) {
            window.location.href = "/phieu-chuyen-ban-thao/quan-ly";
            return;
        }

        setForm(initFormState(res));
    }, [form, isEdit]);

    return (
        <div className="px-2 py-2">
            <ComponentTitleStore
                title={isEdit ? "Cập nhật phiếu chuyển bản thảo sản xuất" : "Thêm mới phiếu chuyển bản thảo sản xuất"}
                callbackSubmit={handleSubmit}
                disabledSubmit={submitting}
            />
            <FormFieldPhieuChuyenBanThao
                form={form}
                listDonvi={listDonvi}
                listMangsach={listMangsach}
                listBTV={listBTV}
                onChooseSach={handleChooseSach}
                onClearSach={handleClearSach}
                setField={setField}
            />
        </div>
    );
});

const ROOT_ID = "root-store-phieu-chuyen-ban-thao";
const bladeProps: ViewStorePhieuChuyenBanThaoProps = {
    PhieuChuyenBanThao: null,
    listDonvi: [],
    listMangsach: [],
    listBTV: [],
    ...readRootDataProps<ViewStorePhieuChuyenBanThaoProps>(ROOT_ID),
};
mountReactComponentOnReady(ROOT_ID, <ViewStorePhieuChuyenBanThao {...bladeProps} />);
