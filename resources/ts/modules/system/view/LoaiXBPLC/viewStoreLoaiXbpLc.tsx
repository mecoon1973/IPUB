import {
    mountReactComponentOnReady,
    readRootDataProps,
} from "../../../core/utils/helpers";
import React, { useMemo, useState } from "react";
import { useCallback } from "react";
import { Col, Form, Input, InputNumber, Row, Select, Table, type TableProps } from "antd";
import { ComponentTitleStore } from "../../../page/component/componentTitleStore";
import type DonviLC from "../../type/DonviLC";
import type LoaiXbpLc from "../../type/LoaiXbpLc";
import { LoaiXbpLcApi } from "../../api/LoaiXbpLcApi";
import { LabelReq } from "../../../page/component/componentLable";


function emptyFormLoaiXbpLcState(): Partial<LoaiXbpLc> {
    return {
        id: 0,
        TenLoai: "",
        IsDeleted: false,
        InUsed: false,
        DaGui: false,
    };
}

interface LoaiXbpLcFormFieldsProps {
    form: Partial<LoaiXbpLc>;
    setField: (key: keyof LoaiXbpLc, value: LoaiXbpLc[keyof LoaiXbpLc]) => void;
}

const LoaiXbpLcFormFields = React.memo((props: LoaiXbpLcFormFieldsProps) => {
    const { form, setField } = props;
    return (
        <React.Fragment>
            <Form.Item label={<LabelReq>Tên loại xuất bản phẩm</LabelReq>}>
                <Input value={form.TenLoai ?? ""} onChange={(e) => setField("TenLoai", e.target.value)} />
            </Form.Item>
        </React.Fragment>
    );
});

interface TableDonviLCProps {
    listDonviLC: DonviLC[];
}

const TableDonviLC = React.memo((props: TableDonviLCProps) => {
    const { listDonviLC } = props;
    const columns: TableProps<DonviLC>["columns"] = useMemo(() => [
        { title: "STT", key: "stt", width: 72, render: (_v, _r, i) => i + 1 },
        { title: "Tên đối tượng", dataIndex: "Ten", key: "name" },
        { title: "Số Lượng",  key: "count", render : (_, record: DonviLC) => (
            <InputNumber
                value={0}
                className="w-100"
                onChange={(v) => {

                }}
                min={0}
                type="number"
            />
        ) },
    ], []);
    return <Table<DonviLC> rowKey="id" columns={columns} dataSource={listDonviLC} pagination={false} size="small" />;
});

interface ViewStoreLoaiXbpLcPageProps {
    loaiXbpLc?: LoaiXbpLc | null;
    listDonviLC: DonviLC[];
}

export const ViewStoreLoaiXbpLc = React.memo((props: ViewStoreLoaiXbpLcPageProps) => {
    const { loaiXbpLc, listDonviLC } = props;
    const [form, setForm] = useState<Partial<LoaiXbpLc>>(() => {
        if (loaiXbpLc) {
            return loaiXbpLc;
        }
        return { ...emptyFormLoaiXbpLcState() };
    });
    const [submitting, setSubmitting] = useState(false);

    const handleSubmit = useCallback(() => {
        const mapKeysRequired = {
            TenLoai: "Tên loại xuất bản lưu chiểu",
        };

        const messageRequired = Object.keys(mapKeysRequired)
            .map((key) =>
                !form[key as keyof Partial<LoaiXbpLc>] ? mapKeysRequired[key as keyof typeof mapKeysRequired] : "",
            )
            .filter(Boolean)
            .join(", ");
        if (messageRequired) {
            window._toastbox(`Vui lòng nhập đầy đủ thông tin: ${messageRequired}`, "error");
            return;
        }

        setSubmitting(true);

        LoaiXbpLcApi.upsert(form)
            .then((res: LoaiXbpLc | null) => {
                if (res) {
                    window._toastbox("Cập nhật loại xuất bản lưu chiểu thành công", "success");
                    setForm((prev: Partial<LoaiXbpLc>) => ({ ...prev, ...res }));
                }
            })
            .finally(() => {
                setSubmitting(false);
            });
    }, [form, setForm]);

    const setField = useCallback(<K extends keyof LoaiXbpLc>(key: K, value: LoaiXbpLc[K]) => {
        setForm((prev: Partial<LoaiXbpLc>) => ({ ...prev, [key]: value }));
    }, []);

    return (
        <div className="px-2">
            <ComponentTitleStore
                title={loaiXbpLc ? "Cập nhật loại xuất bản lưu chiểu" : "Thêm mới loại xuất bản lưu chiểu"}
                callbackSubmit={handleSubmit}
                disabledSubmit={submitting}
            />
            <Row gutter={12}>
                <Col span={16}>
                    <LoaiXbpLcFormFields form={form} setField={setField}  />
                </Col>
            </Row>
            <TableDonviLC listDonviLC={listDonviLC} />
        </div>
    );
});

const ROOT_ID = "root-store-loai-xbp-lc";
const bladeProps: ViewStoreLoaiXbpLcPageProps = {
    listDonviLC: [],
    loaiXbpLc: null,
    ...readRootDataProps<ViewStoreLoaiXbpLcPageProps>(ROOT_ID),
};
mountReactComponentOnReady(ROOT_ID, <ViewStoreLoaiXbpLc {...bladeProps} />);
