import {
    mountReactComponentOnReady,
    readRootDataProps,
} from "../../../core/utils/helpers";
import React, { useCallback, useEffect, useMemo, useState } from "react";
import { type PagiInfo } from "../../../page/type";
import { ComponentPagination } from "../../../page/component/pagination";
import { Button, Col, Divider, Dropdown, Input, Row, Table } from "antd";
import type { MenuProps, TableProps } from "antd";
import type { DonVi } from "../../../user/type";
import type { Sach } from "../../type";
import {  FilterSachComponent } from "../../component/Sach/FilterSach";
import { useManageSachStore } from "../../store/useManageSachStore";
import { SachApi } from "../../api/SachApi";
import type { Mangsach } from "../../../system/type";
import { useDataViewStore } from "../../../system/store/useDataViewStore";
import { FileOutlined, UpCircleOutlined, EditOutlined, PrinterOutlined, StopOutlined } from "@ant-design/icons";
import { ModalChangeKeySach, ModalInfoSach, ModalUpdateCountSach, ModalUpdateLicenseSach, ModalUpdatePriceSach } from "../../component/Sach/ModalSach";

interface TableSachProps {
    listSach: Sach[];
    handleDeleteSach: (id: number) => void;
}

const TableSach = React.memo((props: TableSachProps) => {
    const { listSach, handleDeleteSach } = props;
    const setSachSelected = useManageSachStore((state) => state.setSachSelected);
    const setOpenModalChangeKey = useManageSachStore((state) => state.setOpenModalChangeKey);
    const setOpenModalUpdateCount = useManageSachStore((state) => state.setOpenModalUpdateCount);
    const setOpenModalUpdateLicense = useManageSachStore((state) => state.setOpenModalUpdateLicense);
    const setOpenModalUpdatePrice = useManageSachStore((state) => state.setOpenModalUpdatePrice);
    const setOpenModalInfo = useManageSachStore((state) => state.setOpenModalInfo);

    const handlePrintISBN = useCallback((sach: Sach) => {
        window.location.href = `/sach/in-ma-isbn/${sach.id}`;
    }, []);

    const columns: TableProps<Sach>["columns"] = useMemo(
        () => [
            { title: "STT", key: "stt", render: (_v, _r, i) => i + 1 },
            { title: "Mã số", dataIndex: "MaSo", key: "MaSo" },
            { title: "Tên đề tài", dataIndex: "TenSach", key: "TenSach" },
            { title: "Tác giả", dataIndex: "TacGia", key: "TacGia" },
            { title: "Đơn vị", dataIndex: ["don_vi", "TenDonVi"], key: "don_vi.id" },
            {
                title: "XB/TB",
                dataIndex: "NamXuatBan",
                key: "NamXuatBan",
                render: (value, record) => value || record.NamTaiBan,
            },
            {
                title: "Trạng thái",
                key: "action",
                width: 132,
                render: (_value, record: Sach) => {
                    const items: MenuProps["items"] = [
                        {
                            key: "info",
                            label: <React.Fragment><UpCircleOutlined /> <label>Xem chi tiết</label></React.Fragment>,
                            onClick: () => {
                                setOpenModalInfo(true);
                            },
                        },
                        {
                            key: "change_key",
                            label: <React.Fragment><FileOutlined /> <label htmlFor="change_key">Đổi mã số sách</label></React.Fragment>,
                            onClick: () => {
                                setOpenModalChangeKey(true);
                            },
                        },
                        {
                            key: "update_count",
                            label: <React.Fragment><EditOutlined /> <label>Cập nhập SL cấp phép</label></React.Fragment>,
                            onClick: () => {
                                setOpenModalUpdateCount(true);
                            },
                        },
                        {
                            key: "update_license",
                            label: <React.Fragment><EditOutlined /> <label htmlFor="update_license">Cập nhật bản quyền</label></React.Fragment>,
                            onClick: () => {
                                setOpenModalUpdateLicense(true);
                            },
                        },
                        {
                            key: "update_price",
                            label: <React.Fragment><EditOutlined /> <label>Cập nhật giá bìa</label></React.Fragment>,
                            onClick: () => {
                                setOpenModalUpdatePrice(true);
                            },
                        },
                        {
                            key: "print_isbn",
                            label: <React.Fragment><PrinterOutlined /> <label>In mã ISBN</label></React.Fragment>,
                            onClick: () => {
                                handlePrintISBN(record);
                            },
                        },
                        {
                            key: "cancel",
                            label: <React.Fragment><StopOutlined /> <label>Hủy sách</label></React.Fragment>,
                            onClick: () => {
                                handleDeleteSach(record.id);
                            },
                        },
                    ];
                    return (
                        <Dropdown
                            menu={{ items }}
                            trigger={["click"]}
                            onOpenChange={(open) => {
                                if(open) {
                                    setSachSelected(record);
                                }
                            }}
                        >
                            <Button type="link" className="px-0">
                                Chức năng
                            </Button>
                        </Dropdown>
                    );
                },
            },
        ],
        [handleDeleteSach],
    );

    return <Table<Sach> rowKey="id" columns={columns} dataSource={listSach} pagination={false} size="small" />;
});


