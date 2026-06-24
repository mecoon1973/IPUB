import { mountReactComponentOnReady, readRootDataProps } from "../../../core/utils/helpers";
import React, { useCallback, useState } from "react";
import type { DonVi, User } from "../../../user/type";
import { Col, Input, Row } from "antd";
import { useGetDonVi } from "../../../system/hooks/Donvi/useGetDonVi";
import { ModalTreeDonvi } from "../../../system/component/Donvi/ModalTreeDonvi";
import { UserApi } from "../../api/UserApi";
import { ComponentTitleStore } from "../../../page/component/componentTitleStore";

function emptyFormState(): {
    UserName: string;
    PassWord: string;
    ConfirmPassWord: string;
    ID_DonVi: number;
} {
    return {
        UserName: "",
        PassWord: "",
        ConfirmPassWord: "",
        ID_DonVi: 0,
    };
}

interface ViewCreateAccountProps {
    user?: User | null;
}

export const ViewCreateAccount = React.memo((props: ViewCreateAccountProps) => {
    const { user } = props;

    const [form, setForm] = useState<ReturnType<typeof emptyFormState>>(emptyFormState());
    const [submitting, setSubmitting] = useState(false);
    const { listDonvi } = useGetDonVi();
    const [showModalChooseDonvi, setShowModalChooseDonvi] = useState(false);
    const onShowModalChooseDonvi = useCallback(() => {
        setShowModalChooseDonvi(true);
    }, []);
    const onHideModalChooseDonvi = useCallback(() => {
        setShowModalChooseDonvi(false);
    }, []);

    const setTextField = <K extends keyof ReturnType<typeof emptyFormState>>(key: K) => (e: React.ChangeEvent<HTMLInputElement>) => {
        setForm((prev) => ({ ...prev, [key]: e.target.value.trim() }));
    };

    const handlerChooseDonvi = useCallback((donvi: DonVi) => {
        setForm((prev) => ({ ...prev, ID_DonVi: donvi.id }));
    }, []);

    const handleSubmit = useCallback(() => {
        if (form.UserName === "" || form.PassWord === "" || form.ConfirmPassWord === "" || form.ID_DonVi === 0) {
            window._toastbox("Vui lòng nhập đầy đủ thông tin", "error");
            return;
        }
        if (form.PassWord !== form.ConfirmPassWord) {
            window._toastbox("Mật khẩu và nhập lại mật khẩu không khớp", "error");
            return;
        }
        setSubmitting(true);
        UserApi.createAccount(form, user?.id ?? 0)
            .then((res: User | null) => {
                if (res) {
                    window._toastbox("Tạo tài khoản thành công", "success");
                }
            })
            .finally(() => {
                setSubmitting(false);
            });
    }, [form, user]);
    return (
        <div className="px-1">
            <ComponentTitleStore title="Tạo tài khoản" callbackSubmit={handleSubmit} disabledSubmit={submitting} />
            <Row gutter={[16, 16]}>
                <Col xs={24} lg={16}>
                    <div className="small">
                        <Row className="mb-2 align-items-center" gutter={[8, 8]}>
                            <Col xs={24} md={12}>
                                <Row className="align-items-center" gutter={[8, 8]}>
                                    <Col xs={8}>
                                        <span className="mb-0 d-block">Username</span>
                                    </Col>
                                    <Col xs={16}>
                                        <Input value={form.UserName} onChange={setTextField("UserName")} />
                                    </Col>
                                </Row>
                            </Col>
                        </Row>
                        <Row className="mb-2 align-items-center" gutter={[8, 8]}>
                            <Col xs={24} md={12}>
                                <Row className="align-items-center" gutter={[8, 8]}>
                                    <Col xs={8}>
                                        <span className="mb-0 d-block">Mật khẩu</span>
                                    </Col>
                                    <Col xs={16}>
                                        <Input.Password value={form.PassWord} onChange={setTextField("PassWord")} />
                                    </Col>
                                </Row>
                            </Col>
                        </Row>
                        <Row className="mb-2 align-items-center" gutter={[8, 8]}>
                            <Col xs={24} md={12}>
                                <Row className="align-items-center" gutter={[8, 8]}>
                                    <Col xs={8}>
                                        <span className="mb-0 d-block">Nhập lại mật khẩu</span>
                                    </Col>
                                    <Col xs={16}>
                                        <Input.Password value={form.ConfirmPassWord} onChange={setTextField("ConfirmPassWord")} />
                                    </Col>
                                </Row>
                            </Col>
                        </Row>

                        <Row className="mb-2 align-items-center" gutter={[8, 8]}>
                            <Col xs={24} md={4}>
                                <span className="mb-0 d-block">Sử dụng dữ liệu</span>
                            </Col>
                            <Col xs={24} md={20}>
                                <Input
                                    readOnly
                                    placeholder="Sử dụng dữ liệu"
                                    value={form.ID_DonVi ? listDonvi.find((d) => d.id === form.ID_DonVi)?.TenDonVi ?? "" : ""}
                                    onClick={onShowModalChooseDonvi}
                                    style={{ cursor: "pointer" }}
                                />
                            </Col>
                        </Row>
                    </div>
                </Col>
            </Row>
            <ModalTreeDonvi
                show={showModalChooseDonvi}
                onHide={onHideModalChooseDonvi}
                listDonvi={listDonvi}
                handlerChooseDonvi={handlerChooseDonvi}
                usingselectChoose={true}
            />
        </div>
    );
});

const ROOT_ID = "root-create-account";
const bladeProps = readRootDataProps<ViewCreateAccountProps>(ROOT_ID) ?? {};
mountReactComponentOnReady(ROOT_ID, <ViewCreateAccount {...bladeProps} />);
