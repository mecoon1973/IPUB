import { Checkbox, Input, Radio, Select, Space, Typography } from "antd";
import { KieuBanQuyen, type PhieuDkDetai } from "../../../type";
import React, { useCallback, useMemo, useState } from "react";
import "../../../../../../css/modules/topic/PhieuDkDetai/FormFieldPhieuDkDetai.css";
import DatePicker from "../../../../core/utils/DatePicker";
import { Dayjs } from 'dayjs';
import { convertValueToDayjs } from "../../../../core/utils/helpersDayjs";
import type { DonVi, User } from "../../../../user/type";
import type { Bosach, Doituong, Lop, Mangsach, Monhoc, Tusach } from "../../../../system/type";
import { ComponentSelectAntMap, ComponentSelectAntObject } from "../../../../page/component/componentSelectAnt";
import { LabelReq } from '../../../../page/component/componentLable';
import { ModalChooseBTVComponent } from "../../../../user/components/User/ModalChooseBTV";
import {
    DeCuongEditorChrome,
    InlineLabelOpt,
    InlineLabelReq,
    InlineLabelSection,
    SectionBar,
} from "./FormFieldPhieuDkDetai.ui";
import { PhieuDkTextFieldSm } from "./PhieuDkFieldPrimitives";

/**
 * Map cố định cho select thể loại — tránh tạo object mới mỗi lần render (giảm re-render con).
 */
const PHIEU_DK_MAP_LOAI_XBP: Record<number, string> = {
    19: "Xuất bản phẩm truyền thống (sách giấy)",
    20: "Xuất bản phẩm điện tử",
    21: "Đĩa CD, sản phẩm xuất bản khác",
};

const LUA_TUOI_IDS = [358, 359, 360, 361, 362] as const;

interface FormFieldPhieuDkDetaiProps {
    form: Partial<PhieuDkDetai>;
    setField: (field: keyof PhieuDkDetai, value: any) => void;
    invalidFields?: Record<string, boolean>;
    mapTrangThai: Record<number, string>;
    listMangsach: Mangsach[];
    listDoituong: Doituong[];
    listLop: Lop[];
    listMonhoc: Monhoc[];
    listBosach: Bosach[];
    listTusach: Tusach[];
    Donvi: DonVi | null;
    listBTV: User[];
}

