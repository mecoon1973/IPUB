import { Button, Col, DatePicker, Form, Input, Modal, Row, Typography } from "antd";
import type { Dayjs } from "dayjs";
import dayjs from "dayjs";
import React, { useCallback, useEffect, useMemo } from "react";
import type { ToKhaiLuuChuyen } from "../../type";

interface ModalUpdatTimeCXBProps {
    toKhaiLuuChuyen: ToKhaiLuuChuyen;
    open: boolean;
    onCancel: () => void;
    /** Gọi khi bấm Lưu (có thể gọi API cập nhật) */
    onSave?: (payload: { ngayXacNhan: Date | null }) => void;
}

export const ModalUpdatTimeCXB = React.memo((props: ModalUpdatTimeCXBProps) => {
    const { toKhaiLuuChuyen, open, onCancel, onSave } = props;
    const [form] = Form.useForm<{ tongSoSach: string; ngayXacNhan: Dayjs | null }>();

    // cái này là liên kết 1-n với 
    const tongSoMacDinh = 6;

    useEffect(() => {
        if (!open) return;
        form.setFieldsValue({
            tongSoSach: tongSoMacDinh != null ? String(tongSoMacDinh) : "",
            ngayXacNhan: toKhaiLuuChuyen.NgayXacNhan && dayjs(toKhaiLuuChuyen.NgayXacNhan).isValid()
                ? dayjs(toKhaiLuuChuyen.NgayXacNhan)
                : null,
        });
    }, [open, toKhaiLuuChuyen, tongSoMacDinh, form]);

    const handleHuyNXN = useCallback(() => {
        form.setFieldValue("ngayXacNhan", null);
    }, [form]);

    const handleFinish = useCallback(
        (values: { tongSoSach: string; ngayXacNhan: Dayjs | null }) => {
            onSave?.({
                ngayXacNhan: values.ngayXacNhan ? values.ngayXacNhan.toDate() : null,
            });
            onCancel();
        },
        [onSave, onCancel],
    );

    return (
        <Modal
            open={open}
            onCancel={onCancel}
            width={520}
            destroyOnHidden
            styles={{
                header: {
                    background: "#f5f5f5",
                    marginBottom: 0,
                    paddingBottom: 12,
                    borderBottom: "1px solid #e8e8e8",
                },
                body: { paddingTop: 20, paddingBottom: 8 },
                footer: {
                    background: "#fafafa",
                    marginTop: 0,
                    paddingTop: 12,
                    borderTop: "1px solid #e8e8e8",
                },
            }}
            title={
                <Typography.Text strong style={{ fontSize: 13, letterSpacing: "0.02em", color: "#434343" }}>
                    CẬP NHẬT NGÀY XÁC NHẬN CỦA CỤC XUẤT BẢN
                </Typography.Text>
            }
            footer={[
                <Button key="save" onClick={() => form.submit()}>
                    Lưu
                </Button>,
                <Button key="cancel" onClick={onCancel}>
                    Hủy
                </Button>,
            ]}
            zIndex={1050}
        >
            <Form form={form} layout="vertical" onFinish={handleFinish}>
                <Row gutter={[16, 16]} align="middle">
                    <Col xs={24} sm={8} md={7} lg={6}>
                        <Form.Item label="Tổng số sách" name="tongSoSach" className="mb-0">
                            <Input
                                size="middle"
                                style={{ maxWidth: 120 }}
                                inputMode="numeric"
                            />
                        </Form.Item>
                    </Col>
                </Row>
                <Row gutter={[12, 8]} align="middle" className="mt-3">
                    <Col xs={24} sm={24} md={16} lg={15}>
                        <Form.Item label="Ngày xác nhận" name="ngayXacNhan" className="mb-0">
                            <DatePicker
                                className="w-100"
                                format="DD/MM/YYYY"
                                placeholder="__/__/____"
                                allowClear
                            />
                        </Form.Item>
                    </Col>
                    <Col xs={24} sm={24} md={8} lg={9} className="d-flex align-items-end">
                        <Form.Item label=" " className="mb-0 w-100">
                            <Button onClick={handleHuyNXN}>
                                Hủy NXN
                            </Button>
                        </Form.Item>
                    </Col>
                </Row>
            </Form>
        </Modal>
    );
});
