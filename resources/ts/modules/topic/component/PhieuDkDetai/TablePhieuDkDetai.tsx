import React from "react";
import { Dropdown, Table } from "antd";
import type { MenuProps, TableProps } from "antd";
import type { PhieuDkDetai } from "../../type";
import { useManagePhieuDkDetaiStore } from "../../store/PhieuDkDetai/managePhieuDkDetaiStore";
import { useDataViewStore } from "../../../system/store/useDataViewStore";

export const TablePhieuDkDetaiComponent = React.memo(() => {
    const listPhieuDkDetai = useManagePhieuDkDetaiStore((state) => state.listPhieuDkDetai);
    const listDonvi = useDataViewStore((state) => state.listDonvi);
    const mapTrangThai = useDataViewStore((state) => state.mapTrangThai);
    const setPhieuDkDetaiContext = useManagePhieuDkDetaiStore((state) => state.setPhieuDkDetaiContext);
    const setShowModalInfoPhieuDkDetai = useManagePhieuDkDetaiStore((state) => state.setShowModalInfoPhieuDkDetai);
    const setShowProcessStepInfoModal = useManagePhieuDkDetaiStore((state) => state.setShowProcessStepInfoModal);
    const setShowModalXetDuyetNxbgdvn = useManagePhieuDkDetaiStore((state) => state.setShowModalXetDuyetNxbgdvn);
    const setShowModalCapMaSoNxbgd = useManagePhieuDkDetaiStore((state) => state.setShowModalCapMaSoNxbgd);

    const columns: TableProps<PhieuDkDetai>["columns"] = [
        { title: "STT", key: "stt", width: 56, render: (_v, _r, i) => i + 1 },
        { title: "Mã số", dataIndex: "MaSo", key: "MaSo" },
        { title: "Tên đề tài", dataIndex: "TenDeTai", key: "TenDeTai" },
        { title: "Tác giả", dataIndex: "TacGia", key: "TacGia" },
        {
            title: "Đơn vị",
            key: "donvi",
            render: (_, record) =>
                record.ID_DonVi ? listDonvi.find((d) => d.id === record.ID_DonVi)?.TenDonVi ?? "" : "",
        },
        { title: "XB/TB", dataIndex: "NamXuatBan", key: "NamXuatBan" },
        { title: "Trạng thái", key: "TrangThai", render: (_, r) => mapTrangThai[r.TrangThai] ?? "" },
        { title: "Chú thích", dataIndex: "LiDo", key: "LiDo" },
        {
            title: "",
            key: "action",
            width: 120,
            render: (_, phieuDkDetai, index) => {
                const items: MenuProps["items"] = [
                    { key: "mp1", label: "In mẫu 1 (MP1)" },
                    { key: "mp2", label: "In mẫu 2 (MP2)" },
                    {
                        key: "detail",
                        label: "Xem chi tiết",
                        onClick: () => {
                            setPhieuDkDetaiContext(phieuDkDetai);
                            setShowModalInfoPhieuDkDetai(true);
                        },
                    },
                    {
                        key: "stages",
                        label: "Xem công đoạn",
                        onClick: () => {
                            setShowProcessStepInfoModal(true);
                            setPhieuDkDetaiContext(phieuDkDetai);
                        },
                    },
                    { key: "edit", label: <a href={`/phieu-dk-detai/cap-nhat/${phieuDkDetai.id}`}>Chỉnh sửa</a> },
                    { key: "duyet", label: "Xét Duyệt" },
                    {
                        key: "nxb",
                        label: "NXBGDVN Xét duyệt",
                        onClick: () => {
                            setPhieuDkDetaiContext(phieuDkDetai);
                            setShowModalXetDuyetNxbgdvn(true);
                        },
                    },
                    {
                        key: "ma",
                        label: "Cấp mã số NXBGD",
                        onClick: () => {
                            setPhieuDkDetaiContext(phieuDkDetai);
                            setShowModalCapMaSoNxbgd(true);
                        },
                    },
                    { key: "clone", label: "Nhân bản đề tài" },
                    { key: "cancel", label: <span className="text-danger">Hủy đề tài</span>, onClick: () => {} },
                ];
                return (
                    <Dropdown menu={{ items }} trigger={["click"]}>
                        <a href="#" onClick={(e) => e.preventDefault()}>
                            Chức năng
                        </a>
                    </Dropdown>
                );
            },
        },
    ];

    return (
        <Table<PhieuDkDetai>
            rowKey={(r, i) => String(r.id ?? i)}
            columns={columns}
            dataSource={listPhieuDkDetai}
            pagination={false}
            size="small"
            className="text-sm"
        />
    );
});
