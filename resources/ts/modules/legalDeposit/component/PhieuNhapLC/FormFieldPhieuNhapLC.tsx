import React, { useCallback } from "react";
import { Checkbox, Input, Select } from "antd";
import type { Dayjs } from "dayjs";
import dayjs from "dayjs";
import type { PhieuNhapLC } from "../../type";
import DatePicker from "../../../core/utils/DatePicker";
import { convertValueToDayjs } from "../../../core/utils/helpersDayjs";
import { InlineLabelOpt, InlineLabelReq } from "../../../topic/component/PhieuDkDetai/FromField/FormFieldPhieuDkDetai.ui";
import "../../../../../css/modules/topic/PhieuDkDetai/FormFieldPhieuDkDetai.css";
import "../../../../../css/modules/legalDeposit/FormFieldPhieuNhapLC.css";

/** Các field bổ sung trên form (chưa có / khác kiểu trên model gốc). */
export type PhieuNhapLCFormState = Partial<PhieuNhapLC> & {
    Sach3Mien?: boolean;
    NamXBTB?: string;
    DonViNopLC?: string;
    LuaTuoi?: string;
    SachCanNguoiLonHD?: boolean;
    MaISBN?: string;
    ThongTinBanQuyen?: string;
    SoLuongCapPhep?: number;
    MaSoCXB?: string;
    HinhThucXB?: string;
};

const LOAI_SACH_LC_OPTIONS = [
    { value: 0, label: "— Chọn loại sách —" },
    { value: 1, label: "Sách bổ trợ SGK - Mới" },
];

interface FormFieldPhieuNhapLCProps {
    form: PhieuNhapLCFormState;
    setField: <K extends keyof PhieuNhapLCFormState>(key: K, value: PhieuNhapLCFormState[K]) => void;
    setNum: (key: keyof PhieuNhapLC, raw: string) => void;
}

function FieldWrap({
    children,
    className,
}: {
    children: React.ReactNode;
    className?: string;
}) {
    return <div className={`phieu-dk-field${className ? ` ${className}` : ""}`}>{children}</div>;
}

