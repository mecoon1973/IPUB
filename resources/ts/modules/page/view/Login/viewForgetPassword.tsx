import ReactDOM from "react-dom/client";
import React, { useState } from "react";
import { Button, Col, Form, Input, Row } from "antd";
import { mountReactComponentOnReady } from "../../../core/utils/helpers";
import { LoginApi } from "../../api/LoginApi";

/** Ảnh minh họa ERP */
const ERP_HERO_IMG = "/image/ERP-Solutions-Will-Help-You-In1.png";

export default function ForgetPasswordView() {
    const [email, setEmail] = useState("");
    const handleSubmit = (e: React.MouseEvent<HTMLButtonElement>) => {
        e.preventDefault();
        if (email === "") {
            window._toastbox("Vui lòng nhập email của bạn", "danger");
            return;
        }
        LoginApi.forgetPassword(email)
            .then(() => {
                window._toastbox("Email khôi phục mật khẩu đã được gửi", "success");
            })
            .catch(() => {
                window._toastbox("Email khôi phục mật khẩu không được gửi", "danger");
            });
    };
    return (
        <div
            className="d-flex align-items-center justify-content-center min-vh-100 bg-white py-4 py-md-5"
            style={{ fontFamily: "system-ui, -apple-system, 'Segoe UI', Roboto, Arial, sans-serif" }}
        >
            <div className="container-lg w-100 px-3">
                <Row className="align-items-center justify-content-center" gutter={[24, 24]}>
                    <Col xs={24} lg={14} className="d-flex justify-content-center">
                        <div
                            className="rounded w-100 d-flex align-items-center justify-content-center p-3 p-md-4"
                            style={{
                                backgroundColor: "#e9ecef",
                                maxWidth: 520,
                                minHeight: 320,
                            }}
                        >
                            <img
                                src={ERP_HERO_IMG}
                                alt="Hệ thống nhà xuất bản"
                                className="img-fluid"
                                style={{ maxHeight: 420, objectFit: "contain" }}
                            />
                        </div>
                    </Col>
                    <Col xs={24} lg={10}>
                        <div className="mx-auto" style={{ maxWidth: 400 }}>
                            <h1
                                className="text-center text-dark fw-normal mb-4"
                                style={{ fontSize: "1.25rem", lineHeight: 1.4 }}
                            >
                                KHÔI PHỤC MẬT KHẨU
                            </h1>
                            <Form layout="vertical">
                                <Form.Item>
                                    <Input
                                        autoComplete="email"
                                        placeholder="Nhập email của bạn"
                                        value={email}
                                        type="email"
                                        onChange={(e) => setEmail(e.target.value)}
                                        className="bg-white"
                                        style={{ borderColor: "#dee2e6" }}
                                    />
                                </Form.Item>
                                <div className="d-flex flex-wrap align-items-center justify-content-between gap-2">
                                    <Button type="primary" className="px-4" htmlType="submit" onClick={handleSubmit}>
                                        Lấy lại mật khẩu
                                    </Button>
                                    <a href="/dang-nhap" className="text-decoration-none small">
                                        Quay lại
                                    </a>
                                </div>
                            </Form>
                            <p className="text-center text-dark small mt-4 mb-0">
                                Số điện thoại hỗ trợ: <span className="fw-bold text-danger">096.129.7007</span>
                            </p>
                        </div>
                    </Col>
                </Row>
            </div>
        </div>
    );
}

mountReactComponentOnReady("root-form-forget-password", <ForgetPasswordView />);
