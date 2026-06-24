import {
    mountReactComponentOnReady,
    readRootDataProps,
} from "../../../core/utils/helpers";
import React, { useCallback, useEffect, useMemo, useState } from "react";
import { AppstoreOutlined, DeleteOutlined } from "@ant-design/icons";
import { Button, Card, Col, Form, Input, Radio, Row, Table, type TableProps } from "antd";
import { ComponentTitleStore } from "../../../page/component/componentTitleStore";
import { LabelReq } from "../../../page/component/componentLable";
import { TYPE_DOC_RA_SOAT_OPTIONS, type DSDocRaSoat, type TypeDSDocRaSoat, TYPE_IS_SACH_OPTIONS } from "../../type";
import { DSDocRaSoatApi } from "../../api/DSDocRaSoatApi";
import { ModalChooseDeTaiComponent } from "../../component/DSDocRaSoat/ModalChooseDeTaiComponent";
import type { DonVi } from "../../../user/type";
import type { Mangsach } from "../../../system/type";
import { useDataViewStore } from "../../../system/store/useDataViewStore";
import type { PhieuDkDetai } from "../../../topic/type";



function emptyFormState(): Partial<DSDocRaSoat> {
    return {
        Title: "",
        Type: "KIEM_DINH",
        IsSach: false,
    };
}

function getMissingRequiredFields(form: Partial<DSDocRaSoat>): string[] {
    const missing: string[] = [];
    if (!form.Title?.trim()) {
        missing.push("Tiêu đề");
    }
    if (!form.Type) {
        missing.push("Loại đọc duyệt");
    }
    if (form.IsSach === undefined || form.IsSach === null) {
        missing.push("Loại dữ liệu");
    }
    return missing;
}

interface TableListDeTaiProps {
    listDeTai: PhieuDkDetai[];
}

const TableListDeTai = React.memo((props: TableListDeTaiProps) => {
    const { listDeTai } = props;

    const columns: TableProps<PhieuDkDetai>["columns"] = useMemo(() => [
        { title: "STT", key: "stt", width: 56, render: (_v, _r, i) => i + 1 },
        { title: "Mã số", dataIndex: "MaSo", key: "MaSo" },
        { title: "Tên đề tài", dataIndex: "TenDeTai", key: "TenDeTai" },
        { title: "Tác giả", dataIndex: "TacGia", key: "TacGia" },
        { title: "Biên tập viên", dataIndex: "BienTapVien", key: "BienTapVien" },
        { title: "", key: "actDelete", render: (_v, r) => <Button type="link" icon={<DeleteOutlined />} onClick={() => {}} /> },
    ], []);

    return <Table<PhieuDkDetai>
        dataSource={listDeTai}
        columns={columns}
        rowKey="id"
    />
});

interface DSDocRaSoatFormFieldsProps {
    form: Partial<DSDocRaSoat>;
    setField: <K extends keyof DSDocRaSoat>(key: K, value: DSDocRaSoat[K]) => void;
    setShowModalChonDeTai: (showModalChonDeTai: boolean) => void;
}

