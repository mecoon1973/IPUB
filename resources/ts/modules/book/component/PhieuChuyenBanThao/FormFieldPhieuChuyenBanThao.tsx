import React from "react";
import { Checkbox, Input, InputNumber, Radio } from "antd";
import type { PhieuChuyenBanThao } from "../../type";
import DatePicker from "../../../core/utils/DatePicker";
import { convertValueToDayjs } from "../../../core/utils/helpersDayjs";
import {
    CheckboxRow,
    FormField,
    FormSection,
    RadioRow,
    SplitField,
    SplitSep,
} from "../../../page/component/componentHelperForm";

interface FormFieldPhieuChuyenBanThaoProps {
    form: Partial<PhieuChuyenBanThao>;
    setField: <K extends keyof PhieuChuyenBanThao>(key: K, value: PhieuChuyenBanThao[K]) => void;
}

const gridBase = "grid gap-3 md:gap-4";
const grid12 = `${gridBase} grid-cols-1 md:grid-cols-12`;
const grid933 = `${gridBase} grid-cols-1 md:grid-cols-8`;
const grid4 = `${gridBase} grid-cols-1 sm:grid-cols-2 lg:grid-cols-4`;
const grid3 = `${gridBase} grid-cols-1 sm:grid-cols-2 lg:grid-cols-3`;
const grid2 = `${gridBase} grid-cols-1 md:grid-cols-2`;
const grid1 = `${gridBase} grid-cols-1`;

