import React, { useCallback, useEffect, useMemo, useState } from "react";
import { mountReactComponentOnReady, readRootDataProps } from "../../../core/utils/helpers";
import type DonviLC from "../../type/DonviLC";
import { Button, Dropdown, Input, Table, type MenuProps, type TableProps, Divider } from "antd";
import { DonviLCApi } from "../../api/DonviLCApi";
import { defaultPagiInfo, type PagiInfo, type PagiResult } from "../../../page/type";
import { ComponentPagination } from "../../../page/component/pagination";


interface FilterDonviLCProps {
    filterDonviLC: Partial<DonviLC>;
    setFilterDonviLC: (filterDonviLC: Partial<DonviLC>) => void;
    onSearch: () => void;
}

const FilterDonviLC = React.memo((props: FilterDonviLCProps) => {
    const { filterDonviLC, setFilterDonviLC, onSearch } = props;

    const handleSearch = useCallback(() => {
        onSearch();
    }, [onSearch]);

    const handleChange = useCallback((value: string) => {
        setFilterDonviLC({ ...filterDonviLC, Ten: value });
    }, [filterDonviLC, setFilterDonviLC]);

    return (<>
        <div className="px-1 py-1">
            <Button type="link" href="/he-thong/donvi-lc/cap-nhat" className="text-success fw-semibold px-0">
                + Thêm mới
            </Button>
        </div>
        <div className="py-1 px-1 border-bottom d-grid gap-2" style={{ gridTemplateColumns: "1fr auto" }}>
            <div>
                <div className="small text-muted mb-1">Từ khóa tìm kiếm</div>
                <Input
                    placeholder="Từ khóa tìm kiếm"
                    value={filterDonviLC.Ten ?? ""}
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

interface TableDonviLCProps {
    listDonviLC: DonviLC[];
    handleDeleteDonviLC: (id: number) => void;
}

const TableDonviLC = React.memo((props: TableDonviLCProps) => {
    const { listDonviLC, handleDeleteDonviLC } = props;

    const columns: TableProps<DonviLC>["columns"] = useMemo(() => [
        { title: "STT", key: "stt", width: 72, render: (_v, _r, i) => i + 1 },
        { title: "Tên đối tượng", dataIndex: "Ten", key: "Ten" },
        { title: "Thứ tự", dataIndex: "ThuTu", key: "ThuTu" },
        { title: "", dataIndex: "", key: "act", render : (_, record: DonviLC) => {
            const items: MenuProps["items"] = [
                {
                    key: "edit",
                    label: <a href={`/he-thong/donvi-lc/cap-nhat/${record.id}`}>Chỉnh sửa</a>,
                },
                {
                    key: "delete",
                    label: <span className="text-danger">Xóa</span>,
                    onClick: () => handleDeleteDonviLC(record.id),
                },
            ];
            return (
                <Dropdown menu={{ items }} trigger={["click"]}>
                    <Button type="link" className="px-0">
                        Chức năng
                    </Button>
                </Dropdown>
            );
        } },
    ], [handleDeleteDonviLC]);

    return (
        <Table<DonviLC> rowKey="id" columns={columns} dataSource={listDonviLC} pagination={false} size="small" />
    );
});

interface ManageDonviLCProps {
}

export const ManageDonviLC = React.memo((props: ManageDonviLCProps) => {
    const {  } = props;
    const [listDonviLC, setListDonviLC] = useState<DonviLC[]>([]);
    const [filter, setFilter] = useState<Partial<DonviLC>>(DonviLCApi.conditionDefault);
    const [pagiInfo, setPagiInfo] = useState<PagiInfo>(defaultPagiInfo);

    const getListDonviLC = useCallback((page?: string) => {
        DonviLCApi.getPaginate(filter, page).then((res: PagiResult<DonviLC>) => {
            setPagiInfo(res.pagiInfo);
            setListDonviLC(res.listResult);
        });
    }, [filter]);
    const handleDeleteDonviLC = useCallback((id: number) => {
        const isConfirmed = window.confirm("Bạn có chắc chắn muốn xóa đơn vị lưu chuyển này không?");
        if (!isConfirmed) return;
        DonviLCApi.delete(id).then((res: boolean) => {
            if (res) {
                setListDonviLC((prev: DonviLC[]) => prev.filter((item: DonviLC) => item.id !== id));
            }
        });
    }, []);

    useEffect(() => {
        getListDonviLC();
    }, []);

    return <div className="px-2">
        <FilterDonviLC onSearch={getListDonviLC} filterDonviLC={filter} setFilterDonviLC={setFilter} />
        <TableDonviLC listDonviLC={listDonviLC} handleDeleteDonviLC={handleDeleteDonviLC} />
        <ComponentPagination pagiInfo={pagiInfo} callBack={getListDonviLC} />

    </div>
})
const ROOT_ID = "root-manage-donvilc";
const bladeProps = {
    ...readRootDataProps<ManageDonviLCProps>(ROOT_ID)
};
mountReactComponentOnReady(ROOT_ID, <ManageDonviLC {...bladeProps} />);