const DSDocRaSoatFormFields = React.memo((props: DSDocRaSoatFormFieldsProps) => {
    const { form, setField, setShowModalChonDeTai } = props;

    const handleChonTuPhanMem = useCallback(() => {
        setShowModalChonDeTai(true);
    }, [setShowModalChonDeTai]);

    return (
        <Card size="small" className="shadow-sm">
            <Form layout="vertical" className="mb-0">
                <Row gutter={[12, 8]} align="bottom" wrap={false} style={{ minWidth: 720 }}>
                    <Col flex="1 1" style={{ minWidth: 160 }}>
                        <Form.Item className="mb-0" label={<LabelReq>Tiêu đề</LabelReq>}>
                            <Input
                                allowClear
                                placeholder="Nhập tiêu đề"
                                value={form.Title ?? ""}
                                onChange={(e) => setField("Title", e.target.value)}
                            />
                        </Form.Item>
                    </Col>

                    <Col flex="0 0 auto">
                        <Form.Item className="mb-0" label={<LabelReq>Loại đọc duyệt</LabelReq>}>
                            <Radio.Group
                                className="d-flex flex-nowrap gap-1"
                                optionType="button"
                                buttonStyle="solid"
                                value={form.Type}
                                options={TYPE_DOC_RA_SOAT_OPTIONS}
                                onChange={(e) => setField("Type", e.target.value as TypeDSDocRaSoat)}
                            />
                        </Form.Item>
                    </Col>
                    <Col flex="0 0 auto">
                        <Form.Item className="mb-0" label={<LabelReq>Loại dữ liệu</LabelReq>}>
                            <Radio.Group
                                className="d-flex flex-nowrap gap-1"
                                optionType="button"
                                buttonStyle="solid"
                                value={form.IsSach ?? false}
                                options={TYPE_IS_SACH_OPTIONS}
                                onChange={(e) => setField("IsSach", e.target.value as boolean)}
                            />
                        </Form.Item>
                    </Col>
                    <Col flex="0 0 auto">
                        <Form.Item className="mb-0" label=" ">
                            <Button
                                type="default"
                                icon={<AppstoreOutlined />}
                                onClick={handleChonTuPhanMem}
                            >
                                Chọn từ phần mềm
                            </Button>
                        </Form.Item>
                    </Col>
                </Row>
            </Form>
        </Card>
    );
});

interface ViewStoreDSDocRaSoatPageProps {
    DSDocRaSoat?: DSDocRaSoat | null;
    listDonvi: DonVi[];
    listMangsach: Mangsach[];
}

export const ViewStoreDSDocRaSoat = React.memo((props: ViewStoreDSDocRaSoatPageProps) => {
    const { DSDocRaSoat, listDonvi, listMangsach } = props;
    const setDataView = useDataViewStore((state) => state.setData);
    const [form, setForm] = useState<Partial<DSDocRaSoat>>(() => {
        if (DSDocRaSoat) {
            return DSDocRaSoat;
        }
        return { ...emptyFormState() };
    });
    const [submitting, setSubmitting] = useState(false);
    const [showModalChonDeTai, setShowModalChonDeTai] = useState(false);
    const setField = useCallback(<K extends keyof DSDocRaSoat>(key: K, value: DSDocRaSoat[K]) => {
        setForm((prev) => ({ ...prev, [key]: value }));
    }, []);

    useEffect(() => {
        setDataView({ listDonvi, listMangsach });
    }, [listDonvi, listMangsach, setDataView]);

    const handleSubmit = useCallback(() => {
        const missing = getMissingRequiredFields(form);
        if (missing.length > 0) {
            window._toastbox(`Vui lòng nhập đầy đủ thông tin: ${missing.join(", ")}`, "error");
            return;
        }

        setSubmitting(true);
        DSDocRaSoatApi.upsert(form)
            .then((res: DSDocRaSoat | null) => {
                if (res) {
                    window._toastbox("Cập nhật đề xuất ra soát thành công", "success");
                    setForm((prev) => ({ ...prev, ...res }));
                }
            })
            .finally(() => {
                setSubmitting(false);
            });
    }, [form]);

    return (
        <div className="px-2">
            <ComponentTitleStore
                title={DSDocRaSoat ? "Cập nhật đề xuất ra soát" : "Thêm mới đề xuất ra soát"}
                callbackSubmit={handleSubmit}
                disabledSubmit={submitting}
            />
            <DSDocRaSoatFormFields form={form} setField={setField} setShowModalChonDeTai={setShowModalChonDeTai} />
            <TableListDeTai listDeTai={[]} />
            <ModalChooseDeTaiComponent showModalChonDeTai={showModalChonDeTai} setShowModalChonDeTai={setShowModalChonDeTai} />
        </div>
    );
});

const ROOT_ID = "root-store-ds-doc-ra-soat";
const bladeProps: ViewStoreDSDocRaSoatPageProps = {
    DSDocRaSoat: null,
    listDonvi: [],
    listMangsach: [],
    ...readRootDataProps<ViewStoreDSDocRaSoatPageProps>(ROOT_ID),
};
mountReactComponentOnReady(ROOT_ID, <ViewStoreDSDocRaSoat {...bladeProps} />);
