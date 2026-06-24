import {
    mountReactComponentOnReady,
    readRootDataProps,
} from "../../../core/utils/helpers";
import React, { useState } from "react";
import { useCallback } from "react";
import type { PhieuDkDetai } from "../../type";
import { PhieuDkDetaiApi } from "../../api/PhieuDkDetaiApi";
import { ComponentTitleStore } from "../../../page/component/componentTitleStore";
import { Col, Row } from "antd";
import FormFieldPhieuDkDetai from "../../component/PhieuDkDetai/FromField/FormFieldPhieuDkDetai";
import type { Bosach, Doituong, Lop, Mangsach, Monhoc, Tusach } from "../../../system/type";
import type { DonVi, User } from "../../../user/type";

function emptyFormState(): Partial<PhieuDkDetai> {
    return {
        // id: 0,
        // NgayDK: new Date(),
        // TenDeTai: "ưèwè",
        // TacGia: "ưèwè",
        // HTXB: false,
        // NamXuatBan: "ưèwèwè",
        // MauInRuot: 1,
        // MauInBia: 1,
        // SoHDBS: "34234",
        // NgayKyHDBS: new Date(),
        // TuNgayHDBS: new Date(),
        // BanQuyenTuNgay: new Date(),
        // KieuHDBS: 2,
        // ID_LoaiXBP: 1,
        // ID_BoSach: 1,
        // ID_MangSach: 1,
        // ID_MonHoc: 1,
        // TypeLuaTuoi: 1,
        // LuaTuoi: "fwèwèw",
        // ID_TuSach: 1,
        // ID_Lop: 1,
        // SoLuongDK: 1,
        // ThongTinBanQuyen: "ưègre",
        // SoHuuBanQuyen: "wèwè",
        // SoTrangDK: 1,
        // Rong: "2",
        // Dai: "3",
        id: 0,
        NgayDK: new Date(),
        TenDeTai: "",
        TacGia: "",
        HTXB: false,
        NamXuatBan: "",
        MauInRuot: 0,
        MauInBia: 0,
        SoHDBS: "",
        KieuHDBS: 1,
        ID_LoaiXBP: 0,
        ID_BoSach: 0,
        ID_MangSach: 0,
        ID_MonHoc: 0,
        TypeLuaTuoi: 0,
        LuaTuoi: "",
        ID_TuSach: 0,
        ID_Lop: 0,
        SoLuongDK: 0,
        ThongTinBanQuyen: "",
        SoHuuBanQuyen: "",
        SoTrangDK: 0,
        Rong: "",
        Dai: "",
        MaSo: "",
        MaSoCXB: "",
        ISBNCode: "",
        TenNguyenBan: "",
        tenrutgon: "",
        DiaChi: "",
        BienTapVien: "",
        DeTaiTuongTu: "",
        DC_TieuThu: "",
        DeCuong: "",
        LaDeTaiDich: false,
        DichGia: "",
        NguDuocDich: "",
        ThongTinSachDich: "",
        NguXuatBan: "Tiếng Việt",
        LanTaiBan: 0,
        PTXB: false,
        IsSachDienTu: false,
        DungLuongTep: "",
        DinhDangTep: "",
        DiaChiCungCap: "",
        CanhBao: false,
        CapLopKhac: "",
        ThoiDiemCoDuBT: "",
        GiaBia: 0,
        idListBTV: [],
        BanQuyen: false,
        KieuBanQuyen: 2,
        IsXetDuyet: true,
        ID_DonVi: 0,
        TrangThai: 0,
    };
}

interface ViewStorePhieuDkDetaiProps {
    phieuDkDetai?: PhieuDkDetai | null;
    mapTrangThai : Record<number, string>;
    listMangsach: Mangsach[];
    listDoituong: Doituong[];
    listLop: Lop[];
    listMonhoc: Monhoc[];
    listBosach: Bosach[];
    listTusach: Tusach[];
    Donvi: DonVi | null;
    listBTV: User[];
}

