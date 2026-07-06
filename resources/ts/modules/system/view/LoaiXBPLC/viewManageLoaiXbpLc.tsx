import {
    mountReactComponentOnReady,
    readRootDataProps,
} from "../../../core/utils/helpers";
import React, { useCallback, useEffect, useMemo, useState } from "react";
import { defaultPagiInfo, type PagiInfo, type PagiResult } from "../../../page/type";
import type { LoaiXBP } from "../../type/LoaiXBP";
import type { LoaiXbpLc } from "../../type/LoaiXbpLc";
import { ComponentPagination } from "../../../page/component/pagination";
import { Button, Divider, Dropdown, Input, Table } from "antd";
import type { MenuProps, TableProps } from "antd";
import { LoaiXbpLcApi } from "../../api/LoaiXbpLcApi";

interface FilterLoaiXbpLcProps {
    filterLoaiXbpLc: Partial<LoaiXbpLc>;
    setFilterLoaiXbpLc: (filterLoaiXbpLc: Partial<LoaiXbpLc>) => void;
    onSearch: () => void;
}

const FilterLoaiXbpLc = React.memo((props: FilterLoaiXbpLcProps) => {
    const { filterLoaiXbpLc, setFilterLoaiXbpLc, onSearch } = props;

    const handleSearch = useCallback(() => {
        onSearch();
    }, [onSearch]);

    const handleChange = useCallback((value: string) => {
        setFilterLoaiXbpLc({ ...filterLoaiXbpLc, TenLoai: value });
    }, [filterLoaiXbpLc, setFilterLoaiXbpLc]);

    return (<>
        <div className="px-1 py-1">
            <Button type="link" href="/he-thong/loai-xbp-luu-chieu/cap-nhat" className="text-success fw-semibold px-0">
                + Thêm mới
            </Button>
        </div>
        <div className="py-1 px-1 border-bottom d-grid gap-2" style={{ gridTemplateColumns: "1fr auto" }}>
            <div>
                <div className="small text-muted mb-1">Từ khóa tìm kiếm</div>
                <Input
                    placeholder="Từ khóa tìm kiếm"
                    value={filterLoaiXbpLc.TenLoai ?? ""}
                    onChange={(e) => handleChange(e.target.value)}
                    onPressEnter={handleSearch}
                />
            </div>
            <div className="d-flex align-items-end">
                <Button type="primary" onClick={handleSearch}>
                    Tìm kiếm
                </Button>
            </div>
        </div>
        <Divider className="my-2" />
    </>
    );
});

export const TableLoaiXbpLc = React.memo((props: { listLoaiXbpLc: LoaiXbpLc[]; handleDeleteLoaiXbpLc: (id: number) => void }) => {
    const { listLoaiXbpLc, handleDeleteLoaiXbpLc } = props;
    const columns: TableProps<LoaiXbpLc>["columns"] = useMemo(
        () => [
            { title: "STT", key: "stt", width: 72, render: (_v, _r, i) => i + 1 },
            { title: "Tên loại xuất bản phẩm", dataIndex: "TenLoai", key: "TenLoai" },
            {
                title: "",
                key: "action",
                width: 132,
                render: (_value, record: LoaiXbpLc) => {
                    const items: MenuProps["items"] = [
                        {
                            key: "edit",
                            label: <a href={`/he-thong/loai-xbp-luu-chieu/cap-nhat/${record.id}`}>Chỉnh sửa</a>,
                        },
                        {
                            key: "delete",
                            label: <span className="text-danger">Xóa</span>,
                            onClick: () => handleDeleteLoaiXbpLc(record.id),
                        },
                    ];
                    return (
                        <Dropdown menu={{ items }} trigger={["click"]}>
                            <Button type="link" className="px-0">
                                Chức năng
                            </Button>
                        </Dropdown>
                    );
                },
            },
        ],
        [handleDeleteLoaiXbpLc],
    );

    return <Table<LoaiXbpLc> rowKey="id" columns={columns} dataSource={listLoaiXbpLc} pagination={false} size="small" />;
});

interface ViewManageLoaiXbpLcProps {}

export const ViewManageLoaiXbpLc = React.memo((props: ViewManageLoaiXbpLcProps) => {
    const {} = props;
    const [pagiInfo, setPagiInfo] = useState<PagiInfo>(defaultPagiInfo);
    const [listLoaiXbpLc, setListLoaiXbpLc] = useState<LoaiXbpLc[]>([]);
    const [filterLoaiXbpLc, setFilterLoaiXbpLc] = useState<Partial<LoaiXbpLc>>(LoaiXbpLcApi.conditionDefault);
    const getListLoaiXbpLc = useCallback((page?: string) => {
        LoaiXbpLcApi.getPaginate(filterLoaiXbpLc, page).then((res: PagiResult<LoaiXbpLc>) => {
            setPagiInfo(res.pagiInfo);
            setListLoaiXbpLc(res.listResult);
        });
    }, [filterLoaiXbpLc]);

    const handleDeleteLoaiXbpLc = useCallback((id: number) => {
        const isConfirmed = window.confirm("Bạn có chắc chắn muốn xóa nhóm này không?");
        if (!isConfirmed) return;
        LoaiXbpLcApi.delete(id).then((res: boolean) => {
            if (res) {
                window._toastbox("Xóa nhóm thành công", "success");
                setListLoaiXbpLc((prev: LoaiXbpLc[]) => prev.filter((x: LoaiXbpLc) => x.id !== id));
            }
        });
    }, []);

    useEffect(() => {
        getListLoaiXbpLc();
    }, [getListLoaiXbpLc]);

    return (
        <div className="px-2 py-2">
            <FilterLoaiXbpLc filterLoaiXbpLc={filterLoaiXbpLc} setFilterLoaiXbpLc={setFilterLoaiXbpLc} onSearch={getListLoaiXbpLc} />
            <Divider className="my-2" />
            <TableLoaiXbpLc listLoaiXbpLc={listLoaiXbpLc} handleDeleteLoaiXbpLc={handleDeleteLoaiXbpLc} />
            <ComponentPagination pagiInfo={pagiInfo} callBack={getListLoaiXbpLc} />
        </div>
    );
});

const ROOT_ID = "root-manage-loai-xbp-lc";
const bladeProps = readRootDataProps<ViewManageLoaiXbpLcProps>(ROOT_ID) ?? {};
mountReactComponentOnReady(ROOT_ID, <ViewManageLoaiXbpLc {...bladeProps} />);
