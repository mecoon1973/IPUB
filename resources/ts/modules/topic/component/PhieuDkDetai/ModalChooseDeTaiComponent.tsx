import React, { forwardRef, useCallback, useEffect, useImperativeHandle, useMemo, useRef, useState } from "react";
import { usePhieuDkDetaiStore } from "../../store/PhieuDkDetai/phieuDkDetaiStore";
import { PhieuDkDetaiApi } from "../../api/PhieuDkDetaiApi";
import { ModalTree } from "../../../page/component/componentModalTree";
import { Modal, Table } from "antd";
import { Button, Input } from "antd";
import SelectAntd from "../../../core/utils/SelectAntd";
import type { PhieuDkDetai } from "../../type";
import type { ColumnsType } from "antd/es/table";
import type { Mangsach } from "../../../system/type";
import { useDataViewStore } from "../../../system/store/useDataViewStore";

const MODAL_CHON_DETAI_COLUMNS: ColumnsType<PhieuDkDetai> = [
    { title: "STT", key: "stt", width: 48, render: (_v, _r, i) => i + 1 },
    { title: "Mã số", dataIndex: "MaSo", key: "MaSo", width: 90 },
    { title: "Tên sách", dataIndex: "TenSach", key: "TenSach", width: 160 },
    { title: "Tác giả", dataIndex: "TacGia", key: "TacGia", width: 100 },
    { title: "Năm TB/XB", dataIndex: "NamTBXB", key: "NamTBXB", width: 95 },
    { title: "Kiểu bản quyền", dataIndex: "KieuBanQuyen", key: "KieuBanQuyen", width: 120 },
    {
        title: "Bản quyền",
        key: "BanQuyen",
        children: [
            { title: "Từ ngày", dataIndex: "BanQuyenTuNgay", key: "BanQuyenTuNgay", width: 95 },
            { title: "Đến ngày", dataIndex: "BanQuyenDenNgay", key: "BanQuyenDenNgay", width: 95 },
        ],
    },
    { title: "Đơn vị sở hữu bản quyền", dataIndex: "DonViSoHuuBQ", key: "DonViSoHuuBQ", width: 170 },
    { title: "HĐBS", dataIndex: "HDBS", key: "HDBS", width: 70 },
    { title: "Chú thích", dataIndex: "ChuThich", key: "ChuThich", width: 90 },
    { title: "Đơn vị", dataIndex: "DonVi", key: "DonVi", width: 90 },
];

/** Chiều cao body bảng: bớt chỗ cho header modal + form + footer (tránh dòng cuối bị footer đè). */
const MODAL_TABLE_SCROLL_Y = "clamp(180px, calc(100vh - 520px), 38vh)";

interface PhieuDkModalSearchResultTableHandle {
    getSelectedItems: () => PhieuDkDetai[];
    clearSelection: () => void;
}

interface PhieuDkModalSearchResultTableProps {
    dataSource: PhieuDkDetai[];
}

/** Bảng trong modal: state chọn dòng cục bộ để không re-render form tìm kiếm khi tick checkbox. */
const PhieuDkModalSearchResultTable = React.memo(
    forwardRef<PhieuDkModalSearchResultTableHandle, PhieuDkModalSearchResultTableProps>(function PhieuDkModalSearchResultTable(props, ref) {
        const { dataSource } = props;
        const [selectedRowKeys, setSelectedRowKeys] = useState<React.Key[]>([]);
        const dataSourceRef = useRef(dataSource);
        const selectedRowKeysRef = useRef<React.Key[]>([]);
        dataSourceRef.current = dataSource;

        const syncSelection = useCallback((keys: React.Key[]) => {
            selectedRowKeysRef.current = keys;
            setSelectedRowKeys(keys);
        }, []);

        useEffect(() => {
            syncSelection([]);
        }, [dataSource, syncSelection]);

        useImperativeHandle(
            ref,
            () => ({
                getSelectedItems: () => {
                    const keys = new Set(selectedRowKeysRef.current);
                    return dataSourceRef.current.filter((item) => keys.has(item.id));
                },
                clearSelection: () => syncSelection([]),
            }),
            [syncSelection],
        );

        const rowSelection = useMemo(
            () => ({
                selectedRowKeys,
                onChange: (keys: React.Key[]) => syncSelection(keys),
            }),
            [selectedRowKeys, syncSelection],
        );

        return (
            <div
                style={{
                    flex: 1,
                    minHeight: 0,
                    minWidth: 0,
                    overflow: "hidden",
                }}
            >
                <Table<PhieuDkDetai>
                    rowKey="id"
                    columns={MODAL_CHON_DETAI_COLUMNS}
                    dataSource={dataSource}
                    rowSelection={rowSelection}
                    size="small"
                    bordered
                    pagination={false}
                    scroll={{ x: 1400, y: MODAL_TABLE_SCROLL_Y }}
                />
            </div>
        );
    }),
);