export const ViewStorePhieuDkDetai = React.memo((props: ViewStorePhieuDkDetaiProps) => {
    const { phieuDkDetai, mapTrangThai, listMangsach, listDoituong, listLop, listMonhoc, listBosach, listTusach, Donvi, listBTV } = props;
    const [form, setForm] = useState<Partial<PhieuDkDetai>>(() => {
        if (phieuDkDetai) {
            return phieuDkDetai;
        }
        return {
            ...emptyFormState(),
            ID_DonVi: Donvi?.id ?? 0,
        };
    });
    const [submitting, setSubmitting] = useState(false);
    const [invalidFields, setInvalidFields] = useState<Record<string, boolean>>({});

    const isEmptyValue = useCallback((v: unknown) => {
        if (v === null || v === undefined) return true;
        if (typeof v === "string") return v.trim() === "";
        if (typeof v === "number") return Number.isNaN(v) || v === 0;
        if (Array.isArray(v)) return v.length === 0;
        return false;
    }, []);

    const focusFirstInvalid = useCallback((fieldKey: string) => {
        window.requestAnimationFrame(() => {
            const root = document.querySelector(`[data-field="${fieldKey}"]`) as HTMLElement | null;
            if (!root) return;

            root.scrollIntoView({ behavior: "smooth", block: "center" });

            const focusable =
                (root.querySelector("input, textarea, select, .ant-select-selector, .ant-picker, [contenteditable='true']") as
                    | HTMLElement
                    | null);
            if (!focusable) return;

            // AntD select/datepicker focus via click is more reliable than focus()
            if (focusable.classList.contains("ant-select-selector") || focusable.classList.contains("ant-picker")) {
                focusable.click();
                return;
            }

            (focusable as HTMLInputElement | HTMLTextAreaElement | HTMLSelectElement).focus?.();
        });
    }, []);

    const handleSubmit = useCallback(() => {
        const mapKeysRequired : Record<string, string> = {
            "NgayDK" : "Ngày đăng ký",
            "TenDeTai" : "Tên đề tài",
            "TacGia" : "Tác giả",
            "HTXB" : "Hệ thống xuất bản",
            "NamXuatBan" : "Năm xuất bản",
            "MauInRuot" : "Số màu in ruột",
            "MauInBia" : "Số màu in bìa",
            "SoHDBS" : "Số HDBS",
            "NgayKyHDBS" : "Ngày ký HDBS",
            "KieuHDBS" : "Kiểu HDBS",
            "TuNgayHDBS" : "Từ ngày HDBS",

            "ID_LoaiXBP" : "Loại XB/TB",
            "ID_BoSach" : "Bộ sách",
            "ID_MangSach" : "Mã mạng sách",
            "TypeLuaTuoi" : "Type lứa tuổi",
            "LuaTuoi" : "Lứa tuổi",
            "ID_Lop" : "ID lớp",
            "ID_MonHoc" : "ID môn học",
            "SoLuongDK" : "Số lượng đăng ký",
            "ThongTinBanQuyen" : "Thông tin bản quyền",
            "SoHuuBanQuyen" : "Số hữu bản quyền",
            "BanQuyen" : "Bản quyền",
            "KieuBanQuyen" : "Kiểu bản quyền",
            "BanQuyenTuNgay" : "Ngày bản quyền từ",
            "ID_DonVi" : "Mã đơn vị",
            "IsXetDuyet" : "Xét duyệt",
        };

        if(form.IsSachDienTu){
            mapKeysRequired["DungLuongTep"] = "Dung lượng tập tin";
        }else{
            mapKeysRequired["SoTrangDK"] = "Số trang dự kiến";
            mapKeysRequired["Rong"] = "Rộng";
            mapKeysRequired["Dai"] = "Dài";
        }

        if(form.KieuBanQuyen === 1){
            mapKeysRequired["BanQuyenDenNgay"] = "Ngày bản quyền đến";
        }
        if(form.KieuHDBS == 1){
            mapKeysRequired["DenNgayHDBS"] = "Đến ngày HDBS";
        }

        const missingKeys = Object.keys(mapKeysRequired).filter((key) =>
            isEmptyValue(form[key as keyof Partial<PhieuDkDetai>]),
        );
        if (missingKeys.length > 0) {
            setInvalidFields(Object.fromEntries(missingKeys.map((k) => [k, true])));
            window._toastbox(`Vui lòng nhập đầy đủ thông tin`, "error");
            const firstKey = missingKeys[0];
            if (firstKey) focusFirstInvalid(firstKey);
            return;
        }

        setSubmitting(true);

        PhieuDkDetaiApi.upsert(form).then((res: PhieuDkDetai | null) => {
            if (res) {
                window._toastbox("Cập nhật đề tài thành công", "success");
                setForm( (prev: Partial<PhieuDkDetai>) => ({ ...prev, ...res}));
            }
        }).finally(() => {
            setSubmitting(false);
        });
    },[focusFirstInvalid, form, isEmptyValue, setForm]);

    const setField = useCallback(<K extends keyof PhieuDkDetai>(key: K, value: PhieuDkDetai[K]) => {
            setForm((prev: Partial<PhieuDkDetai>) => {
                if (Object.is(prev[key], value)) return prev;
                return { ...prev, [key]: value };
            });
            setInvalidFields((prev) => {
                if (!prev[String(key)]) return prev;
                const next = { ...prev };
                delete next[String(key)];
                return next;
            });
    }, []);

    return (
        <div className="px-2">
            <ComponentTitleStore title={phieuDkDetai ? "Cập nhật đề tài" : "Thêm mới đề tài"} callbackSubmit={handleSubmit} disabledSubmit={submitting} />
            <Row>
                <Col xs={24}>
                    <FormFieldPhieuDkDetai
                        form={form}
                        setField={setField}
                        invalidFields={invalidFields}
                        mapTrangThai={mapTrangThai}
                        listMangsach={listMangsach}
                        listDoituong={listDoituong}
                        listLop={listLop}
                        listMonhoc={listMonhoc}
                        listBosach={listBosach}
                        listTusach={listTusach}
                        Donvi={Donvi}
                        listBTV={listBTV}
                    />
                </Col>

            </Row>
        </div>
    );
});

const ROOT_ID = "root-store-phieu-dk-detai";
const bladeProps: ViewStorePhieuDkDetaiProps = {
    mapTrangThai : {},
    listMangsach : [],
    listDoituong : [],
    listMonhoc: [],
    listLop: [],
    listBosach: [],
    listTusach: [],
    phieuDkDetai : null,
    Donvi: null,
    listBTV: [],
    ...readRootDataProps<ViewStorePhieuDkDetaiProps>(ROOT_ID),
};
mountReactComponentOnReady(ROOT_ID, <ViewStorePhieuDkDetai {...bladeProps} />);
