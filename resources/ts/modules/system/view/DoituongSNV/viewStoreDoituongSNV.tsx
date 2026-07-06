import {
    mountReactComponentOnReady,
    readRootDataProps,
} from "../../../core/utils/helpers";
import React, { useMemo, useState } from "react";
import { useCallback } from "react";
import { Form, Input, InputNumber, Table, type TableProps } from "antd";
import { ComponentTitleStore } from "../../../page/component/componentTitleStore";
import type { DoituongSNV } from "../../type/DoiTuongSNV";
import type { LoaiSnv } from "../../type/LoaiSnv";
import { LabelReq } from "../../../page/component/componentLable";
import { DoituongSNVApi } from "../../api/DoituongSNVApi";


function emptyFormState(): Partial<DoituongSNV> {
    return {
        id: 0,
        TenDonVi: "",
        ThuTu: 0,
        listLoaiSNV: [],
    };
}

interface DoituongSNVFormFieldsProps {
    form: Partial<DoituongSNV>;
    setField: (key: keyof DoituongSNV, value: DoituongSNV[keyof DoituongSNV]) => void;
}

const DoituongSNVFormFields = React.memo((props: DoituongSNVFormFieldsProps) => {
    const { form, setField } = props;
    return (
        <Form layout="vertical">
            <Form.Item label={<LabelReq>Tên đối tượng nhận sách nghiệp vụ</LabelReq>}>
                <Input value={form.TenDonVi ?? ""} onChange={(e) => setField("TenDonVi", e.target.value)} />
            </Form.Item>
            <Form.Item label={<LabelReq>Thứ tự</LabelReq>}>
                <Input type="number" min={0} value={form.ThuTu ?? 0} onChange={(e) => setField("ThuTu", Number(e.target.value) || 0)} />
            </Form.Item>

        </Form>
    );
});

interface TableSettingProps {
    listSetting: LoaiSnv[];
    form: Partial<DoituongSNV>;
    setField: <K extends keyof DoituongSNV>(key: K, value: DoituongSNV[K]) => void;
}

const TableSetting = React.memo((props: TableSettingProps) => {
    const { listSetting, form, setField } = props;
    const columns: TableProps<LoaiSnv>["columns"] = useMemo(() => [
        { title: "STT", key: "stt", width: 72, render: (_v, _r, i) => i + 1 },
        { title: "Tên đối tượng", dataIndex: "TenLoai", key: "TenLoai" },
        { title: "Số lượng", dataIndex: "", key: "soluong", render: (_v, record: LoaiSnv) => (
            <InputNumber
                value={form.listLoaiSNV?.find((item) => item.id === record.id)?.SoLuong ?? 0}
                onChange={(v) => {
                    const entry = { id: record.id, SoLuong: v ?? 0 };
                    const rest = (form.listLoaiSNV ?? []).filter((item) => item.id !== record.id);
                    setField("listLoaiSNV", [...rest, entry]);
                }}
                min={0}
                type="number"
            />
        )},
    ], [form.listLoaiSNV, setField]);

    return (
        <div>
            <Table dataSource={listSetting} columns={columns} />
        </div>
    );
});

interface ViewStoreDoituongSNVPageProps {
    doituongSNV?: DoituongSNV | null;
    listLoaiSNV: LoaiSnv[];
}

export const ViewStoreDoituongSNV = React.memo((props: ViewStoreDoituongSNVPageProps) => {
    const { doituongSNV, listLoaiSNV } = props;
    const [form, setForm] = useState<Partial<DoituongSNV>>(() => {
        if (doituongSNV) {
            return doituongSNV;
        }
        return { ...emptyFormState() };
    });
    const [submitting, setSubmitting] = useState(false);

    const handleSubmit = useCallback(() => {
        const mapKeysRequired = {
            TenDonVi: "Tên đối tượng nhận sách nghiệp vụ",
            ThuTu: "Thứ tự",
        };

        const messageRequired = Object.keys(mapKeysRequired)
            .map((key) =>
                !form[key as keyof Partial<DoituongSNV>] ? mapKeysRequired[key as keyof typeof mapKeysRequired] : "",
            )
            .filter(Boolean)
            .join(", ");
        if (messageRequired) {
            window._toastbox(`Vui lòng nhập đầy đủ thông tin: ${messageRequired}`, "error");
            return;
        }

        setSubmitting(true);

        DoituongSNVApi.upsert(form).then((res: DoituongSNV | null) => {
                if (res) {
                    window._toastbox("Cập nhật chức năng thành công", "success");
                    setForm((prev: Partial<DoituongSNV>) => ({ ...prev, ...res }));
                }
            })
            .finally(() => {
                setSubmitting(false);
            });
    }, [form, setForm]);

    const setField = useCallback(<K extends keyof DoituongSNV>(key: K, value: DoituongSNV[K]) => {
        setForm((prev: Partial<DoituongSNV>) => ({ ...prev, [key]: value }));
    }, []);

    return (
        <div className="px-2">
            <ComponentTitleStore
                title={doituongSNV ? "Sửa danh mục đối tượng nhận nhận sách nghiệp vụ" : "Thêm mới danh mục đối tượng nhận nhận sách nghiệp vụ"}
                callbackSubmit={handleSubmit}
                disabledSubmit={submitting}
            />
            <DoituongSNVFormFields form={form} setField={setField} />
            <TableSetting listSetting={listLoaiSNV} form={form} setField={setField} />
        </div>
    );
});

const ROOT_ID = "root-store-doituong-snv";
const bladeProps: ViewStoreDoituongSNVPageProps = {
    listLoaiSNV: [],
    doituongSNV: null,
    ...readRootDataProps<ViewStoreDoituongSNVPageProps>(ROOT_ID),
};
mountReactComponentOnReady(ROOT_ID, <ViewStoreDoituongSNV {...bladeProps} />);
