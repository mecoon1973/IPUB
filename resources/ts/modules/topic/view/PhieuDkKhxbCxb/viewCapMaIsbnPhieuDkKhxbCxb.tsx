import React, { useCallback, useMemo, useState } from "react";
import { Button, Flex, Form, Input, Table, Typography, type TableProps } from "antd";
import { ReloadOutlined, SaveOutlined } from "@ant-design/icons";
import {
    mountReactComponentOnReady,
    readRootDataProps,
} from "../../../core/utils/helpers";
import { formatDateToString } from "../../../core/utils/helpersDayjs";
import { PhieuDkKhxbCxbApi } from "../../api/PhieuDkKhxbCxbApi";
import type { User } from "../../../user/type";
import type { PhieuDkDetai, PhieuDkKhxbCxb } from "../../type";

interface ViewCapMaIsbnPhieuDkKhxbCxbProps {
    listUsers: User[];
    phieuDkKhxbCxb: PhieuDkKhxbCxb | null;
    listDeTai?: PhieuDkDetai[];
}

export const ViewCapMaIsbnPhieuDkKhxbCxb = React.memo((props: ViewCapMaIsbnPhieuDkKhxbCxbProps) => {
    const { listUsers, phieuDkKhxbCxb, listDeTai: initialListDeTai = [] } = props;

    const [isbnMap, setIsbnMap] = useState<Record<number, string>>(() => {
        const map: Record<number, string> = {};
        initialListDeTai.forEach((item) => {
            map[item.id] = item.ISBNCode ?? "";
        });
        return map;
    });
    const [submitting, setSubmitting] = useState(false);

    const mapUserName = useMemo(() => {
        const map = new Map<number, string>();
        listUsers.forEach((user) => {
            map.set(user.id, user.HoTen || user.UserName || String(user.id));
        });
        return map;
    }, [listUsers]);

    const setIsbn = useCallback((id: number, value: string) => {
        setIsbnMap((prev) => ({ ...prev, [id]: value }));
    }, []);

    const handleReset = useCallback(() => {
        const map: Record<number, string> = {};
        initialListDeTai.forEach((item) => {
            map[item.id] = item.ISBNCode ?? "";
        });
        setIsbnMap(map);
    }, [initialListDeTai]);

    const handleSave = useCallback(async () => {
        if (!phieuDkKhxbCxb?.id) {
            window._toastbox("Không xác định được phiếu trình CXB", "danger");
            return;
        }

        setSubmitting(true);
        const res = await PhieuDkKhxbCxbApi.capMaIsbn({
            idPhieu: phieuDkKhxbCxb.id,
            listIsbn: initialListDeTai.map((item) => ({
                id: item.id,
                ISBNCode: isbnMap[item.id] ?? "",
            })),
        });
        setSubmitting(false);

        if (!res) {
            return;
        }

        window._toastbox("Cấp mã ISBN thành công", "success");
    }, [initialListDeTai, isbnMap, phieuDkKhxbCxb]);

    const columns: TableProps<PhieuDkDetai>["columns"] = [
        { title: "STT", key: "stt", width: 56, render: (_v, _r, i) => i + 1 },
        { title: "Mã số", dataIndex: "MaSo", key: "MaSo", width: 140 },
        { title: "Tên đề tài", dataIndex: "TenDeTai", key: "TenDeTai" },
        { title: "Tác giả", dataIndex: "TacGia", key: "TacGia", width: 160 },
        { title: "Biên tập viên", dataIndex: "BienTapVien", key: "BienTapVien", width: 140 },
        { title: "Mã số CXB", dataIndex: "MaSoCXB", key: "MaSoCXB", width: 200 },
        {
            title: "Mã số ISBN",
            key: "ISBNCode",
            width: 180,
            render: (_, record) => (
                <Input
                    value={isbnMap[record.id] ?? ""}
                    onChange={(e) => setIsbn(record.id, e.target.value)}
                    placeholder="Nhập mã ISBN"
                />
            ),
        },
    ];

    return (
        <div className="px-2 py-2">
            <Typography.Title level={4} className="mb-2 border-bottom pb-2">
                Cấp mã ISBN
            </Typography.Title>

            <Flex gap={8} className="mb-3 border-bottom pb-2">
                <Button
                    type="text"
                    icon={<SaveOutlined />}
                    title="Lưu"
                    onClick={handleSave}
                    loading={submitting}
                />
                <Button
                    type="text"
                    icon={<ReloadOutlined />}
                    title="Làm mới"
                    onClick={handleReset}
                    disabled={submitting}
                />
            </Flex>

            <Form layout="vertical" className="mt-2">
                <div className="d-flex gap-3">
                    <Form.Item label="Mã số phiếu" style={{ width: 260 }}>
                        <Input value={phieuDkKhxbCxb?.MaSo ?? ""} readOnly disabled />
                    </Form.Item>
                    <Form.Item label="Ngày ĐK" style={{ width: 260 }}>
                        <Input value={formatDateToString(phieuDkKhxbCxb?.NgayDK)} readOnly disabled />
                    </Form.Item>
                </div>

                <Form.Item label="Tiêu đề">
                    <Input value={phieuDkKhxbCxb?.TieuDe ?? ""} readOnly disabled />
                </Form.Item>

                <Form.Item label="Nội dung">
                    <Input.TextArea rows={2} value={phieuDkKhxbCxb?.NoiDung ?? ""} readOnly disabled />
                </Form.Item>
            </Form>

            <Table<PhieuDkDetai>
                rowKey={(record, index) => String(record.id ?? index)}
                columns={columns}
                dataSource={initialListDeTai}
                pagination={false}
                size="small"
                className="text-sm mt-2"
                scroll={{ x: 1000 }}
            />
        </div>
    );
});

const ROOT_ID = "root-cap-ma-isbn-phieu-dk-khxb-cxb";
const bladeProps: ViewCapMaIsbnPhieuDkKhxbCxbProps = {
    listUsers: [] as User[],
    phieuDkKhxbCxb: null,
    listDeTai: [],
    ...readRootDataProps<ViewCapMaIsbnPhieuDkKhxbCxbProps>(ROOT_ID),
};
mountReactComponentOnReady(ROOT_ID, <ViewCapMaIsbnPhieuDkKhxbCxb {...bladeProps} />);