export const FormFieldPhieuChuyenBanThao = React.memo((props: FormFieldPhieuChuyenBanThaoProps) => {
    const { form, setField } = props;

    return (
        <div className="flex flex-col gap-4 py-1">
            <FormSection title="Thông tin sách">
                <div className={grid12}>
                    <FormField label="Năm XB/TB" className="md:col-span-2">
                        <Input size="small" disabled />
                    </FormField>
                    <FormField label="Tên sách / Mã số" className="md:col-span-7">
                        <Input size="small" disabled />
                    </FormField>
                    <FormField label="Mã đơn vị" className="md:col-span-3">
                        <Input
                            size="small"
                            value={form.MaDVIN ?? ""}
                            onChange={(e) => setField("MaDVIN", e.target.value)}
                        />
                    </FormField>
                </div>

                <div className={`${grid933} mt-3.5`}>
                    <FormField label="Mã số CXB" className="md:col-span-3">
                        <Input size="small" disabled />
                    </FormField>
                    <FormField label="Mã số ISBN" className="md:col-span-3">
                        <Input size="small" disabled />
                    </FormField>
                    <FormField label="Tác giả" className="md:col-span-2">
                        <Input
                            size="small"
                            value={form.TacGia ?? ""}
                            onChange={(e) => setField("TacGia", e.target.value)}
                        />
                    </FormField>
                </div>

                <div className={`${grid1} mt-3.5`}>
                    <FormField label="Biên tập viên" className="col-span-full">
                        <Input
                            size="small"
                            value={form.BienTapVien ?? ""}
                            onChange={(e) => setField("BienTapVien", e.target.value)}
                        />
                    </FormField>
                </div>
            </FormSection>

            <FormSection title="Loại phiếu & kỹ thuật">
                <div className={grid1}>
                    <FormField label="Loại phiếu" required className="col-span-full">
                        <RadioRow>
                            <Radio.Group
                                value={form.LoaiPhieu ?? false}
                                onChange={(e) => setField("LoaiPhieu", e.target.value)}
                            >
                                <Radio value={false}>Là sách tham khảo</Radio>
                                <Radio value={true}>Là sách giáo khoa</Radio>
                            </Radio.Group>
                        </RadioRow>
                    </FormField>
                </div>

                <div className={`${grid4} mt-3.5`}>
                    <FormField label="In lần thứ">
                        <InputNumber
                            size="small"
                            value={form.LanIn ?? 0}
                            onChange={(value) => setField("LanIn", Number(value ?? 0))}
                        />
                    </FormField>
                    <FormField label="Khổ sách (Rộng x Dài)">
                        <SplitField>
                            <InputNumber
                                size="small"
                                className="w-full"
                                value={form.Rong ?? 0}
                                onChange={(value) => setField("Rong", Number(value ?? 0))}
                            />
                            <SplitSep>x</SplitSep>
                            <InputNumber
                                size="small"
                                className="w-full"
                                value={form.Dai ?? 0}
                                onChange={(value) => setField("Dai", Number(value ?? 0))}
                            />
                        </SplitField>
                    </FormField>
                    <FormField label="Số trang">
                        <InputNumber
                            size="small"
                            value={form.SoTrang ?? 0}
                            onChange={(value) => setField("SoTrang", Number(value ?? 0))}
                        />
                    </FormField>
                    <FormField label="Mảng sách">
                        <Input size="small" disabled />
                    </FormField>
                </div>
            </FormSection>

            <FormSection title="Số bộ & in ấn">
                <div className={grid3}>
                    <FormField label="Số mẫu in ruột">
                        <InputNumber
                            size="small"
                            value={form.MauInRout ?? 0}
                            onChange={(value) => setField("MauInRout", Number(value ?? 0))}
                        />
                    </FormField>
                    <FormField label="Số mẫu in bìa">
                        <SplitField>
                            <InputNumber
                                size="small"
                                className="w-full"
                                value={form.MauInBia ?? 0}
                                onChange={(value) => setField("MauInBia", Number(value ?? 0))}
                            />
                            <SplitSep>/</SplitSep>
                            <InputNumber
                                size="small"
                                className="w-full"
                                value={form.SoMauInBia ?? 0}
                                onChange={(value) => setField("SoMauInBia", Number(value ?? 0))}
                            />
                        </SplitField>
                    </FormField>
                    <FormField label="Bản thảo (Số bộ)">
                        <InputNumber
                            size="small"
                            value={form.SoBoBanThao ?? 0}
                            onChange={(value) => setField("SoBoBanThao", Number(value ?? 0))}
                        />
                    </FormField>
                    <FormField label="Phim bìa (Số bộ)">
                        <InputNumber
                            size="small"
                            value={form.SoBoPhimBia ?? 0}
                            onChange={(value) => setField("SoBoPhimBia", Number(value ?? 0))}
                        />
                    </FormField>
                    <FormField label="Bìa mẫu (Số bộ)">
                        <InputNumber
                            size="small"
                            value={form.SoBoBiaMau ?? 0}
                            onChange={(value) => setField("SoBoBiaMau", Number(value ?? 0))}
                        />
                    </FormField>
                    <FormField label="Số trang ruột sách">
                        <InputNumber
                            size="small"
                            value={form.SoTrangRuotSach ?? 0}
                            onChange={(value) => setField("SoTrangRuotSach", Number(value ?? 0))}
                        />
                    </FormField>
                    <FormField label="Số bộ">
                        <InputNumber
                            size="small"
                            value={form.SoBo ?? 0}
                            onChange={(value) => setField("SoBo", Number(value ?? 0))}
                        />
                    </FormField>
                    <FormField label="Phụ bản (Số trang)">
                        <InputNumber
                            size="small"
                            value={form.SoTrangPhuBan ?? 0}
                            onChange={(value) => setField("SoTrangPhuBan", Number(value ?? 0))}
                        />
                    </FormField>
                </div>
            </FormSection>

            <FormSection title="Sách điện tử">
                <div className={grid3}>
                    <FormField label="Là sách điện tử">
                        <CheckboxRow>
                            <Checkbox
                                checked={form.IsSachDienTu ?? false}
                                onChange={(e) => setField("IsSachDienTu", e.target.checked)}
                            />
                        </CheckboxRow>
                    </FormField>
                    <FormField label="Dung lượng tệp">
                        <Input
                            size="small"
                            value={form.DungLuongTep ?? ""}
                            onChange={(e) => setField("DungLuongTep", e.target.value)}
                        />
                    </FormField>
                    <FormField label="Định dạng tệp">
                        <Input
                            size="small"
                            placeholder="exe..pdf..cdr"
                            value={form.DinhDangTep ?? ""}
                            onChange={(e) => setField("DinhDangTep", e.target.value)}
                        />
                    </FormField>
                </div>
            </FormSection>

            <FormSection title="Bìa & địa chỉ">
                <div className={grid2}>
                    <FormField label="Loại bìa">
                        <RadioRow>
                            <Radio.Group
                                value={form.LoaiBia ?? false}
                                onChange={(e) => setField("LoaiBia", e.target.value)}
                            >
                                <Radio value={true}>Cứng</Radio>
                                <Radio value={false}>Mềm</Radio>
                            </Radio.Group>
                        </RadioRow>
                    </FormField>
                    <FormField label="Áo bọc">
                        <RadioRow>
                            <Radio.Group
                                value={form.CoAoBoc ?? false}
                                onChange={(e) => setField("CoAoBoc", e.target.value)}
                            >
                                <Radio value={true}>Có áo bọc</Radio>
                                <Radio value={false}>Không có áo bọc</Radio>
                            </Radio.Group>
                        </RadioRow>
                    </FormField>
                </div>

                <div className={`${grid1} mt-3.5`}>
                    <FormField label="Địa chỉ cung cấp" className="col-span-full">
                        <Input
                            size="small"
                            value={form.DiaChiCungCap ?? ""}
                            onChange={(e) => setField("DiaChiCungCap", e.target.value)}
                        />
                    </FormField>
                </div>
            </FormSection>

            <FormSection title="Giao nhận">
                <div className={grid1}>
                    <FormField label="Đơn vị giao" className="col-span-full">
                        <Input
                            size="small"
                            value={form.NguoiNhan ?? ""}
                            onChange={(e) => setField("NguoiNhan", e.target.value)}
                        />
                    </FormField>
                    <FormField label="Người ký (Bên giao)" className="col-span-full">
                        <Input size="small" disabled />
                    </FormField>
                </div>

                <div className={`${grid3} mt-3.5`}>
                    <FormField label="Người giao">
                        <Input
                            size="small"
                            value={form.NguoiGiao ?? ""}
                            onChange={(e) => setField("NguoiGiao", e.target.value)}
                        />
                    </FormField>
                    <FormField label="Người nhận">
                        <Input size="small" disabled />
                    </FormField>
                    <FormField label="Bản thư kí biên tập">
                        <Input size="small" disabled />
                    </FormField>
                    <FormField label="Ngày giao">
                        <DatePicker
                            size="small"
                            className="w-full"
                            format="DD/MM/YYYY"
                            value={convertValueToDayjs(form.NgayGiao)}
                            onChange={(date) => setField("NgayGiao", date ? date.toDate().toISOString() : "")}
                        />
                    </FormField>
                    <FormField label="Người ký">
                        <Input size="small" disabled />
                    </FormField>
                </div>
            </FormSection>

            <FormSection title="Ghi chú">
                <div className={grid1}>
                    <FormField label="Ghi chú" className="col-span-full">
                        <Input.TextArea
                            rows={3}
                            value={form.GhiChu ?? ""}
                            onChange={(e) => setField("GhiChu", e.target.value)}
                        />
                    </FormField>
                </div>
            </FormSection>
        </div>
    );
});
