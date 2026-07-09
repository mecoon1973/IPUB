import React, { useCallback, useEffect, useMemo, useState } from "react";
import { Button, Input, Modal, Table } from "antd";
import type { ColumnsType } from "antd/es/table";
import SelectAntd from "../../../core/utils/SelectAntd";
import type { DonVi } from "../../../user/type";
import type { Mangsach } from "../../../system/type";
import { PhieuDkDetaiApi } from "../../api/PhieuDkDetaiApi";
import type { FilterPhieuDkDetai, PhieuDkDetai } from "../../type";
import { defaultFilterPhieuDkDetai } from "../../type";
import { formatDateToString } from "../../../core/utils/helpersDayjs";

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
    const mangSachOptions = useMemo(
        () => listMangsach.map((mang) => ({ value: mang.id, label: mang.TenMang })),
        [listMangsach],
    );
    const donViOptions = useMemo(
        () => listDonvi.map((donvi) => ({ value: donvi.id, label: donvi.TenDonVi })),
        [listDonvi],
    );

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
        { title: "Mã số", dataIndex: "MaSo", key: "MaSo", width: 110 },
        { title: "Tên đề tài", dataIndex: "TenDeTai", key: "TenDeTai", width: 220 },
        { title: "Tác giả", dataIndex: "TacGia", key: "TacGia", width: 140 },
        {
            title: "Năm TB/XB",
            key: "NamTBXB",
            width: 95,
            render: (_, record) => record.NamXuatBan || record.NamTaiBan || "",
        },
        {
            title: "Loại",
            key: "Loai",
            width: 70,
            render: (_, record) => (record.HTXB ? "Mới" : "Tái bản"),
        },
        {
            title: "Kiểu bản quyền",
            key: "KieuBanQuyen",
            width: 110,
            render: (_, record) => (record.KieuBanQuyen === 1 ? "Có thời hạn" : "Vô thời hạn"),
        },
        {
            title: "Bản quyền",
            key: "BanQuyen",
            children: [
                {
                    title: "Từ ngày",
                    key: "BanQuyenTuNgay",
                    width: 95,
                    render: (_, record) => formatDateToString(record.BanQuyenTuNgay),
                },
                {
                    title: "Đến ngày",
                    key: "BanQuyenDenNgay",
                    width: 95,
                    render: (_, record) => formatDateToString(record.BanQuyenDenNgay),
                },
            ],
        },
        {
            title: "Đơn vị sở hữu bản quyền",
            key: "SoHuuBanQuyen",
            width: 170,
            render: (_, record) => record.SoHuuBanQuyen || "",
        },
        {
            title: "Tên người tạo",
            key: "CreatedBy",
            width: 130,
            render: (_, record) => String(record.CreatedBy ?? ""),
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
            title="CHỌN ĐỀ TÀI"
            width={1180}
            style={{ top: 24 }}
            footer={[
                <Button key="choose" type="primary" onClick={handleConfirm}>
                    Chọn
                </Button>,
                <Button key="close" onClick={onClose}>
                    Đóng
                </Button>,
            ]}
        >
            <div className="d-grid gap-2 mb-2" style={{ gridTemplateColumns: "repeat(3, minmax(0, 1fr))" }}>
                <div>
                    <div className="small">Mã số</div>
                    <Input
                        value={filter.MaSo ?? ""}
                        onChange={(e) => setFilter((prev) => ({ ...prev, MaSo: e.target.value }))}
                        placeholder="(Hỗ trợ tìm kiếm theo dạng ???G??6 hoặc *G??6)"
                    />
                </div>
                <div>
                    <div className="small">Tên sách</div>
                    <Input
                        value={filter.TenDeTai ?? ""}
                        onChange={(e) => setFilter((prev) => ({ ...prev, TenDeTai: e.target.value }))}
                        placeholder="Tên sách"
                    />
                </div>
                <div>
                    <div className="small">Tác giả</div>
                    <Input
                        value={filter.TacGia ?? ""}
                        onChange={(e) => setFilter((prev) => ({ ...prev, TacGia: e.target.value }))}
                        placeholder="Tác giả"
                    />
                </div>
                <div>
                    <div className="small">Biên tập viên</div>
                    <Input
                        value={filter.BienTapVien ?? ""}
                        onChange={(e) => setFilter((prev) => ({ ...prev, BienTapVien: e.target.value }))}
                        placeholder="Biên tập viên"
                    />
                </div>
                <div>
                    <div className="small">Năm XB/TB</div>
                    <Input
                        value={filter.NamXuatBan ?? ""}
                        onChange={(e) => setFilter((prev) => ({ ...prev, NamXuatBan: e.target.value }))}
                        placeholder="2026"
                    />
                </div>
                <div>
                    <div className="small">Hình thức xuất bản</div>
                    <SelectAntd<number>
                        className="w-100"
                        placeholder="-- Tất cả --"
                        value={typeof filter.HTXB === "number" ? filter.HTXB : null}
                        options={[
                            { value: 1, label: "Mới" },
                            { value: 0, label: "Tái bản" },
                        ]}
                        allowClear
                        onChange={(value) => setFilter((prev) => ({ ...prev, HTXB: value ?? undefined }))}
                    />
                </div>
                <div>
                    <div className="small">Đơn vị</div>
                    <SelectAntd<number>
                        className="w-100"
                        allowClear
                        showSearch
                        placeholder="Đơn vị tổ chức bản thảo"
                        value={filter.ID_DonVi && filter.ID_DonVi > 0 ? filter.ID_DonVi : null}
                        options={donViOptions}
                        onChange={(value) => setFilter((prev) => ({ ...prev, ID_DonVi: value ?? 0 }))}
                        optionFilterProp="label"
                    />
                </div>
                <div>
                    <div className="small">Người tạo</div>
                    <Input placeholder="Người tạo" disabled />
                </div>
                <div>
                    <div className="small">Mảng sách</div>
                    <SelectAntd<number>
                        className="w-100"
                        allowClear
                        showSearch
                        placeholder="Mảng sách"
                        value={filter.ID_MangSach && filter.ID_MangSach > 0 ? filter.ID_MangSach : null}
                        options={mangSachOptions}
                        onChange={(value) => setFilter((prev) => ({ ...prev, ID_MangSach: value ?? 0 }))}
                        optionFilterProp="label"
                    />
                </div>
            </div>
            <div className="mb-2 d-flex justify-content-end">
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
                scroll={{ x: 1400, y: 420 }}
            />
            <div className="mt-2 small text-muted">
                Tổng số bản ghi đã chọn: {selectedRowKeys.length}
            </div>
        </Modal>
    );
});
