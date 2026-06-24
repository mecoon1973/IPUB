import {
    mountReactComponentOnReady,
    readRootDataProps,
} from "../../../core/utils/helpers";
import React, { useMemo, useState } from "react";
import type { DonviLC, LoaiXbpLc } from "../../type";
import { useCallback } from "react";
import { Col, Input, InputNumber, Row, Table, type TableProps } from "antd";
import { ComponentTitleStore } from "../../../page/component/componentTitleStore";
import { DonviLCApi } from "../../api/DonviLCApi";
import { LabelReq } from "../../../page/component/componentLable";

function emptyFormState(): Partial<DonviLC> {
    return {
        id: 0,
        Ten: "",
        ThuTu: 0,
        IsDeleted: false,
        InUsed: true,
        LoaiXbpLc: [],
    };
}

interface TableLoaiXbpLcProps {
    form : Partial<DonviLC>;
    listLoaiXbpLc: LoaiXbpLc[];
    setField: <K extends keyof DonviLC>(key: K, value: DonviLC[K]) => void;
}

const TableLoaiXbpLc = React.memo((props: TableLoaiXbpLcProps) => {
    const { listLoaiXbpLc, setField, form } = props;
    const columns: TableProps<LoaiXbpLc>["columns"] = useMemo(() => [
        { title: "STT", key: "stt", width: 72, render: (_v, _r, i) => i + 1 },
        { title: "Tên loại xuất bản lưu chiểu", dataIndex: "TenLoai", key: "TenLoai" },
        { title: "Số Lượng", key: "input", width: 100, render: (_v, record: LoaiXbpLc) => (
            <InputNumber
                value={form.LoaiXbpLc?.find((item) => item.ID_LOAI_XBP_LC === record.id)?.SoLuong ?? 0}
                onChange={(v) => {
                    setField("LoaiXbpLc", [...(form.LoaiXbpLc ?? []).filter((item) => item.ID_LOAI_XBP_LC !== record.id), {
                        ID_LOAI_XBP_LC: record.id,
                        SoLuong: v ?? 0,
                    }]);
                }}
                min={0}
                type="number"
            />
        )},
    ], [form, setField]);

    return (
        <Table<LoaiXbpLc>
            rowKey="id"
            dataSource={listLoaiXbpLc}
            columns={columns}
            pagination={false}
            size="small"
            bordered
            scroll={{ x: 1100 }}
        />
    );
});

interface DonviLCFormFieldsProps {
    form: Partial<DonviLC>;
    setField: <K extends keyof DonviLC>(key: K, value: DonviLC[K]) => void;
}

const DonviLCFormFields = React.memo((props: DonviLCFormFieldsProps) => {
    const { form, setField } = props;

    return (
        <div className="border-top border-bottom">
            <div className="row align-items-center py-2 px-2 border-bottom g-0">
                <div className="col-sm-4 col-md-3">
                    <LabelReq>Tên đối tượng nhận lưu chiểu</LabelReq>
                </div>
                <div className="col">
                    <Input
                        value={form.Ten ?? ""}
                        onChange={(e) => setField("Ten", e.target.value)}
                    />
                </div>
            </div>
            <div className="row align-items-center py-2 px-2 g-0">
                <div className="col-sm-4 col-md-3">
                    <LabelReq>Thứ tự</LabelReq>
                </div>
                <div className="col-sm-3 col-md-2">
                    <InputNumber
                        className="w-100"
                        type="number"
                        value={form.ThuTu ?? 0}
                        onChange={(v) => setField("ThuTu", Number(v) || 0)}
                    />
                </div>
            </div>
        </div>
    );
});

interface ViewStoreDonviLCPageProps {
    DonviLC?: DonviLC | null;
    listLoaiXbpLc: LoaiXbpLc[];
}

export const ViewStoreDonviLC = React.memo((props: ViewStoreDonviLCPageProps) => {
    const { DonviLC, listLoaiXbpLc } = props;
    console.log(props);
    const [form, setForm] = useState<Partial<DonviLC>>(() => {
        if (DonviLC) {
            return DonviLC;
        }
        return {
            ...emptyFormState(),
        };
    });
    const [submitting, setSubmitting] = useState(false);

    const setField = useCallback(<K extends keyof DonviLC>(key: K, value: DonviLC[K]) => {
        setForm((prev: Partial<DonviLC>) => ({ ...prev, [key]: value }));
    }, []);

    const handleSubmit = useCallback(() => {
        const mapKeysRequired: Record<string, string> = {
            Ten: "Tên đối tượng nhận lưu chiểu",
            ThuTu: "Thứ tự",
        };

        const messageRequired = Object.keys(mapKeysRequired)
            .map((key) => {
                const val = form[key as keyof DonviLC];
                if (key === "ThuTu") {
                    return val === null || val === undefined || val === ""
                        ? mapKeysRequired[key]
                        : "";
                }
                return !val ? mapKeysRequired[key] : "";
            })
            .filter(Boolean)
            .join(", ");

        if (messageRequired) {
            window._toastbox(`Vui lòng nhập đầy đủ thông tin: ${messageRequired}`, "error");
            return;
        }

        setSubmitting(true);

        DonviLCApi.upsert(form)
            .then((res: DonviLC | null) => {
                if (res) {
                    window._toastbox(
                        `${form.id ? "Cập nhật" : "Thêm mới"} đơn vị lưu chuyển thành công`,
                        "success",
                    );
                    setForm((prev: Partial<DonviLC>) => ({ ...prev, ...res }));
                }
            })
            .finally(() => {
                setSubmitting(false);
            });
    }, [form]);

    return (
        <div className="px-1">
            <ComponentTitleStore
                title={DonviLC ? "Cập nhật đơn vị lưu chuyển" : "Thêm mới đơn vị lưu chuyển"}
                callbackSubmit={handleSubmit}
                disabledSubmit={submitting}
            />
            <Row gutter={12}>
                <Col span={24}>
                    <DonviLCFormFields form={form} setField={setField} />
                </Col>
            </Row>
            <Row gutter={12}>
                <Col span={24}>
                    <TableLoaiXbpLc form={form} listLoaiXbpLc={listLoaiXbpLc} setField={setField} />
                </Col>
            </Row>
        </div>
    );
});

const ROOT_ID = "root-store-donvilc";
const bladeProps = {
    listLoaiXbpLc: [],
    DonviLC: null,
    ...readRootDataProps<ViewStoreDonviLCPageProps>(ROOT_ID),
};
mountReactComponentOnReady(ROOT_ID, <ViewStoreDonviLC {...bladeProps} />);
