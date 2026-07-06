import React, { useCallback, useState } from "react";
import { Checkbox, Col, Input, Row, Select } from "antd";
import type { Dayjs } from "dayjs";
import type { DonVi } from "../../../user/type/DonVi";
import type { User } from "../../../user/type/User";
import DatePicker from "../../../core/utils/DatePicker";
import { ModalTreeDonvi } from "../../../system/component/Donvi/ModalTreeDonvi";
import { convertValueToDayjs } from "../../../core/utils/helpersDayjs";

interface UserFormFieldsProps {
    form: Partial<User>;
    listDonvi: DonVi[];
    setField: (key: keyof User, value: User[keyof User]) => void;
}

const labelCls = "mb-0 d-block";

export const UserFormFields = React.memo((props: UserFormFieldsProps) => {
    const { form, setField, listDonvi } = props;
    const [showModalChooseDonvi, setShowModalChooseDonvi] = useState(false);
    const onShowModalChooseDonvi = useCallback(() => {
        setShowModalChooseDonvi(true);
    }, []);
    const onHideModalChooseDonvi = useCallback(() => {
        setShowModalChooseDonvi(false);
    }, []);

    const setTextField = <K extends keyof User>(key: K) => (e: React.ChangeEvent<HTMLInputElement>) => {
        setField(key, e.target.value as User[K]);
    };

    const setCheckboxField = <K extends keyof User>(key: K) => (checked: boolean) => {
        setField(key, checked as User[K]);
    };

    const handlerChooseDonvi = useCallback(
        (donvi: DonVi) => {
            setField("ID_DonVi", donvi.id);
        },
        [setField],
    );

    const emailInvalid = !!(form.Email && !form.Email.includes("@"));

    return (
        <React.Fragment>
            <div className="small">
                <Row className="mb-2 align-items-center" gutter={[8, 8]}>
                    <Col xs={24} md={12}>
                        <Row className="align-items-center" gutter={[8, 8]}>
                            <Col xs={8}>
                                <span className={labelCls}>Mã cán bộ</span>
                            </Col>
                            <Col xs={16}>
                                <Input disabled value={form.MaCanBo ?? ""} />
                            </Col>
                        </Row>
                    </Col>
                </Row>

                <Row className="mb-2 align-items-center" gutter={[8, 8]}>
                    <Col xs={24} md={12}>
                        <Row className="align-items-center" gutter={[8, 8]}>
                            <Col xs={8}>
                                <span className={labelCls}>Họ tên</span>
                            </Col>
                            <Col xs={16}>
                                <Input value={form.HoTen ?? ""} onChange={setTextField("HoTen")} />
                            </Col>
                        </Row>
                    </Col>
                    <Col xs={24} md={12}>
                        <Row className="align-items-center" gutter={[8, 8]}>
                            <Col xs={8}>
                                <span className={labelCls}>Ngày sinh</span>
                            </Col>
                            <Col xs={16}>
                                <DatePicker
                                    style={{ width: "100%" }}
                                    placeholder="Chọn ngày sinh"
                                    value={convertValueToDayjs(form.NgaySinh)}
                                    format="DD/MM/YYYY"
                                    onChange={(date: Dayjs | null | undefined) =>
                                        setField("NgaySinh", date ? new Date(date.toISOString()) : undefined)
                                    }
                                />
                            </Col>
                        </Row>
                    </Col>
                </Row>

                <Row className="mb-2 align-items-center" gutter={[8, 8]}>
                    <Col xs={24} md={12}>
                        <Row className="align-items-center" gutter={[8, 8]}>
                            <Col xs={8}>
                                <span className={labelCls}>Email</span>
                            </Col>
                            <Col xs={16}>
                                <Input
                                    type="email"
                                    {...(emailInvalid ? ({ status: "error" } as const) : {})}
                                    value={form.Email ?? ""}
                                    onChange={setTextField("Email")}
                                />
                            </Col>
                        </Row>
                    </Col>
                    <Col xs={24} md={12}>
                        <Row className="align-items-center" gutter={[8, 8]}>
                            <Col xs={8}>
                                <span className={labelCls}>Số ĐT</span>
                            </Col>
                            <Col xs={16}>
                                <Input value={form.SoDienThoai ?? ""} onChange={setTextField("SoDienThoai")} />
                            </Col>
                        </Row>
                    </Col>
                </Row>

                <Row className="mb-2 align-items-center" gutter={[8, 8]}>
                    <Col xs={24} md={4}>
                        <span className={labelCls}>Địa chỉ</span>
                    </Col>
                    <Col xs={24} md={20}>
                        <Input value={form.DiaChi ?? ""} onChange={setTextField("DiaChi")} />
                    </Col>
                </Row>

                <Row className="mb-2 align-items-center" gutter={[8, 8]}>
                    <Col xs={24} md={12}>
                        <Row className="align-items-center" gutter={[8, 8]}>
                            <Col xs={8}>
                                <span className={labelCls}>Chức vụ</span>
                            </Col>
                            <Col xs={16}>
                                <Select
                                    className="w-100"
                                    value={form.ID_ChucVu ?? 0}
                                    onChange={(v) => setField("ID_ChucVu", v as User["ID_ChucVu"])}
                                    options={[
                                        { value: 0, label: "-- Chọn chức vụ --" },
                                        { value: 1, label: "BIÊN TẬP VIÊN" },
                                        { value: 2, label: "TRƯỞNG BAN" },
                                        { value: 3, label: "PHÓ BAN" },
                                    ]}
                                />
                            </Col>
                        </Row>
                    </Col>
                    <Col xs={24} md={12}>
                        <Row className="align-items-center" gutter={[8, 8]}>
                            <Col xs={8}>
                                <span className={labelCls}>Chuyên môn</span>
                            </Col>
                            <Col xs={16}>
                                <Select
                                    className="w-100"
                                    value={form.ID_ChuyenMon ?? 0}
                                    onChange={(v) => setField("ID_ChuyenMon", v as User["ID_ChuyenMon"])}
                                    options={[
                                        { value: 0, label: "-- Chọn chuyên môn --" },
                                        { value: 1, label: "Âm nhạc" },
                                        { value: 2, label: "Văn học" },
                                        { value: 3, label: "Mỹ thuật" },
                                    ]}
                                />
                            </Col>
                        </Row>
                    </Col>
                </Row>

                <Row className="mb-2" gutter={[8, 8]}>
                    <Col xs={24} md={12}>
                        <Row className="align-items-center" gutter={[8, 8]}>
                            <Col xs={8}>
                                <span className={labelCls}>Là người ký</span>
                            </Col>
                            <Col xs={16}>
                                <Checkbox checked={Boolean(form.NguoiKi)} onChange={(e) => setCheckboxField("NguoiKi")(e.target.checked)} />
                            </Col>
                        </Row>
                    </Col>
                    <Col xs={24} md={12}>
                        <Row className="align-items-center" gutter={[8, 8]}>
                            <Col xs={8}>
                                <span className={labelCls}>Người ký QĐXB</span>
                            </Col>
                            <Col xs={16}>
                                <Checkbox checked={Boolean(form.KyQDXB)} onChange={(e) => setCheckboxField("KyQDXB")(e.target.checked)} />
                            </Col>
                        </Row>
                    </Col>
                </Row>

                <Row className="mb-2" gutter={[8, 8]}>
                    <Col xs={24} md={12}>
                        <Row className="align-items-center" gutter={[8, 8]}>
                            <Col xs={8}>
                                <span className={labelCls}>Người đọc duyệt bản thảo</span>
                            </Col>
                            <Col xs={16}>
                                <Checkbox
                                    checked={Boolean(form.NguoiSoanThao)}
                                    onChange={(e) => setCheckboxField("NguoiSoanThao")(e.target.checked)}
                                />
                            </Col>
                        </Row>
                    </Col>
                    <Col xs={24} md={12}>
                        <Row className="align-items-center" gutter={[8, 8]}>
                            <Col xs={8}>
                                <span className={labelCls}>Người ủy quyền ký QĐXB</span>
                            </Col>
                            <Col xs={16}>
                                <Checkbox checked={Boolean(form.UQKyQDXB)} onChange={(e) => setCheckboxField("UQKyQDXB")(e.target.checked)} />
                            </Col>
                        </Row>
                    </Col>
                </Row>

                <Row className="mb-2" gutter={[8, 8]}>
                    <Col xs={24} md={12}>
                        <Row className="align-items-center" gutter={[8, 8]}>
                            <Col xs={8}>
                                <span className={labelCls}>Là biên tập viên</span>
                            </Col>
                            <Col xs={16}>
                                <Checkbox checked={Boolean(form.IsEditor)} onChange={(e) => setCheckboxField("IsEditor")(e.target.checked)} />
                            </Col>
                        </Row>
                    </Col>
                    <Col xs={24} md={12}>
                        <Row className="align-items-center" gutter={[8, 8]}>
                            <Col xs={8}>
                                <span className={labelCls}>Người ký nháy QĐXB</span>
                            </Col>
                            <Col xs={16}>
                                <Checkbox
                                    checked={Boolean(form.KyNhayQDXB)}
                                    onChange={(e) => setCheckboxField("KyNhayQDXB")(e.target.checked)}
                                />
                            </Col>
                        </Row>
                    </Col>
                </Row>

                <Row className="mb-2 align-items-center" gutter={[8, 8]}>
                    <Col xs={24} md={12}>
                        <Row className="align-items-center" gutter={[8, 8]}>
                            <Col xs={8}>
                                <span className={labelCls}>Mã số chứng chỉ</span>
                            </Col>
                            <Col xs={16}>
                                <Input value={form.MaSoChungChi ?? ""} onChange={setTextField("MaSoChungChi")} />
                            </Col>
                        </Row>
                    </Col>
                    <Col xs={24} md={12}>
                        <Row className="align-items-center" gutter={[8, 8]}>
                            <Col xs={8}>
                                <span className={labelCls}>Ngày cấp</span>
                            </Col>
                            <Col xs={16}>
                                <DatePicker
                                    style={{ width: "100%" }}
                                    placeholder="Chọn ngày cấp"
                                    value={convertValueToDayjs(form.NgayCap)}
                                    onChange={(date: Dayjs | null | undefined) =>
                                        setField("NgayCap", date ? new Date(date.toISOString()) : undefined)
                                    }
                                    format="DD/MM/YYYY"
                                />
                            </Col>
                        </Row>
                    </Col>
                </Row>

                <Row className="mb-2 align-items-center" gutter={[8, 8]}>
                    <Col xs={24} md={4}>
                        <span className={labelCls}>Nơi cấp</span>
                    </Col>
                    <Col xs={24} md={20}>
                        <Input value={form.NoiCap ?? ""} onChange={setTextField("NoiCap")} />
                    </Col>
                </Row>

                <Row className="mb-2 align-items-center" gutter={[8, 8]}>
                    <Col xs={24} md={4}>
                        <span className={labelCls}>Chức danh biên tập</span>
                    </Col>
                    <Col xs={24} md={20}>
                        <Input value={form.ChucDanhBienTap ?? ""} onChange={setTextField("ChucDanhBienTap")} />
                    </Col>
                </Row>

                <Row className="mb-2 align-items-center" gutter={[8, 8]}>
                    <Col xs={24} md={4}>
                        <span className={labelCls}>Đơn vị</span>
                    </Col>
                    <Col xs={24} md={20}>
                        <Input
                            readOnly
                            placeholder="Chọn đơn vị"
                            value={form.ID_DonVi ? listDonvi.find((d) => d.id === form.ID_DonVi)?.TenDonVi ?? "" : ""}
                            onClick={onShowModalChooseDonvi}
                            style={{ cursor: "pointer" }}
                        />
                    </Col>
                </Row>

                <Row className="mb-2 align-items-center" gutter={[8, 8]}>
                    <Col xs={24} md={12}>
                        <Row className="align-items-center" gutter={[8, 8]}>
                            <Col xs={12}>
                                <span className={labelCls}>Số bản ghi</span>
                            </Col>
                            <Col xs={12}>
                                <Input value={form.SoLuongBanGhi ?? ""} onChange={setTextField("SoLuongBanGhi")} />
                            </Col>
                        </Row>
                    </Col>
                </Row>
            </div>
            <ModalTreeDonvi show={showModalChooseDonvi} onHide={onHideModalChooseDonvi} listDonvi={listDonvi} handlerChooseDonvi={handlerChooseDonvi} usingselectChoose={true} />
        </React.Fragment>
    );
});