interface ModalChooseDeTaiProps {

}

export const ModalChooseDeTaiComponent = React.memo((props: ModalChooseDeTaiProps) => {
    const listDonvi = useDataViewStore((state) => state.listDonvi);
    const listMangsach = useDataViewStore((state) => state.listMangsach);
    const showModalChonDeTai = usePhieuDkDetaiStore((state) => state.showModalChonDeTai);
    const setShowModalChonDeTai = usePhieuDkDetaiStore((state) => state.setShowModalChonDeTai);
    const filter = usePhieuDkDetaiStore((state) => state.filter);
    const setFilter = usePhieuDkDetaiStore((state) => state.setFilter);
    const setListDetaiTaiBan = usePhieuDkDetaiStore((state) => state.setListDetaiTaiBan);
    const isLoadingSearch = usePhieuDkDetaiStore((state) => state.isLoadingSearch);
    const setIsLoadingSearch = usePhieuDkDetaiStore((state) => state.setIsLoadingSearch);
    const showModalChonMangSach = usePhieuDkDetaiStore((state) => state.showModalChonMangSach);
    const setShowModalChonMangSach = usePhieuDkDetaiStore((state) => state.setShowModalChonMangSach);

    const [phieuDkDetaiSearch, setPhieuDkDetaiSearch] = useState<PhieuDkDetai[]>([]);
    const searchResultTableRef = useRef<PhieuDkModalSearchResultTableHandle>(null);

    const onSearch = useCallback(() => {
        setIsLoadingSearch(true);
        PhieuDkDetaiApi.getList({...filter, limit: 100}).then((res: PhieuDkDetai[]) => {
            window._toastbox("Tải dữ liệu thành công");
            setPhieuDkDetaiSearch(res);
        }).finally(() => {
            setIsLoadingSearch(false);
        });
    }, [filter, setIsLoadingSearch]);

    const handlerSubmitDk = useCallback(() => {
        const selectedItems = searchResultTableRef.current?.getSelectedItems() ?? [];
        if (selectedItems.length === 0) {
            window._toastbox("Vui lòng chọn ít nhất một đề tài", "warning");
            return;
        }
        setListDetaiTaiBan((prev) => {
            const existingIds = new Set(prev.map((p) => p.id));
            const toAdd = selectedItems.filter((item) => !existingIds.has(item.id));
            return [...prev, ...toAdd];
        });
        searchResultTableRef.current?.clearSelection();
        setShowModalChonDeTai(false);
    }, [setListDetaiTaiBan, setShowModalChonDeTai]);

    const modalFlexStyles = useMemo(
        () => ({
            container: {
                display: "flex",
                flexDirection: "column" as const,
                flex: 1,
                minHeight: 0,
                overflow: "hidden",
            },
            header: { flexShrink: 0 },
            body: {
                flex: 1,
                minHeight: 0,
                overflow: "hidden",
                display: "flex",
                flexDirection: "column" as const,
                paddingBottom: 8,
            },
            footer: { flexShrink: 0 },
        }),
        [],
    );

    return (
        <Modal
            open={showModalChonDeTai}
            onCancel={() => setShowModalChonDeTai(false)}
            title="TÌM KIẾM ĐỀ TÀI"
            width="xl"
            transitionName=""
            maskTransitionName=""
            styles={modalFlexStyles}
            style={{
                top: 24,
                maxHeight: "calc(100vh - 48px)",
                paddingBottom: 0,
                overflow: "hidden",
                display: "flex",
                flexDirection: "column",
            }}
            footer={[
                <Button key="choose" type="default" onClick={handlerSubmitDk}>
                    Chọn
                </Button>,
                <Button key="close" onClick={() => setShowModalChonDeTai(false)}>
                    Đóng
                </Button>,
            ]}
        >
            <div
                style={{
                    display: "flex",
                    flexDirection: "column",
                    flex: 1,
                    minHeight: 0,
                    height: "100%",
                }}
            >
                <div className="mb-2" style={{ flexShrink: 0 }}>
                    <div className="d-grid gap-2 mb-2" style={{ gridTemplateColumns: "repeat(4, minmax(0, 1fr))" }}>
                        <div>
                            <div className="small">Mã số</div>
                            <Input value={filter.MaSo} onChange={(e) => setFilter((prev) => ({ ...prev, MaSo: e.target.value }))} placeholder="(Hỗ trợ tìm kiếm theo dạng ???G??6 hoặc *G??6)" />
                        </div>
                        <div>
                            <div className="small">Tên đề tài</div>
                            <Input value={filter.TenDeTai} onChange={(e) => setFilter((prev) => ({ ...prev, TenDeTai: e.target.value }))} placeholder="Tên đề tài" />
                        </div>
                        <div>
                            <div className="small">Tác giả</div>
                            <Input value={filter.TacGia} onChange={(e) => setFilter((prev) => ({ ...prev, TacGia: e.target.value }))} placeholder="Tác giả" />
                        </div>
                        <div>
                            <div className="small">Biên tập viên</div>
                            <Input value={filter.BienTapVien} onChange={(e) => setFilter((prev) => ({ ...prev, BienTapVien: e.target.value }))} placeholder="Biên tập viên" />
                        </div>
                        <div>
                            <div className="small">Năm XB/TB</div>
                            <Input value={filter.NamXuatBan} onChange={(e) => setFilter((prev) => ({ ...prev, NamXuatBan: e.target.value }))} placeholder="Năm XB/TB" />
                        </div>
                        <div>
                            <div className="small">Mảng sách</div>
                            <Input
                                readOnly
                                value={listMangsach.find((mang) => mang.id === filter.ID_MangSach)?.TenMang ?? ""}
                                onClick={() => setShowModalChonMangSach(true)}
                                placeholder="Mảng sách"
                            />
                        </div>
                        <div>
                            <div className="small">Đơn vị</div>
                            <SelectAntd<number>
                                className="w-100"
                                value={filter.ID_DonVi && filter.ID_DonVi > 0 ? filter.ID_DonVi : null}
                                onChange={(value) => setFilter((prev) => ({ ...prev, ID_DonVi: value }))}
                                placeholder="Đơn vị tổ chức bản thảo"
                                options={listDonvi.map((donvi) => ({
                                    value: donvi.id,
                                    label: donvi.TenDonVi,
                                }))}
                                showSearch
                                optionFilterProp="label"
                                filterOption={(input, option) =>
                                    String(option?.label ?? "").toLowerCase().includes(input.toLowerCase())
                                }
                            />
                        </div>
                        <div className="d-flex align-items-end">
                            <Button
                                type="default"
                                className="w-40"
                                onClick={onSearch}
                                loading={isLoadingSearch}
                            >
                                Tìm kiếm
                            </Button>
                        </div>
                    </div>
                </div>

                <PhieuDkModalSearchResultTable ref={searchResultTableRef} dataSource={phieuDkDetaiSearch} />
                <ModalTree<Mangsach>
                    title="Chọn mảng sách"
                    show={showModalChonMangSach}
                    onHide={() => setShowModalChonMangSach(false)}
                    listData={listMangsach}
                    getLabel={(mang) => mang.TenMang}
                    handlerChoose={(mang) => setFilter((prev) => ({ ...prev, ID_MangSach: mang.id }))}
                    usingselectChoose={true}
                    size="sm"
                />
            </div>
        </Modal>
    );
});
