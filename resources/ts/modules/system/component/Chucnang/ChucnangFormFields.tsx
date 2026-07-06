import React, { useCallback, useState } from "react";
import { Checkbox, Col, Form, Input, Row, Select } from "antd";
import type { ChucNang } from "../../type/ChucNang";
import type { PhanHe } from "../../type/PhanHe";
import { ModalTreeChucnang } from "./ModalTreeChucnang";
import { LabelOpt, LabelReq } from "../../../page/component/componentLable";

interface ChucnangFormFieldsProps {
    form: Partial<ChucNang>;
    setField: <K extends keyof ChucNang>(key: K, value: ChucNang[K]) => void;
    listPhanhe: PhanHe[];
    listChucnang: ChucNang[];
}

export const ChucnangFormFields = React.memo((props: ChucnangFormFieldsProps) => {
    const { form, setField, listPhanhe, listChucnang } = props;
    const [showModalChooseChucnang, setShowModalChooseChucnang] = useState(false);
    const onShowModalChooseChucnang = useCallback(() => {
        setShowModalChooseChucnang(true);
    }, []);
    const onHideModalChooseChucnang = useCallback(() => {
        setShowModalChooseChucnang(false);
    }, []);
    const handlerChooseChucnang = useCallback(
        (chucnang: ChucNang) => {
            setField("ParentID", chucnang.id);
        },
        [setField],
    );

    return (
        <>
            <Form layout="vertical">
                <Row gutter={16}>
                    <Col span={12}>
                        <Form.Item label={<LabelReq>Mã chức năng</LabelReq>}>
                            <Input style={{ maxWidth: "50%" }} value={form.Code ?? ""} onChange={(e) => setField("Code", e.target.value)} />
                        </Form.Item>
                    </Col>
                    <Col span={12}>
                        <Form.Item label={<LabelReq>Mã trên url</LabelReq>}>
                            <Input
                                style={{ maxWidth: "50%" }}
                                value={form.FunctionCode ?? ""}
                                onChange={(e) => setField("FunctionCode", e.target.value)}
                            />
                        </Form.Item>
                    </Col>
                </Row>

                <Form.Item label={<LabelReq>Tên chức năng</LabelReq>}>
                    <Input style={{ maxWidth: "80%" }} value={form.Title ?? ""} onChange={(e) => setField("Title", e.target.value)} />
                </Form.Item>

                <Form.Item label={<LabelOpt>Chức năng cha</LabelOpt>}>
                    <Input
                        style={{ maxWidth: "80%" }}
                        value={form.ParentID ? listChucnang.find((c) => c.id === form.ParentID)?.Title ?? "" : ""}
                        readOnly
                        className="bg-light"
                        onClick={onShowModalChooseChucnang}
                    />
                </Form.Item>

                <Form.Item label={<LabelOpt>Đường dẫn</LabelOpt>}>
                    <Input style={{ maxWidth: "80%" }} value={form.Href ?? ""} onChange={(e) => setField("Href", e.target.value)} />
                </Form.Item>

                <Row gutter={16}>
                    <Col span={12}>
                        <Form.Item label={<LabelOpt>Phân hệ</LabelOpt>}>
                            <Select<number | null, { value: number; label: string }>
                                style={{ maxWidth: "50%" }}
                                value={form.PhanHeID === undefined ? null : form.PhanHeID}
                                options={listPhanhe?.map((p) => ({ value: p.id, label: p.TenPhanHe }))}
                                onChange={(v) => setField("PhanHeID", v ?? 0)}
                                allowClear
                            />
                        </Form.Item>
                    </Col>
                    <Col span={12}>
                        <Form.Item label={<LabelOpt>Có links đầy đủ</LabelOpt>} valuePropName="checked">
                            <Checkbox checked={!!form.isLinkFull} onChange={(e) => setField("isLinkFull", e.target.checked)} />
                        </Form.Item>
                    </Col>
                </Row>

                <Row gutter={16}>
                    <Col span={12}>
                        <Form.Item label={<LabelOpt>Thứ tự</LabelOpt>}>
                            <Input
                                type="number"
                                style={{ maxWidth: "50%" }}
                                value={form.ThuTu ?? 0}
                                onChange={(e) => {
                                    if (Number(e.target.value) < 0) return;
                                    setField("ThuTu", e.target.value === "" ? 0 : Number(e.target.value));
                                }}
                            />
                        </Form.Item>
                    </Col>
                    <Col span={12}>
                        <Form.Item label={<LabelOpt>On menu</LabelOpt>} valuePropName="checked">
                            <Checkbox checked={!!form.OnMenu} onChange={(e) => setField("OnMenu", e.target.checked)} />
                        </Form.Item>
                    </Col>
                </Row>

                <Form.Item label={<LabelOpt>Mô tả</LabelOpt>}>
                    <Input.TextArea
                        rows={4}
                        style={{ maxWidth: "80%" }}
                        value={form.Description ?? ""}
                        onChange={(e) => setField("Description", e.target.value)}
                    />
                </Form.Item>

                <Form.Item label={<LabelOpt>Mã chức năng con</LabelOpt>}>
                    <Input.TextArea
                        rows={4}
                        style={{ maxWidth: "80%" }}
                        value={form.ChildFunctionCode ?? ""}
                        onChange={(e) => setField("ChildFunctionCode", e.target.value)}
                        placeholder="Thêm ở chức năng cha cao nhất để được focus"
                    />
                </Form.Item>

                <Form.Item label={<LabelOpt>Hiển thị tiêu đề</LabelOpt>}>
                    <Input style={{ maxWidth: "80%" }} value={form.Crumb ?? ""} readOnly />
                </Form.Item>
            </Form>
            <ModalTreeChucnang
                show={showModalChooseChucnang}
                onHide={onHideModalChooseChucnang}
                listChucnang={listChucnang}
                handlerChooseChucnang={handlerChooseChucnang}
                usingselectChoose={true}
            />
        </>
    );
});