export const FormFieldPhieuNhapLC = React.memo((props: FormFieldPhieuNhapLCProps) => {
    const { form, setField, setNum } = props;

    const setStr = useCallback(
        (key: keyof PhieuNhapLCFormState, raw: string) => {
            setField(key, raw as PhieuNhapLCFormState[typeof key]);
        },
        [setField],
    );

    const setDate = useCallback(
        (key: keyof PhieuNhapLC, date: Dayjs | null | undefined) => {
            if (!date) return;
            setField(key, date.toDate() as PhieuNhapLCFormState[typeof key]);
        },
        [setField],
    );

    const setBool = useCallback(
        (key: keyof PhieuNhapLCFormState, checked: boolean) => {
            setField(key, checked as PhieuNhapLCFormState[typeof key]);
        },
        [setField],
    );

    return (
        <div className="phieu-dk-form phieu-lc-form">
            <div className="phieu-dk-grid-3">
                <div className="phieu-dk-inline-row">
                    <InlineLabelOpt>Ngày nhập</InlineLabelOpt>
                    <FieldWrap>
                        <DatePicker
                            className="w-100"
                            format="DD/MM/YYYY"
                            placeholder="Ngày nhập"
                            value={convertValueToDayjs(form.NgayNhap) ?? dayjs()}
                            onChange={(date) => setDate("NgayNhap", date)}
                        />
                    </FieldWrap>
                </div>
                <div className="phieu-dk-inline-row">
                    <InlineLabelOpt>Số phiếu</InlineLabelOpt>
                    <FieldWrap>
                        <Input
                            size="small"
                            type="number"
                            value={form.SoPhieu ?? ""}
                            onChange={(e) => setNum("SoPhieu", e.target.value)}
                        />
                    </FieldWrap>
                </div>
                <div className="phieu-dk-inline-row">
                    <InlineLabelOpt>Sách 3 miền</InlineLabelOpt>
                    <FieldWrap>
                        <Checkbox
                            checked={!!form.Sach3Mien}
                            onChange={(e) => setBool("Sach3Mien", e.target.checked)}
                        />
                    </FieldWrap>
                </div>
            </div>

            <div className="phieu-dk-grid-2">
                <div className="phieu-dk-inline-row">
                    <InlineLabelOpt>Năm XB/TB</InlineLabelOpt>
                    <FieldWrap>
                        <Input
                            size="small"
                            value={form.NamXBTB ?? ""}
                            onChange={(e) => setStr("NamXBTB", e.target.value)}
                        />
                    </FieldWrap>
                </div>
                <div className="phieu-dk-inline-row">
                    <InlineLabelOpt>Mã số / Tên sách</InlineLabelOpt>
                    <FieldWrap>
                        <Input
                            size="small"
                            value={form.TenSach ?? ""}
                            onChange={(e) => setStr("TenSach", e.target.value)}
                        />
                    </FieldWrap>
                </div>
            </div>

            <div className="phieu-dk-grid-3">
                <div className="phieu-dk-inline-row">
                    <InlineLabelOpt>Số QĐXB</InlineLabelOpt>
                    <FieldWrap>
                        <Input
                            size="small"
                            type="number"
                            value={form.SoQuyetDXB ?? ""}
                            onChange={(e) => setNum("SoQuyetDXB", e.target.value)}
                        />
                    </FieldWrap>
                </div>
                <div className="phieu-dk-inline-row">
                    <InlineLabelOpt>Ngày QĐXB</InlineLabelOpt>
                    <FieldWrap>
                        <DatePicker
                            className="w-100"
                            format="DD/MM/YYYY"
                            placeholder="Ngày QĐXB"
                            value={convertValueToDayjs(form.NgayQD)}
                            onChange={(date) => setDate("NgayQD", date)}
                        />
                    </FieldWrap>
                </div>
                <div className="phieu-dk-inline-row">
                    <InlineLabelOpt>Là in nối bản</InlineLabelOpt>
                    <FieldWrap>
                        <Checkbox
                            checked={!!form.LaInNoiBan}
                            onChange={(e) => setBool("LaInNoiBan", e.target.checked)}
                        />
                    </FieldWrap>
                </div>
            </div>

            <div className="phieu-dk-inline-row">
                <InlineLabelOpt>Đơn vị nộp lưu chiểu</InlineLabelOpt>
                <FieldWrap>
                    <Input
                        size="small"
                        value={form.DonViNopLC ?? form.KhoaGuiNhan ?? ""}
                        onChange={(e) => {
                            setStr("DonViNopLC", e.target.value);
                            setStr("KhoaGuiNhan", e.target.value);
                        }}
                    />
                </FieldWrap>
            </div>

            <div className="phieu-dk-inline-row">
                <InlineLabelOpt>Tác giả</InlineLabelOpt>
                <FieldWrap>
                    <Input size="small" value={form.TacGia ?? ""} onChange={(e) => setStr("TacGia", e.target.value)} />
                </FieldWrap>
            </div>

            <div className="phieu-dk-inline-row">
                <InlineLabelOpt>Biên tập viên</InlineLabelOpt>
                <FieldWrap>
                    <Input
                        size="small"
                        value={String(form.BienTapVien ?? "")}
                        onChange={(e) => setStr("BienTapVien", e.target.value)}
                    />
                </FieldWrap>
            </div>

            <div className="phieu-dk-inline-row">
                <InlineLabelOpt>Biên dịch viên</InlineLabelOpt>
                <FieldWrap>
                    <Input size="small" value={form.BienDich ?? ""} onChange={(e) => setStr("BienDich", e.target.value)} />
                </FieldWrap>
            </div>

            <div className="phieu-dk-grid-3">
                <div className="phieu-dk-inline-row">
                    <InlineLabelOpt>Ngữ được dịch</InlineLabelOpt>
                    <FieldWrap>
                        <Input
                            size="small"
                            value={form.NgonNguDichSach ?? ""}
                            onChange={(e) => setStr("NgonNguDichSach", e.target.value)}
                        />
                    </FieldWrap>
                </div>
                <div className="phieu-dk-inline-row">
                    <InlineLabelOpt>Ngữ xuất bản</InlineLabelOpt>
                    <FieldWrap>
                        <Input
                            size="small"
                            value={form.NguXuatBanSach ?? ""}
                            onChange={(e) => setStr("NguXuatBanSach", e.target.value)}
                        />
                    </FieldWrap>
                </div>
                <div className="phieu-dk-inline-row">
                    <InlineLabelOpt>Thể loại</InlineLabelOpt>
                    <FieldWrap>
                        <Input
                            size="small"
                            value={form.TheLoaiSach ?? ""}
                            onChange={(e) => setStr("TheLoaiSach", e.target.value)}
                        />
                    </FieldWrap>
                </div>
            </div>

            <div className="phieu-dk-grid-3">
                <div className="phieu-dk-inline-row">
                    <InlineLabelOpt>Khổ sách</InlineLabelOpt>
                    <FieldWrap>
                        <Input size="small" value={form.KhoSach ?? ""} onChange={(e) => setStr("KhoSach", e.target.value)} />
                    </FieldWrap>
                </div>
                <div className="phieu-dk-inline-row">
                    <InlineLabelOpt>Giá bìa</InlineLabelOpt>
                    <FieldWrap>
                        <Input
                            size="small"
                            value={form.GiaBia != null ? String(form.GiaBia) : ""}
                            onChange={(e) => setStr("GiaBia", e.target.value)}
                        />
                    </FieldWrap>
                </div>
                <div className="phieu-dk-inline-row">
                    <InlineLabelOpt>Số trang</InlineLabelOpt>
                    <FieldWrap>
                        <Input
                            size="small"
                            type="number"
                            value={form.SoTrang ?? ""}
                            onChange={(e) => setNum("SoTrang", e.target.value)}
                        />
                    </FieldWrap>
                </div>
            </div>

            <div className="phieu-dk-grid-3">
                <div className="phieu-dk-inline-row">
                    <InlineLabelOpt>Là sách điện tử</InlineLabelOpt>
                    <FieldWrap>
                        <Checkbox
                            checked={!!form.LoaiSach}
                            onChange={(e) => setBool("LoaiSach", e.target.checked)}
                        />
                    </FieldWrap>
                </div>
                <div className="phieu-dk-inline-row">
                    <InlineLabelOpt>Dung lượng tệp</InlineLabelOpt>
                    <FieldWrap>
                        <Input
                            size="small"
                            value={form.DungLuongTep ?? ""}
                            onChange={(e) => setStr("DungLuongTep", e.target.value)}
                        />
                    </FieldWrap>
                </div>
                <div className="phieu-dk-inline-row">
                    <InlineLabelOpt>Định dạng tệp</InlineLabelOpt>
                    <FieldWrap>
                        <Input
                            size="small"
                            placeholder=".exe, .pdf, .cda"
                            value={form.DinhDangTep ?? ""}
                            onChange={(e) => setStr("DinhDangTep", e.target.value)}
                        />
                    </FieldWrap>
                </div>
            </div>

            <div className="phieu-dk-grid-2wide">
                <div className="phieu-dk-inline-row">
                    <InlineLabelOpt>Lứa tuổi</InlineLabelOpt>
                    <FieldWrap>
                        <Input size="small" value={form.LuaTuoi ?? ""} onChange={(e) => setStr("LuaTuoi", e.target.value)} />
                    </FieldWrap>
                </div>
                <div className="phieu-dk-inline-row">
                    <InlineLabelOpt>Sách cần người lớn hướng dẫn trẻ đọc</InlineLabelOpt>
                    <FieldWrap>
                        <Checkbox
                            checked={!!form.SachCanNguoiLonHD}
                            onChange={(e) => setBool("SachCanNguoiLonHD", e.target.checked)}
                        />
                    </FieldWrap>
                </div>
            </div>

            <div className="phieu-dk-inline-row">
                <InlineLabelOpt>Địa chỉ cung cấp</InlineLabelOpt>
                <FieldWrap>
                    <Input
                        size="small"
                        value={form.DiaChiWebSachDienTu ?? ""}
                        onChange={(e) => setStr("DiaChiWebSachDienTu", e.target.value)}
                    />
                </FieldWrap>
            </div>

            <div className="phieu-dk-inline-row">
                <InlineLabelOpt>Mã ISBN</InlineLabelOpt>
                <FieldWrap className="phieu-dk-field--half">
                    <Input size="small" value={form.MaISBN ?? ""} onChange={(e) => setStr("MaISBN", e.target.value)} />
                </FieldWrap>
            </div>

            <div className="phieu-dk-inline-row">
                <InlineLabelOpt>Thông tin bản quyền</InlineLabelOpt>
                <FieldWrap>
                    <Input
                        size="small"
                        value={form.ThongTinBanQuyen ?? ""}
                        onChange={(e) => setStr("ThongTinBanQuyen", e.target.value)}
                    />
                </FieldWrap>
            </div>

            <div className="phieu-dk-grid-3">
                <div className="phieu-dk-inline-row">
                    <InlineLabelOpt>Số lượng cấp phép</InlineLabelOpt>
                    <FieldWrap>
                        <Input
                            size="small"
                            type="number"
                            value={form.SoLuongCapPhep ?? form.SoVB ?? ""}
                            onChange={(e) => {
                                setNum("SoVB", e.target.value);
                                setField("SoLuongCapPhep", Number(e.target.value) || 0);
                            }}
                        />
                    </FieldWrap>
                </div>
                <div className="phieu-dk-inline-row">
                    <InlineLabelOpt>HTXB</InlineLabelOpt>
                    <FieldWrap>
                        <Input
                            size="small"
                            value={form.HinhThucXB ?? ""}
                            onChange={(e) => setStr("HinhThucXB", e.target.value)}
                        />
                    </FieldWrap>
                </div>
                <div className="phieu-dk-inline-row">
                    <InlineLabelOpt>TB lần thứ</InlineLabelOpt>
                    <FieldWrap>
                        <Input
                            size="small"
                            type="number"
                            value={form.LanTaiBan ?? ""}
                            onChange={(e) => setNum("LanTaiBan", e.target.value)}
                        />
                    </FieldWrap>
                </div>
            </div>

            <div className="phieu-dk-grid-3">
                <div className="phieu-dk-inline-row">
                    <InlineLabelOpt>Mã số CXB</InlineLabelOpt>
                    <FieldWrap>
                        <Input
                            size="small"
                            value={form.MaSoCXB ?? ""}
                            onChange={(e) => setStr("MaSoCXB", e.target.value)}
                        />
                    </FieldWrap>
                </div>
                <div className="phieu-dk-inline-row">
                    <InlineLabelOpt>Ngày cấp GPXB</InlineLabelOpt>
                    <FieldWrap>
                        <DatePicker
                            className="w-100"
                            format="DD/MM/YYYY"
                            placeholder="Ngày cấp GPXB"
                            value={convertValueToDayjs(form.NgayCXBXacNhan)}
                            onChange={(date) => setDate("NgayCXBXacNhan", date)}
                        />
                    </FieldWrap>
                </div>
                <div className="phieu-dk-inline-row">
                    <InlineLabelOpt>Số tập</InlineLabelOpt>
                    <FieldWrap>
                        <Input
                            size="small"
                            type="number"
                            value={form.SoTap ?? 1}
                            onChange={(e) => setNum("SoTap", e.target.value)}
                        />
                    </FieldWrap>
                </div>
            </div>

            <div className="phieu-dk-inline-row">
                <InlineLabelOpt>Tên đơn vị liên kết</InlineLabelOpt>
                <FieldWrap>
                    <Input
                        size="small"
                        value={form.TenDonViLK ?? ""}
                        onChange={(e) => setStr("TenDonViLK", e.target.value)}
                    />
                </FieldWrap>
            </div>

            <div className="phieu-dk-grid-2wide">
                <div className="phieu-dk-inline-row">
                    <InlineLabelOpt>Địa chỉ đơn vị liên kết</InlineLabelOpt>
                    <FieldWrap>
                        <Input
                            size="small"
                            value={form.DiaChiDonViLK ?? ""}
                            onChange={(e) => setStr("DiaChiDonViLK", e.target.value)}
                        />
                    </FieldWrap>
                </div>
                <div className="phieu-dk-inline-row">
                    <InlineLabelReq>Số lượng in</InlineLabelReq>
                    <FieldWrap>
                        <Input
                            size="small"
                            type="number"
                            value={form.SoLuongIn ?? ""}
                            onChange={(e) => setNum("SoLuongIn", e.target.value)}
                        />
                    </FieldWrap>
                </div>
            </div>

            <div className="phieu-dk-grid-2wide">
                <div className="phieu-dk-inline-row">
                    <InlineLabelOpt>Loại sách theo lưu chiểu</InlineLabelOpt>
                    <FieldWrap>
                        <Select
                            size="small"
                            className="w-100"
                            options={LOAI_SACH_LC_OPTIONS}
                            value={form.ID_LoaiSachLC ?? 0}
                            onChange={(value) => setField("ID_LoaiSachLC", value)}
                        />
                    </FieldWrap>
                </div>
                <div className="phieu-dk-inline-row">
                    <InlineLabelOpt>Số lượng nộp</InlineLabelOpt>
                    <FieldWrap>
                        <Input
                            size="small"
                            type="number"
                            value={form.SoLuong ?? ""}
                            onChange={(e) => setNum("SoLuong", e.target.value)}
                        />
                    </FieldWrap>
                </div>
            </div>

            <div className="phieu-dk-inline-row">
                <InlineLabelOpt>Tên đơn vị in</InlineLabelOpt>
                <FieldWrap>
                    <Input
                        size="small"
                        value={form.TenCoSoIn ?? form.DonViIn ?? ""}
                        onChange={(e) => {
                            setStr("TenCoSoIn", e.target.value);
                            setStr("DonViIn", e.target.value);
                        }}
                    />
                </FieldWrap>
            </div>

            <div className="phieu-dk-inline-row">
                <InlineLabelOpt>Địa chỉ</InlineLabelOpt>
                <FieldWrap>
                    <Input
                        size="small"
                        value={form.DiaChiInSach ?? ""}
                        onChange={(e) => setStr("DiaChiInSach", e.target.value)}
                    />
                </FieldWrap>
            </div>

            <div className="phieu-dk-inline-row">
                <InlineLabelOpt>Ghi chú</InlineLabelOpt>
                <FieldWrap>
                    <Input size="small" value={form.GhiChu ?? ""} onChange={(e) => setStr("GhiChu", e.target.value)} />
                </FieldWrap>
            </div>
        </div>
    );
});
