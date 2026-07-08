import React, { useCallback, useEffect, useMemo, useRef, useState } from "react";
import { Button, Input, Modal, Table } from "antd";
import type { ColumnsType } from "antd/es/table";
import SelectAntd from "../../../core/utils/SelectAntd";
import type { DonVi } from "../../../user/type";
import type { Mangsach } from "../../../system/type";
import { PhieuDkDetaiApi } from "../../api/PhieuDkDetaiApi";
import type { FilterPhieuDkDetai, PhieuDkDetai } from "../../type";
import { defaultFilterPhieuDkDetai } from "../../type";

interface ModalChooseDeTaiPhieuDkKhxbCxbProps {
    open: boolean;
    onClose: () => void;
    onConfirm: (items: PhieuDkDetai[]) => void;
    excludedIds: number[];
    listDonvi: DonVi[];
    listMangsach: Mangsach[];
}

export const ModalChooseDeTaiPhieuDkKhxbCxb = React.memo(({
    open,
    onClose,
    onConfirm,
    excludedIds,
    listDonvi,
    listMangsach,
}: ModalChooseDeTaiPhieuDkKhxbCxbProps) => {
    const [filter, setFilter] = useState<FilterPhieuDkDetai>({ ...defaultFilterPhieuDkDetai });
    const [listResult, setListResult] = useState<PhieuDkDetai[]>([]);
    const [selectedRowKeys, setSelectedRowKeys] = useState<React.Key[]>([]);
    const [isLoading, setIsLoading] = useState(false);
    const excludedSet = useMemo(() => new Set(excludedIds), [excludedIds]);

    useEffect(() => {
        if (!open) {
            setSelectedRowKeys([]);
            setListResult([]);
            setFilter({ ...defaultFilterPhieuDkDetai });
        }
    }, [open]);

    const handleSearch = useCallback(() => {
        setIsLoading(true);
        PhieuDkDetaiApi.getList({ ...filter, limit: 100 }).then((res) => {
            setListResult(res.filter((item) => !excludedSet.has(item.id)));
            setSelectedRowKeys([]);
        }).finally(() => setIsLoading(false));
    }, [filter, excludedSet]);

    const columns: ColumnsType<PhieuDkDetai> = [
        { title: "STT", key: "stt", width: 48, render: (_v, _r, i) => i + 1 },
        { title: "Mã số", dataIndex: "MaSo", key: "MaSo", width: 90 },
        { title: "Tên đề tài", dataIndex: "TenDeTai", key: "TenDeTai", width: 180 },
        { title: "Tác giả", dataIndex: "TacGia", key: "TacGia", width: 100 },
        {
            title: "Năm TB/XB",
            key: "NamTBXB",
            width: 95,
            render: (_, record) => record.NamXuatBan || record.NamTaiBan || "",
        },
    ];

    const handleConfirm = useCallback(() => {
        const selected = listResult.filter((item) => selectedRowKeys.includes(item.id));
        if (selected.length === 0) {
            window._toastbox("Vui lòng chọn ít nhất một đề tài", "warning");
            return;
        }
        onConfirm(selected);
        onClose();
    }, [listResult, onClose, onConfirm, selectedRowKeys]);

    return (
        <Modal
            open={open}
            onCancel={onClose}
            title="TÌM KIẾM ĐỀ TÀI"
            width="xl"
            footer={[
                <Button key="choose" type="primary" onClick={handleConfirm}>
                    Chọn
                </Button>,
                <Button key="close" onClick={onClose}>
                    Đóng
                </Button>,
            ]}
        >
            <div className="d-grid gap-2 mb-3" style={{ gridTemplateColumns: "repeat(4, minmax(0, 1fr))" }}>
                <div>
                    <div className="small">Mã số</div>
                    <Input
                        value={filter.MaSo ?? ""}
                        onChange={(e) => setFilter((prev) => ({ ...prev, MaSo: e.target.value }))}
                        placeholder="Mã số"
                    />
                </div>
                <div>
                    <div className="small">Tên đề tài</div>
                    <Input
                        value={filter.TenDeTai ?? ""}
                        onChange={(e) => setFilter((prev) => ({ ...prev, TenDeTai: e.target.value }))}
                        placeholder="Tên đề tài"
                    />
                </div>
                <div>
                    <div className="small">Đơn vị</div>
                    <SelectAntd<number>
                        className="w-100"
                        allowClear
                        showSearch
                        placeholder="Đơn vị"
                        value={filter.ID_DonVi && filter.ID_DonVi > 0 ? filter.ID_DonVi : null}
                        options={listDonvi.map((donvi) => ({ value: donvi.id, label: donvi.TenDonVi }))}
                        onChange={(value) => setFilter((prev) => ({ ...prev, ID_DonVi: value ?? 0 }))}
                        optionFilterProp="label"
                    />
                </div>
                <div>
                    <div className="small">Mảng sách</div>
                    <SelectAntd<number>
                        className="w-100"
                        allowClear
                        showSearch
                        placeholder="Mảng sách"
                        value={filter.ID_MangSach && filter.ID_MangSach > 0 ? filter.ID_MangSach : null}
                        options={listMangsach.map((mang) => ({ value: mang.id, label: mang.TenMang }))}
                        onChange={(value) => setFilter((prev) => ({ ...prev, ID_MangSach: value ?? 0 }))}
                        optionFilterProp="label"
                    />
                </div>
            </div>
            <div className="mb-2">
                <Button loading={isLoading} onClick={handleSearch}>
                    Tìm kiếm
                </Button>
            </div>
            <Table<PhieuDkDetai>
                rowKey="id"
                size="small"
                bordered
                pagination={false}
                columns={columns}
                dataSource={listResult}
                rowSelection={{
                    selectedRowKeys,
                    onChange: setSelectedRowKeys,
                }}
                scroll={{ y: 360 }}
            />
        </Modal>
    );
});