const FormFieldPhieuDkDetai = React.memo((props: FormFieldPhieuDkDetaiProps) => {
    const { form, setField, invalidFields, listMangsach, listDoituong, listLop, listMonhoc, listBosach, listTusach, Donvi, listBTV} = props;
    const [showModalChooseBTV, setShowModalChooseBTV] = useState(false);

    const onShowModalChooseBTV = useCallback(() => {
        setShowModalChooseBTV(true);
    }, []);

    const onHideModalChooseBTV = useCallback(() => {
        setShowModalChooseBTV(false);
    }, []);

    const setNum = useCallback(
        (key: keyof PhieuDkDetai, raw: string) => {
            const n = raw === "" ? 0 : Number(raw);
            setField(key, (Number.isNaN(n) ? 0 : n) as PhieuDkDetai[typeof key]);
        },
        [setField],
    );

    const markField = useCallback((key: string) => {
        const base: { "data-field": string; "data-invalid"?: "true" } = { "data-field": key };
        if (invalidFields?.[key]) base["data-invalid"] = "true";
        return base;
    }, [invalidFields]);

    const handlerDeleteBTV = useCallback(
        (btv: User) => {
            const newIds = form.idListBTV?.filter((id) => id !== btv.id) ?? [];
            setField("idListBTV", newIds);
            setField(
                "BienTapVien",
                newIds
                    .map((id) => listBTV.find((u) => u.id === id)?.HoTen)
                    .filter((n): n is string => Boolean(n?.trim()))
                    .join(", "),
            );
        },
        [form.idListBTV, listBTV, setField],
    );

    const handlerChooseBTV = useCallback(
        (btv: User) => {
            const newIds = [...(form.idListBTV ?? []), btv.id];
            setField("idListBTV", newIds);
            setField(
                "BienTapVien",
                newIds
                    .map((id) => (listBTV.find((u) => u.id === id) ?? (id === btv.id ? btv : undefined))?.HoTen)
                    .filter((n): n is string => Boolean(n?.trim()))
                    .join(", "),
            );
        },
        [form.idListBTV, listBTV, setField],
    );

    const handlerChangeLuatuoi = useCallback((value: unknown) => {
        setField("TypeLuaTuoi", (value as number) ?? 0);
        setField("LuaTuoi", listDoituong.find((item) => item.id === (value as number))?.TenDoiTuong ?? "");
    }, [setField, listDoituong]);

    const listLuaTuoiDoituong = useMemo(
        () =>
            listDoituong.filter((item) =>
                LUA_TUOI_IDS.includes(item.id as (typeof LUA_TUOI_IDS)[number]),
            ),
        [listDoituong],
    );

    const onDeCuongChange = useCallback((contentHTML: string) => {
        console.log(contentHTML)
        setField("DeCuong", contentHTML);
    }, [setField]);

    /**
     * `form` là một object state — mỗi lần gõ một field, toàn bộ `FormFieldPhieuDkDetai` vẫn chạy lại (điều không tránh được với `useState` + một object).
     * Tối ưu: `useMemo` tạo *cùng một React element* khi deps không đổi → phase reconciliation bỏ qua subtree đó (không gọi lại render của CKEditor / Ant Select / DatePicker trong nhánh đó).
     * Không dùng `form` trực tiếp trong deps; chỉ liệt kê từng primitive/date cần cho từng khối để khi field khác đổi, khối không liên quan giữ cache.
     */
    const memoBlockPhanI_Header = useMemo(
        () => (
            <React.Fragment>
                <SectionBar>I - ĐỀ TÀI</SectionBar>

                <div className="phieu-dk-grid-2">
                    <div className="phieu-dk-inline-row">
                        <InlineLabelOpt>Mã số</InlineLabelOpt>
                        <div className="phieu-dk-field">
                            <Input
                                size="small"
                                value={form.MaSo ?? ""}
                                disabled
                            />
                        </div>
                    </div>
                    <div className="phieu-dk-inline-row">
                        <InlineLabelReq>Ngày đăng ký</InlineLabelReq>
                        <div className="phieu-dk-field" {...markField("NgayDK")}>
                            <DatePicker
                                style={{ width: "100%" }}
                                placeholder="Ngày đăng ký"
                                value={convertValueToDayjs(form.NgayDK)}
                                onChange={(date: Dayjs | null | undefined) => {
                                    if (date) setField("NgayDK", date ? new Date(date.toISOString()) : undefined);
                                }}
                                format="DD/MM/YYYY"
                            />
                        </div>
                    </div>
                </div>

                <div className="phieu-dk-grid-3">
                    <div className="phieu-dk-inline-row">
                        <InlineLabelOpt>Mã số CXB</InlineLabelOpt>
                        <div className="phieu-dk-field">
                            <Input size="small" value={form.MaSoCXB ?? ""} disabled />
                        </div>
                    </div>
                    <div className="phieu-dk-inline-row">
                        <InlineLabelOpt>Mã số ISBN</InlineLabelOpt>
                        <div className="phieu-dk-field">
                            <Input size="small" value={form.ISBNCode ?? ""} disabled />
                        </div>
                    </div>
                    <div className="phieu-dk-radio-cell" {...markField("IsXetDuyet")}>
                        <Space wrap>
                            <Radio checked={form.IsXetDuyet !== false} onChange={() => setField("IsXetDuyet", true)}>
                                Xét duyệt
                            </Radio>
                            <Radio checked={form.IsXetDuyet === false} onChange={() => setField("IsXetDuyet", false)}>
                                Thẩm định
                            </Radio>
                        </Space>
                    </div>
                </div>

                <div className="phieu-dk-inline-row">
                    <InlineLabelReq strong>1. Tên đề tài</InlineLabelReq>
                    <PhieuDkTextFieldSm
                        field="TenDeTai"
                        value={form.TenDeTai}
                        setField={setField}
                        fieldWrapperProps={markField("TenDeTai")}
                    />
                </div>
                <div className="phieu-dk-inline-row">
                    <InlineLabelOpt>Tên nguyên bản</InlineLabelOpt>
                    <PhieuDkTextFieldSm
                        field="TenNguyenBan"
                        value={form.TenNguyenBan}
                        setField={setField}
                    />
                </div>
                <div className="phieu-dk-inline-row">
                    <InlineLabelOpt>Tên rút gọn</InlineLabelOpt>
                    <PhieuDkTextFieldSm field="tenrutgon" value={form.tenrutgon} setField={setField} />
                </div>
                <div className="phieu-dk-inline-row">
                    <InlineLabelReq strong>2. Tác giả</InlineLabelReq>
                    <PhieuDkTextFieldSm
                        field="TacGia"
                        value={form.TacGia}
                        setField={setField}
                        fieldWrapperProps={markField("TacGia")}
                    />
                </div>
                <div className="phieu-dk-inline-row">
                    <InlineLabelOpt>Địa chỉ</InlineLabelOpt>
                    <PhieuDkTextFieldSm field="DiaChi" value={form.DiaChi} setField={setField} />
                </div>

                <div className="phieu-dk-grid-2wide">
                    <div className="phieu-dk-inline-row">
                        <InlineLabelReq>Tên đơn vị đăng ký</InlineLabelReq>
                        <div className="phieu-dk-field">
                            <Input size="small" value={Donvi?.TenDonVi ?? ""} readOnly />
                        </div>
                    </div>
                    <div className="phieu-dk-inline-row">
                        <InlineLabelOpt>Biên tập viên</InlineLabelOpt>
                        <PhieuDkTextFieldSm
                            field="BienTapVien"
                            value={form.BienTapVien}
                            setField={setField}
                            readOnly
                            onClick={onShowModalChooseBTV}
                        />
                    </div>
                </div>
            </React.Fragment>
        ),
        [
            Donvi?.TenDonVi,
            form.BienTapVien,
            form.DiaChi,
            form.ISBNCode,
            form.IsXetDuyet,
            form.MaSo,
            form.MaSoCXB,
            form.NgayDK,
            form.TacGia,
            form.TenDeTai,
            form.TenNguyenBan,
            form.tenrutgon,
            markField,
            onShowModalChooseBTV,
            setField,
        ],
    );

    const memoBlockMuc11_13 = useMemo(
        () => (
            <div className="phieu-dk-block-rest">
                <div className="phieu-dk-inline-row phieu-dk-inline-row--top">
                    <InlineLabelSection>11. Đề tài tương tự</InlineLabelSection>
                    <PhieuDkTextFieldSm
                        field="DeTaiTuongTu"
                        value={form.DeTaiTuongTu}
                        setField={setField}
                        as="textarea"
                        rows={4}
                    />
                </div>
                <div className="phieu-dk-inline-row phieu-dk-inline-row--top">
                    <InlineLabelSection>12. Địa chỉ tiêu thụ</InlineLabelSection>
                    <PhieuDkTextFieldSm
                        field="DC_TieuThu"
                        value={form.DC_TieuThu}
                        setField={setField}
                        as="textarea"
                        rows={4}
                    />
                </div>

                <div className="phieu-dk-heading-13">13. Thông tin bản quyền</div>
                <div className="phieu-dk-inline-row phieu-dk-inline-row--top">
                    <InlineLabelReq>13.1. Chịu trách nhiệm bản quyền</InlineLabelReq>
                    <PhieuDkTextFieldSm
                        field="ThongTinBanQuyen"
                        value={form.ThongTinBanQuyen}
                        setField={setField}
                        fieldWrapperProps={markField("ThongTinBanQuyen")}
                        as="textarea"
                        rows={3}
                    />
                </div>
                <div className="phieu-dk-inline-row phieu-dk-inline-row--top">
                    <InlineLabelReq>13.2. Sở hữu bản quyền</InlineLabelReq>
                    <PhieuDkTextFieldSm
                        field="SoHuuBanQuyen"
                        value={form.SoHuuBanQuyen}
                        setField={setField}
                        fieldWrapperProps={markField("SoHuuBanQuyen")}
                        as="textarea"
                        rows={3}
                    />
                </div>
                <div className="phieu-dk-inline-row phieu-dk-row-133">
                    <InlineLabelOpt>13.3. Thời hạn bản quyền</InlineLabelOpt>
                    <div className="phieu-dk-field phieu-dk-field--133">
                        <div className="phieu-dk-133-item" {...markField("BanQuyen")}>
                            <Checkbox
                                id="BanQuyen"
                                checked={!!form.BanQuyen}
                                onChange={(e) => setField("BanQuyen", e.target.checked)}
                            >
                                Có bản quyền <span className="text-danger">(*)</span>
                            </Checkbox>
                        </div>
                        <div className="phieu-dk-133-item" {...markField("KieuBanQuyen")}>
                            <LabelReq>Kiểu bản quyền</LabelReq>
                            <Select
                                size="small"
                                className="w-100"
                                value={form.KieuBanQuyen === undefined || form.KieuBanQuyen === null ? undefined : form.KieuBanQuyen}
                                onChange={(v) => setField("KieuBanQuyen", v)}
                                options={[
                                    { value: KieuBanQuyen.CO_THOI_HAN, label: "Có thời hạn" },
                                    { value: KieuBanQuyen.VO_THOI_HAN, label: "Không thời hạn" },
                                ]}
                            />
                        </div>
                        <div className="phieu-dk-133-item" {...markField("BanQuyenTuNgay")}>
                            <LabelReq>Bản quyền từ ngày</LabelReq>
                            <DatePicker
                                style={{ width: "100%" }}
                                placeholder="Bản quyền từ ngày"
                                value={convertValueToDayjs(form.BanQuyenTuNgay)}
                                onChange={(date: Dayjs | null | undefined) => {
                                    if (date) setField("BanQuyenTuNgay", date ? new Date(date.toISOString()) : undefined);
                                }}
                                format="DD/MM/YYYY"
                            />
                        </div>
                        {form.KieuBanQuyen === KieuBanQuyen.CO_THOI_HAN && (
                            <React.Fragment>
                                <div className="phieu-dk-133-item" {...markField("BanQuyenDenNgay")}>
                                    <Typography.Text className="d-block mb-1">
                                        Đến ngày <span className="text-danger">(*)</span>
                                    </Typography.Text>
                                    <DatePicker
                                        style={{ width: "100%" }}
                                        placeholder="Bản quyền đến ngày"
                                        value={convertValueToDayjs(form.BanQuyenDenNgay)}
                                        onChange={(date: Dayjs | null | undefined) => {
                                            if (date) setField("BanQuyenDenNgay", date ? new Date(date.toISOString()) : undefined);
                                        }}
                                        format="DD/MM/YYYY"
                                    />
                                </div>
                            </React.Fragment>
                        )}
                    </div>
                </div>
            </div>
        ),
        [
            form.BanQuyen,
            form.BanQuyenDenNgay,
            form.BanQuyenTuNgay,
            form.DC_TieuThu,
            form.DeTaiTuongTu,
            form.KieuBanQuyen,
            form.SoHuuBanQuyen,
            form.ThongTinBanQuyen,
            markField,
            setField,
            setNum,
        ],
    );

    const memoBlockDeCuong = useMemo(
        () => (
            <React.Fragment>
                <div className="phieu-dk-field">
                    <Input.TextArea
                        rows={4}
                        size="small"
                        value={form.DeCuong ?? ""}
                        onChange={(e) => setField("DeCuong", e.target.value)}
                    />
                </div>
                <SectionBar spaced>II - ĐỀ CƯƠNG</SectionBar>
                <div className="phieu-dk-outline-wrap">
                    <div className="phieu-dk-inline-row phieu-dk-inline-row--top">
                        <InlineLabelOpt>Nội dung tóm tắt</InlineLabelOpt>
                        <div className="phieu-dk-field">
                            <DeCuongEditorChrome value={form.DeCuong ?? ""} onChange={onDeCuongChange} />
                        </div>
                    </div>
                </div>
            </React.Fragment>
        ),
        [form.DeCuong, onDeCuongChange],
    );

    /** Ba cột giữa: Ant Select + DatePicker nặng — chỉ tạo lại element khi một trong các field trong deps đổi (gõ ở phần I không làm đổi deps này). */
    const memoBlockBaCot = useMemo(
        () => (
                    <div className="phieu-dk-block border-top">
                        <div className="phieu-dk-columns">
                            <div className="phieu-dk-col">
                                <div className="phieu-dk-col-checkline">
                                    <InlineLabelOpt>Là sách dịch</InlineLabelOpt>
                                    <Checkbox
                                        id="LaDeTaiDich"
                                        checked={!!form.LaDeTaiDich}
                                        onChange={(e) => {
                                            setField("LaDeTaiDich", e.target.checked);
                                            setField("DichGia", "");
                                            setField("NguDuocDich", "");
                                            setField("ThongTinSachDich", "");
                                        }}
                                    >
                                        <span className="text-muted small">(nhập các thông tin sách dịch)</span>
                                    </Checkbox>
                                </div>
                                <div className="phieu-dk-inline-row">
                                    <InlineLabelOpt>Dịch giả</InlineLabelOpt>
                                    <div className="phieu-dk-field">
                                        <Input
                                            size="small"
                                            value={form.DichGia ?? ""}
                                            onChange={(e) => setField("DichGia", e.target.value)}
                                            disabled={!form.LaDeTaiDich}
                                        />
                                    </div>
                                </div>
                                <div className="phieu-dk-inline-row phieu-dk-inline-row--top">
                                    <InlineLabelOpt>Ngữ được dịch</InlineLabelOpt>
                                    <div className="phieu-dk-field">
                                        <Input.TextArea
                                            rows={4}
                                            size="small"
                                            value={form.NguDuocDich ?? ""}
                                            onChange={(e) => setField("NguDuocDich", e.target.value)}
                                            disabled={!form.LaDeTaiDich}
                                        />
                                    </div>
                                </div>
                                <div className="phieu-dk-inline-row">
                                    <InlineLabelOpt>Thông tin sách dịch</InlineLabelOpt>
                                    <div className="phieu-dk-field">
                                        <Input
                                            size="small"
                                            value={form.ThongTinSachDich ?? ""}
                                            onChange={(e) => setField("ThongTinSachDich", e.target.value)}
                                            disabled={!form.LaDeTaiDich}
                                        />
                                    </div>
                                </div>
                                <div className="phieu-dk-inline-row">
                                    <InlineLabelOpt>Ngữ xuất bản</InlineLabelOpt>
                                    <div className="phieu-dk-field">
                                        <Input
                                            size="small"
                                            value={form.NguXuatBan ?? "Tiếng Việt"}
                                            onChange={(e) => setField("NguXuatBan", e.target.value)}
                                        />
                                    </div>
                                </div>
                                <div className="phieu-dk-inline-row">
                                    <InlineLabelReq>HT Xuất bản</InlineLabelReq>
                                    <div className="phieu-dk-field" {...markField("HTXB")}>
                                        <Select
                                            size="small"
                                            className="w-100"
                                            value={form.HTXB === false ? 0 : 1}
                                            onChange={(v) => setField("HTXB", v === 1)}
                                            options={[
                                                { value: 1, label: "Mới" },
                                                { value: 0, label: "Tái bản" },
                                            ]}
                                        />
                                    </div>
                                </div>
                                <div className="phieu-dk-inline-row">
                                    <InlineLabelReq>Năm TB/XB</InlineLabelReq>
                                    <div className="phieu-dk-field" {...markField("NamXuatBan")}>
                                        <Input
                                            size="small"
                                            value={form.NamXuatBan ?? ""}
                                            onChange={(e) => setField("NamXuatBan", e.target.value)}
                                        />
                                    </div>
                                </div>
                                <div className="phieu-dk-inline-row">
                                    <InlineLabelOpt>Lần tái bản</InlineLabelOpt>
                                    <div className="phieu-dk-field">
                                        <Input
                                            size="small"
                                            type="number"
                                            value={form.LanTaiBan ?? 0}
                                            onChange={(e) => setNum("LanTaiBan", e.target.value)}
                                        />
                                    </div>
                                </div>
                                <div className="phieu-dk-inline-row">
                                    <InlineLabelOpt>Phương thức XB</InlineLabelOpt>
                                    <div className="phieu-dk-field">
                                        <Select
                                            size="small"
                                            className="w-100"
                                            value={form.PTXB === false ? 0 : 1}
                                            onChange={(v) => setField("PTXB", v === 1)}
                                            options={[
                                                { value: 1, label: "Tự xuất bản" },
                                                { value: 0, label: "Liên kết" },
                                            ]}
                                        />
                                    </div>
                                </div>
                                <div className="phieu-dk-inline-row">
                                    <InlineLabelOpt>Đơn vị liên kết bản thảo</InlineLabelOpt>
                                    <div className="phieu-dk-field">
                                        <Input
                                            size="small"
                                            placeholder="Gõ * để tìm kiếm tất cả"
                                            readOnly
                                        />
                                    </div>
                                </div>
                                <div className="phieu-dk-inline-row">
                                    <InlineLabelOpt>Đơn vị liên kết phát hành</InlineLabelOpt>
                                    <div className="phieu-dk-field">
                                        <Input
                                            size="small"
                                            placeholder="Gõ * để tìm kiếm tất cả"
                                            readOnly
                                        />
                                    </div>
                                </div>
                                <div className="phieu-dk-inline-row">
                                    <InlineLabelOpt>Tủ sách</InlineLabelOpt>
                                    <div className="phieu-dk-field">
                                        <ComponentSelectAntObject
                                            listData={listTusach}
                                            keyValue="id"
                                            labelValue="TenTuSach"
                                            onChange={(value) => setField("ID_TuSach", value as number)}
                                            value={form.ID_TuSach == 0 ? "" : form.ID_TuSach}
                                            placeholder="Chọn tủ sách"
                                            style={{ width: "100%" }}
                                            showSearch={true}
                                            optionFilterProp="label"
                                            filterOption={(input, option) =>
                                                option?.label.toLowerCase().indexOf(input.toLowerCase()) >= 0
                                            }
                                        />
                                    </div>
                                </div>
                            </div>

                            <div className="phieu-dk-col">
                                <div className="phieu-dk-col-checkline">
                                    <InlineLabelOpt>Là sách điện tử</InlineLabelOpt>
                                    <Checkbox
                                        id="IsSachDienTu"
                                        checked={!!form.IsSachDienTu}
                                        onChange={(e) => setField("IsSachDienTu", e.target.checked)}
                                    >
                                        <span className="text-muted small">(chọn nếu là sách điện tử)</span>
                                    </Checkbox>
                                </div>
                                {form.IsSachDienTu ? (
                                    <React.Fragment>
                                        <div className="phieu-dk-inline-row">
                                            <InlineLabelReq strong>3. Số byte dự kiến</InlineLabelReq>
                                            <div className="phieu-dk-field" {...markField("DungLuongTep")}>
                                                <Input
                                                    size="small"
                                                    type="text"
                                                    inputMode="numeric"
                                                    value={form.DungLuongTep ?? ""}
                                                    onChange={(e) => setField("DungLuongTep", e.target.value)}
                                                    placeholder="0"
                                                />
                                            </div>
                                        </div>
                                        <div className="phieu-dk-inline-row">
                                            <InlineLabelOpt>Định dạng tệp</InlineLabelOpt>
                                            <div className="phieu-dk-field">
                                                <Input
                                                    size="small"
                                                    value={form.DinhDangTep ?? ""}
                                                    onChange={(e) => setField("DinhDangTep", e.target.value)}
                                                    placeholder=".pdf, .epub, …"
                                                />
                                            </div>
                                        </div>
                                        <div className="phieu-dk-inline-row">
                                            <InlineLabelOpt>Website đăng tải hoặc nhà cung cấp</InlineLabelOpt>
                                            <div className="phieu-dk-field">
                                                <Input
                                                    size="small"
                                                    value={form.DiaChiCungCap ?? ""}
                                                    onChange={(e) => setField("DiaChiCungCap", e.target.value)}
                                                />
                                            </div>
                                        </div>
                                    </React.Fragment>
                                ) : (
                                    <React.Fragment>
                                        <div className="phieu-dk-inline-row">
                                            <InlineLabelReq strong>3. Số trang dự kiến</InlineLabelReq>
                                            <div className="phieu-dk-field" {...markField("SoTrangDK")}>
                                                <Input
                                                    size="small"
                                                    type="number"
                                                    value={form.SoTrangDK ?? 0}
                                                    onChange={(e) => setNum("SoTrangDK", e.target.value)}
                                                />
                                            </div>
                                        </div>
                                        <div className="phieu-dk-inline-row">
                                            <InlineLabelReq>Khổ sách</InlineLabelReq>
                                            <div className="phieu-dk-field d-flex align-items-center gap-1 flex-wrap">
                                                <div {...markField("Rong")}>
                                                    <Input
                                                        size="small"
                                                        type="number"
                                                        placeholder="Rộng"
                                                        className="w-auto"
                                                        style={{ maxWidth: "5rem" }}
                                                        value={form.Rong ?? ""}
                                                        onChange={(e) => setField("Rong", e.target.value)}
                                                    />
                                                </div>
                                                <span>×</span>
                                                <div {...markField("Dai")}>
                                                    <Input
                                                        size="small"
                                                        type="number"
                                                        placeholder="Dài"
                                                        className="w-auto"
                                                        style={{ maxWidth: "5rem" }}
                                                        value={form.Dai ?? ""}
                                                        onChange={(e) => setField("Dai", e.target.value)}
                                                    />
                                                </div>
                                                <span className="small text-muted">(rộng × dài) (cm)</span>
                                            </div>
                                        </div>
                                    </React.Fragment>
                                )}
                                <div className="phieu-dk-inline-row phieu-dk-inline-row--top">
                                    <InlineLabelReq>Số màu in</InlineLabelReq>
                                    <div className="phieu-dk-field phieu-dk-field--pair">
                                        <div {...markField("MauInRuot")}>
                                            <Input
                                                size="small"
                                                type="number"
                                                placeholder="Ruột"
                                                value={form.MauInRuot ?? ""}
                                                onChange={(e) => setNum("MauInRuot", e.target.value)}
                                            />
                                        </div>
                                        <div {...markField("MauInBia")}>
                                            <Input
                                                size="small"
                                                type="number"
                                                placeholder="Bìa"
                                                value={form.MauInBia ?? ""}
                                                onChange={(e) => setNum("MauInBia", e.target.value)}
                                            />
                                        </div>
                                    </div>
                                </div>

                                <div className="phieu-dk-col-subhead">
                                    4. Hợp đồng biên soạn xuất bản phẩm
                                </div>
                                <div className="phieu-dk-inline-row">
                                    <InlineLabelReq>4.1. Số hợp đồng</InlineLabelReq>
                                    <div className="phieu-dk-field" {...markField("SoHDBS")}>
                                        <Input
                                            size="small"
                                            placeholder="Số hợp đồng"
                                            value={form.SoHDBS ?? ""}
                                            onChange={(e) => setField("SoHDBS", e.target.value)}
                                        />
                                    </div>
                                </div>
                                <div className="phieu-dk-inline-row">
                                    <InlineLabelReq>4.2. Ngày ký</InlineLabelReq>
                                    <div className="phieu-dk-field" {...markField("NgayKyHDBS")}>
                                        <DatePicker
                                            style={{ width: "100%" }}
                                            placeholder="Ngày ký"
                                            value={convertValueToDayjs(form.NgayKyHDBS)}
                                            onChange={(date: Dayjs | null | undefined) => {
                                                if (date) setField("NgayKyHDBS", date ? new Date(date.toISOString()) : undefined);
                                            }}
                                            format="DD/MM/YYYY"
                                        />
                                    </div>
                                </div>
                                <div className="phieu-dk-inline-row">
                                    <InlineLabelReq>4.3. Kiểu hợp đồng</InlineLabelReq>
                                    <div className="phieu-dk-field" {...markField("KieuHDBS")}>
                                        <Select
                                            size="small"
                                            className="w-100"
                                            value={form.KieuHDBS === undefined || form.KieuHDBS === null ? undefined : form.KieuHDBS}
                                            onChange={(v) => setField("KieuHDBS", v)}
                                            options={[
                                                { value: 1, label: "Có thời hạn" },
                                                { value: 2, label: "Không thời hạn" },
                                            ]}
                                        />
                                    </div>
                                </div>
                                <div className="phieu-dk-inline-row phieu-dk-inline-row--top">
                                    <InlineLabelReq>4.4. Thời hạn</InlineLabelReq>
                                    <div className="phieu-dk-field phieu-dk-field--dates2">
                                        <div {...markField("TuNgayHDBS")}>
                                            <DatePicker
                                                style={{ width: "100%" }}
                                                placeholder="Từ ngày"
                                                value={convertValueToDayjs(form.TuNgayHDBS)}
                                                onChange={(date: Dayjs | null | undefined) => {
                                                    if (date) setField("TuNgayHDBS", date ? new Date(date.toISOString()) : undefined);
                                                }}
                                                format="DD/MM/YYYY"
                                            />
                                        </div>
                                        {form.KieuHDBS == 1 ? (
                                            <div {...markField("DenNgayHDBS")}>
                                                <DatePicker
                                                    style={{ width: "100%" }}
                                                    placeholder="Đến ngày"
                                                    value={convertValueToDayjs(form.DenNgayHDBS)}
                                                    onChange={(date: Dayjs | null | undefined) => {
                                                        if (date) setField("DenNgayHDBS", date ? new Date(date.toISOString()) : undefined);
                                                    }}
                                                    format="DD/MM/YYYY"
                                                />
                                            </div>
                                        ) : null}
                                    </div>
                                </div>

                                <div className="phieu-dk-col-subhead">5. Mảng sách - ấn phẩm</div>
                                <div className="phieu-dk-inline-row">
                                    <InlineLabelReq>5.1. Thể loại</InlineLabelReq>
                                    <div className="phieu-dk-field" {...markField("ID_LoaiXBP")}>
                                        <ComponentSelectAntMap
                                            mapData={PHIEU_DK_MAP_LOAI_XBP}
                                            onChange={(value) => setField("ID_LoaiXBP", value as number)}
                                            value={form.ID_LoaiXBP == 0 ? "" : form.ID_LoaiXBP}
                                            placeholder="Chọn thể loại"
                                            style={{ width: "100%" }}
                                            warning={markField("ID_LoaiXBP")["data-invalid"] === "true"}
                                        />
                                    </div>
                                </div>
                                <div className="phieu-dk-inline-row">
                                    <InlineLabelReq>5.2. Bộ SGK</InlineLabelReq>
                                    <div className="phieu-dk-field" {...markField("ID_BoSach")}>
                                        <ComponentSelectAntObject
                                            listData={listBosach}
                                            keyValue="id"
                                            labelValue="TenBo"
                                            onChange={(value) => setField("ID_BoSach", value as number)}
                                            value={form.ID_BoSach == 0 ? "" : form.ID_BoSach}
                                            placeholder="Chọn bộ SGK"
                                            style={{ width: "100%" }}
                                            showSearch={true}
                                            optionFilterProp="label"
                                            filterOption={(input, option) =>
                                                option?.label.toLowerCase().indexOf(input.toLowerCase()) >= 0
                                            }
                                            warning={markField("ID_BoSach")["data-invalid"] === "true"}
                                        />
                                    </div>
                                </div>
                                <div className="phieu-dk-inline-row">
                                    <InlineLabelReq>5.3. Mảng sách</InlineLabelReq>
                                    <div className="phieu-dk-field" {...markField("ID_MangSach")}>
                                        <ComponentSelectAntObject
                                            listData={listMangsach}
                                            keyValue="id"
                                            labelValue="TenMang"
                                            onChange={(value) => setField("ID_MangSach", value as number)}
                                            value={form.ID_MangSach == 0 ? "" : form.ID_MangSach}
                                            placeholder="Chọn mảng sách"
                                            style={{ width: "100%" }}
                                            showSearch={true}
                                            optionFilterProp="label"
                                            filterOption={(input, option) =>
                                                option?.label.toLowerCase().indexOf(input.toLowerCase()) >= 0
                                            }
                                            warning={markField("ID_MangSach")["data-invalid"] === "true"}
                                        />
                                    </div>
                                </div>
                            </div>

                            <div className="phieu-dk-col">
                                <div className="phieu-dk-col-subhead">
                                    6. Đối tượng sử dụng và lứa tuổi
                                </div>
                                <div className="phieu-dk-inline-row">
                                    <InlineLabelReq>6.1. Đối tượng</InlineLabelReq>
                                    <div className="phieu-dk-field" {...markField("TypeLuaTuoi")}>
                                        <ComponentSelectAntObject
                                            listData={listDoituong}
                                            keyValue="id"
                                            labelValue="TenDoiTuong"
                                            onChange={handlerChangeLuatuoi}
                                            value={form.TypeLuaTuoi ?? ""}
                                            placeholder="Chọn đối tượng"
                                            style={{ width: "100%" }}
                                            showSearch={true}
                                            optionFilterProp="label"
                                            filterOption={(input, option) =>
                                                option?.label.toLowerCase().indexOf(input.toLowerCase()) >= 0
                                            }
                                            warning={markField("TypeLuaTuoi")["data-invalid"] === "true"}
                                        />
                                    </div>
                                </div>
                                <div className="phieu-dk-inline-row">
                                    <InlineLabelReq>6.2. Lứa tuổi</InlineLabelReq>
                                    <div className="phieu-dk-field" {...markField("LuaTuoi")}>
                                        <ComponentSelectAntObject
                                            listData={listLuaTuoiDoituong}
                                            keyValue="id"
                                            labelValue="TenDoiTuong"
                                            onChange={handlerChangeLuatuoi}
                                            value={form.TypeLuaTuoi ?? ""}
                                            placeholder="Chọn đối tượng"
                                            style={{ width: "100%" }}
                                            showSearch={true}
                                            optionFilterProp="label"
                                            filterOption={(input, option) =>
                                                option?.label.toLowerCase().indexOf(input.toLowerCase()) >= 0
                                            }
                                            warning={markField("LuaTuoi")["data-invalid"] === "true"}
                                        />
                                    </div>
                                </div>
                                <div className="phieu-dk-col-checkline d-flex align-items-start gap-2 flex-wrap">
                                    <Checkbox id="CanhBao" checked={!!form.CanhBao} onChange={(e) => setField("CanhBao", e.target.checked)} />
                                    <InlineLabelOpt>
                                        6.3. Cảnh báo (chọn nếu cần người lớn hướng dẫn trẻ em đọc)
                                    </InlineLabelOpt>
                                </div>

                                <div className="phieu-dk-col-subhead">7. Bậc học, cấp học, lớp</div>
                                <div className="phieu-dk-inline-row">
                                    <InlineLabelReq>Cấp, lớp</InlineLabelReq>
                                    <div className="phieu-dk-field" {...markField("ID_Lop")}>
                                        <ComponentSelectAntObject
                                            listData={listLop}
                                            keyValue="id"
                                            labelValue="TenLop"
                                            onChange={(value) => setField("ID_Lop", value as number)}
                                            value={form.ID_Lop == 0 ? "" : form.ID_Lop}
                                            placeholder="Chọn lớp"
                                            style={{ width: "100%" }}
                                            warning={markField("ID_Lop")["data-invalid"] === "true"}
                                        />
                                    </div>
                                </div>
                                <div className="phieu-dk-inline-row">
                                    <InlineLabelOpt>Cấp, lớp khác</InlineLabelOpt>
                                    <div className="phieu-dk-field">
                                        <Input
                                            size="small"
                                            value={form.CapLopKhac ?? ""}
                                            onChange={(e) => setField("CapLopKhac", e.target.value)}
                                        />
                                    </div>
                                </div>
                                <div className="phieu-dk-inline-row">
                                    <InlineLabelReq>8. Môn học</InlineLabelReq>
                                    <div className="phieu-dk-field" {...markField("ID_MonHoc")}>
                                        <ComponentSelectAntObject
                                            listData={listMonhoc}
                                            keyValue="id"
                                            labelValue="TenMonHoc"
                                            onChange={(value) => setField("ID_MonHoc", value as number)}
                                            value={form.ID_MonHoc == 0 ? "" : form.ID_MonHoc}
                                            placeholder="Chọn môn học"
                                            style={{ width: "100%" }}
                                            showSearch={true}
                                            optionFilterProp="label"
                                            filterOption={(input, option) =>
                                                option?.label.toLowerCase().indexOf(input.toLowerCase()) >= 0
                                            }
                                            warning={markField("ID_MonHoc")["data-invalid"] === "true"}
                                        />
                                    </div>
                                </div>
                                <div className="phieu-dk-inline-row">
                                    <InlineLabelOpt>9. TĐ đủ bản thảo</InlineLabelOpt>
                                    <div className="phieu-dk-field">
                                        <Input
                                            size="small"
                                            value={form.ThoiDiemCoDuBT ?? ""}
                                            onChange={(e) => setField("ThoiDiemCoDuBT", e.target.value)}
                                        />
                                    </div>
                                </div>
                                <div className="phieu-dk-col-subhead">10. Thời điểm ra sách</div>
                                <div className="phieu-dk-inline-row">
                                    <InlineLabelReq>SL in</InlineLabelReq>
                                    <div className="phieu-dk-field" {...markField("SoLuongDK")}>
                                        <Input
                                            size="small"
                                            type="number"
                                            value={form.SoLuongDK ?? 0}
                                            onChange={(e) => setNum("SoLuongDK", e.target.value)}
                                        />
                                    </div>
                                </div>
                                <div className="phieu-dk-inline-row">
                                    <InlineLabelOpt>Giá bìa dự kiến</InlineLabelOpt>
                                    <div className="phieu-dk-field">
                                        <Input
                                            size="small"
                                            type="number"
                                            value={form.GiaBia ?? 0}
                                            onChange={(e) => setNum("GiaBia", e.target.value)}
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
        ),
        [
            form.CanhBao,
            form.CapLopKhac,
            form.DenNgayHDBS,
            form.DiaChiCungCap,
            form.DichGia,
            form.DinhDangTep,
            form.DungLuongTep,
            form.Dai,
            form.GiaBia,
            form.HTXB,
            form.ID_BoSach,
            form.ID_LoaiXBP,
            form.ID_Lop,
            form.ID_MangSach,
            form.ID_MonHoc,
            form.ID_TuSach,
            form.IsSachDienTu,
            form.KieuHDBS,
            form.LaDeTaiDich,
            form.LanTaiBan,
            form.MauInBia,
            form.MauInRuot,
            form.NamXuatBan,
            form.NgayKyHDBS,
            form.NguDuocDich,
            form.NguXuatBan,
            form.PTXB,
            form.Rong,
            form.SoHDBS,
            form.SoLuongDK,
            form.SoTrangDK,
            form.ThongTinSachDich,
            form.ThoiDiemCoDuBT,
            form.TuNgayHDBS,
            form.TypeLuaTuoi,
            handlerChangeLuatuoi,
            listBosach,
            listDoituong,
            listLop,
            listLuaTuoiDoituong,
            listMangsach,
            listMonhoc,
            listTusach,
            markField,
            setField,
            setNum,
        ],
    );

    return (
        <React.Fragment>
            <div className="phieu-dk-form">
                <div>
                    {memoBlockPhanI_Header}
                    {memoBlockBaCot}
                    {memoBlockMuc11_13}
                    {memoBlockDeCuong}
                </div>
            </div>
            <ModalChooseBTVComponent
                show={showModalChooseBTV}
                onHide={() => onHideModalChooseBTV()}
                listChooseBTV={form.idListBTV ?? []}
                listBTV={listBTV}
                handlerChooseBTV={handlerChooseBTV}
                handlerDeleteBTV={handlerDeleteBTV}
            />
        </React.Fragment>
    );
});

export default FormFieldPhieuDkDetai;