interface ViewManageSachProps {
    listDonvi: DonVi[];
    listMangsach: Mangsach[];
}

export const ViewManageSach = React.memo((props: ViewManageSachProps) => {
    const {listDonvi, listMangsach} = props;
    const setData = useDataViewStore((state) => state.setData);
    const pagiInfo = useManageSachStore((state) => state.pagiInfo);
    const listSach = useManageSachStore((state) => state.listSach);
    const filter = useManageSachStore((state) => state.filterSach);
    const openModalChangeKey = useManageSachStore((state) => state.openModalChangeKey);
    const openModalUpdateCount = useManageSachStore((state) => state.openModalUpdateCount);
    const openModalUpdateLicense = useManageSachStore((state) => state.openModalUpdateLicense);
    const openModalUpdatePrice = useManageSachStore((state) => state.openModalUpdatePrice);
    const openModalInfo = useManageSachStore((state) => state.openModalInfo);
    const setOpenModalChangeKey = useManageSachStore((state) => state.setOpenModalChangeKey);
    const setOpenModalUpdateCount = useManageSachStore((state) => state.setOpenModalUpdateCount);
    const setOpenModalUpdateLicense = useManageSachStore((state) => state.setOpenModalUpdateLicense);
    const setOpenModalUpdatePrice = useManageSachStore((state) => state.setOpenModalUpdatePrice);
    const setSachSelected = useManageSachStore((state) => state.setSachSelected);
    const setOpenModalInfo = useManageSachStore((state) => state.setOpenModalInfo);
    const sachSelected = useManageSachStore((state) => state.sachSelected);
    const setListSach = useManageSachStore((state) => state.setListSach);
    const setPagiInfo = useManageSachStore((state) => state.setPagiInfo);
    const getListSach = useCallback((page?: string) => {
        SachApi.getPaginate(filter, page).then((res: { listResult: Sach[]; pagiInfo: PagiInfo }) => {
            setPagiInfo(res.pagiInfo);
            setListSach(res.listResult);
        });
    }, [filter]);

    const handleDeleteSach = useCallback((id: number) => {
        const isConfirmed = window.confirm("Bạn có chắc chắn muốn xóa sách này không?");
        if (!isConfirmed) return;
        SachApi.delete(id).then((res: boolean) => {
            if (res) {
                window._toastbox("Xóa sách thành công", "success");
                setListSach((prev) => prev.filter((item) => item.id !== id));
            }
        });
    }, []);

    const handelSavedSach = useCallback((sach: Sach) => {
        setListSach((prev) => prev.map((item) => item.id === sach.id ? sach : item));
    }, [setListSach]);

    useEffect(() => {
        setData({ listDonvi, listMangsach });
        getListSach();
    }, []);


    return (
        <div className="px-2 py-2">
            <FilterSachComponent getListSach={getListSach} />
            <Divider className="my-2" />
            <Row gutter={12}>
                <Col span={24}>
                    <TableSach listSach={listSach} handleDeleteSach={handleDeleteSach} />
                </Col>
            </Row>
            <ComponentPagination pagiInfo={pagiInfo} callBack={getListSach} />
            <ModalChangeKeySach
                sach={sachSelected}
                open={openModalChangeKey}
                onClose={() => {
                    setOpenModalChangeKey(false)
                    setSachSelected(null);
                }}
                onSaved={handelSavedSach}
            />
            <ModalUpdateCountSach
                sach={sachSelected}
                open={openModalUpdateCount}
                onClose={() => {
                    setOpenModalUpdateCount(false)
                    setSachSelected(null);
                }}
                onSaved={handelSavedSach}
            />
            <ModalUpdateLicenseSach
                sach={sachSelected}
                open={openModalUpdateLicense}
                onClose={() => {
                    setOpenModalUpdateLicense(false)
                    setSachSelected(null);
                }} onSaved={handelSavedSach}
            />
            <ModalUpdatePriceSach
                sach={sachSelected}
                open={openModalUpdatePrice}
                onClose={() => {
                    setOpenModalUpdatePrice(false)
                    setSachSelected(null);
                }}
                onSaved={handelSavedSach}
            />
            <ModalInfoSach
                sach={sachSelected}
                open={openModalInfo}
                onClose={() => {
                    setOpenModalInfo(false)
                    setSachSelected(null);
                }}
            />
        </div>
    );
});


const ROOT_ID = "root-manage-sach";
const bladeProps: ViewManageSachProps = {
    listDonvi: [],
    listMangsach: [],
    ...readRootDataProps<ViewManageSachProps>(ROOT_ID)
};
mountReactComponentOnReady(ROOT_ID, <ViewManageSach {...bladeProps} />);
