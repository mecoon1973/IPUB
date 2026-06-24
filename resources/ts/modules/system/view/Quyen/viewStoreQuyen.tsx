import {
    mountReactComponentOnReady,
    readRootDataProps,
} from "../../../core/utils/helpers";
import React, { useState } from "react";
import { useCallback } from "react";
import { Col, Form, Input, Row } from "antd";
import { ComponentTitleStore } from "../../../page/component/componentTitleStore";
import type { Quyen } from "../../type";
import { QuyenApi } from "../../api/QuyenApi";
import { useGetQuyen } from "../../hooks/Quyen/useGetQuyen";
import { ModalTree } from "../../../page/component/componentModalTree";
import { LabelOpt, LabelReq } from "../../../page/component/componentLable";

function emptyFormState(): Partial<Quyen> {
    return {
        id: 0,
        MaQuyen: "",
        TenQuyen: "",
        ThuTu: 0,
        ParentID: 0,
    };
}


interface ViewStoreQuyenProps {
    quyen?: Quyen | null;
    parentId?: number;
}

export const ViewStoreQuyen = React.memo((props: ViewStoreQuyenProps) => {
    const { quyen, parentId } = props;
    const [form, setForm] = useState<Partial<Quyen>>(() => {
        let dataForm = emptyFormState();
        if (parentId) {
            dataForm.ParentID = parentId;
        }
        if (quyen && Object.keys(quyen).length > 0) {
            dataForm = {
                ...dataForm,
                ...quyen,
            };
        }
        return dataForm;
    });
    const [submitting, setSubmitting] = useState(false);
    const { listQuyen } = useGetQuyen();
    const [showModalChooseQuyen, setShowModalChooseQuyen] = useState(false);

    const setField = useCallback(<K extends keyof Quyen>(key: K, value: Quyen[K]) => {
        setForm((prev: Partial<Quyen>) => ({ ...prev, [key]: value }));
    }, []);

    const onShowModalChooseQuyen = useCallback(() => {
        setShowModalChooseQuyen(true);
    }, []);
    const onHideModalChooseQuyen = useCallback(() => {
        setShowModalChooseQuyen(false);
    }, []);

    const handlerChooseQuyen = useCallback((quyen: Quyen) => {
        setField("ParentID", quyen.id);
    }, [setField]);


    const handleSubmit = useCallback(() => {

        const mapKeysRequired = {
            "MaQuyen": "Mã quyền",
            "TenQuyen": "Tên quyền",
        };

        const messageRequired = Object.keys(mapKeysRequired).map(key => !form[key as keyof Quyen] ? mapKeysRequired[key as keyof typeof mapKeysRequired] : "").filter(Boolean).join(", ");
        if (messageRequired) {
            window._toastbox(`Vui lòng nhập đầy đủ thông tin: ${messageRequired}`, "error");
            return;
        }

        setSubmitting(true);

        QuyenApi.upsert(form).then((res: Quyen | null) => {
            if (res) {
                window._toastbox(`${form.id ? "Cập nhật" : "Thêm mới"} quyền ${form.MaQuyen ?? ""} thành công`, "success");
                setForm( (prev: Partial<Quyen>) => ({ ...prev, ...res}));
            }
        }).finally(() => {
            setSubmitting(false);
        });
    },[form, setForm]);



    return (
        <div className="px-2">
            <ComponentTitleStore title={quyen ? "Cập nhật quyền" : "Thêm mới quyền"} callbackSubmit={handleSubmit} disabledSubmit={submitting} />
            <Row gutter={12}>
                <Col span={16}>
                    <Form layout="vertical">
                        <Row gutter={8}>
                            <Col span={24}>
                                <Form.Item label={<LabelReq>Mã quyền</LabelReq>}>
                                <Input
                                    value={form.MaQuyen ?? ""}
                                    onChange={(e) => setField("MaQuyen", e.target.value)}
                                />
                                </Form.Item>
                            </Col>
                            <Col span={24}>
                                <Form.Item label={<LabelReq>Tên quyền</LabelReq>}>
                                <Input
                                    value={form.TenQuyen ?? ""}
                                    onChange={(e) => setField("TenQuyen", e.target.value)}
                                />
                                </Form.Item>
                            </Col>
                            <Col span={24}>
                                <Form.Item label={<LabelOpt>Quyền cha</LabelOpt>}>
                                <Input
                                    readOnly
                                    value={form.ParentID ? listQuyen.find((q) => q.id === form.ParentID)?.TenQuyen ?? "" : ""}
                                    placeholder="Chọn quyền cha"
                                    onClick={onShowModalChooseQuyen}
                                />
                                </Form.Item>
                            </Col>
                            <Col span={24}>
                                <Form.Item label={<LabelOpt>Thứ tự</LabelOpt>}>
                                <Input
                                    type="number"
                                    value={form.ThuTu ?? 0}
                                    onChange={(e) => {
                                        if(Number(e.target.value) < 0) {
                                            return;
                                        }
                                        setField("ThuTu", e.target.value === "" ? 0 : Number(e.target.value));
                                    }}
                                />
                                </Form.Item>
                            </Col>
                        </Row>
                    </Form>
                    <ModalTree
                        title="Danh sách quyền"
                        show={showModalChooseQuyen}
                        onHide={onHideModalChooseQuyen}
                        listData={listQuyen}
                        handlerChoose={(data: Quyen) => handlerChooseQuyen(data)}
                        getLabel={(data: Quyen) => data.TenQuyen + " (" + data.MaQuyen + ")"}
                        usingselectChoose={true}
                        size="lg"
                        selectedIdDefault={form.ParentID ?? 0}
                    />

                </Col>
            </Row>
        </div>
    );
});

const ROOT_ID = "root-store-quyen";
const bladeProps = readRootDataProps<ViewStoreQuyenProps>(ROOT_ID) ?? {};
mountReactComponentOnReady(ROOT_ID, <ViewStoreQuyen {...bladeProps} />);
