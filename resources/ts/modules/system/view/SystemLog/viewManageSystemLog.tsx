import {
    mountReactComponentOnReady,
    readRootDataProps,
} from "../../../core/utils/helpers";
import React, { useCallback, useEffect, useMemo, useState } from "react";
import { defaultPagiInfo, type PagiInfo } from "../../../page/type";
import type SystemLog, type SystemLogFilter from "../../type/SystemLog";
import { ComponentPagination } from "../../../page/component/pagination";
import { Button, DatePicker, Divider, Input, Table } from "antd";
import type { TableProps } from "antd";
import { SystemLogApi } from "../../api/SystemLogApi";
import dayjs from "dayjs";
import type { Dayjs } from "dayjs";
import { ComponentModalTreeDonvi, ModalTreeDonvi } from "../../component/Donvi/ModalTreeDonvi";
import type { DonVi } from "../../../user/type/DonVi";
const { RangePicker } = DatePicker;

const TableSystemLog = React.memo((props: { listSystemLog: SystemLog[];}) => {
    const { listSystemLog } = props;
    const columns: TableProps<SystemLog>["columns"] = useMemo(
        () => [
            { title: "STT", key: "stt", width: 72, render: (_v, _r, i) => i + 1 },
            { title: "User", dataIndex: "UserID", key: "id" },
            { title: "Mô tả", dataIndex: "TenTuSach", key: "TenTuSach" },
            { title: "Địa chỉ Ip", dataIndex: "MoTa", key: "MoTa" },
            { title: "Thời gian", dataIndex: "MoTa", key: "MoTa" },

        ],
        [],
    );

    return <Table<SystemLog> rowKey="id" columns={columns} dataSource={listSystemLog} pagination={false} size="small" bordered />;
});

const FilterSystemLog = React.memo((props: {
    filterSystemLog: SystemLogFilter;
    setFilterSystemLog: (filterSystemLog: SystemLogFilter) => void;
    onSearch: () => void;
    listDonvi: DonVi[];
}) => {
    const { filterSystemLog, setFilterSystemLog, onSearch, listDonvi } = props;
    const [showModalChooseDonvi, setShowModalChooseDonvi] = useState(false);
    const onShowModalChooseDonvi = useCallback(() => {
        setShowModalChooseDonvi(true);
    }, []);
    const onHideModalChooseDonvi = useCallback(() => {
        setShowModalChooseDonvi(false);
    }, []);
    const handleDateRangeChange = useCallback(
        (dates: [Dayjs | null, Dayjs | null] | null) => {
            if (!dates || (!dates[0] && !dates[1])) {
                const nextFilter = { ...filterSystemLog };
                delete nextFilter.startDate;
                delete nextFilter.endDate;
                setFilterSystemLog(nextFilter);
                return;
            }

            const nextFilter = { ...filterSystemLog };
            if (dates[0]) {
                nextFilter.startDate = dates[0].toDate();
            } else {
                delete nextFilter.startDate;
            }
            if (dates[1]) {
                nextFilter.endDate = dates[1].toDate();
            } else {
                delete nextFilter.endDate;
            }
            setFilterSystemLog(nextFilter);
        },
        [filterSystemLog, setFilterSystemLog],
    );

    return (
        <div className="mb-2">
            <div className="d-grid gap-2" style={{ gridTemplateColumns: "1.2fr 1.2fr 1fr 1.5fr 1.8fr auto" }}>
                <div>
                    <div className="small text-muted mb-1">Tài khoản đăng nhập</div>
                    <Input
                        type="text"
                        placeholder="Nhập tài khoản đăng nhập"
                        value={filterSystemLog.accountName ?? ""}
                        onChange={(e) => setFilterSystemLog({ ...filterSystemLog, accountName: e.target.value })}
                    />
                </div>
                <div>
                    <div className="small text-muted mb-1">Họ tên</div>
                    <Input
                        type="text"
                        placeholder="Nhập họ tên"
                        value={filterSystemLog.userName ?? ""}
                        onChange={(e) => setFilterSystemLog({ ...filterSystemLog, userName: e.target.value })}
                    />
                </div>
                <div>
                    <div className="small text-muted mb-1">Đơn vị</div>
                    <Input
                        type="text"
                        placeholder="Nhập đơn vị"
                        value={filterSystemLog.id_Dv ? listDonvi.find((donvi) => donvi.id === filterSystemLog.id_Dv)?.TenDonVi : ""}
                        readOnly
                        onClick={onShowModalChooseDonvi}
                    />
                </div>
                <div>
                    <div className="small text-muted mb-1">Nội dung</div>
                    <Input
                        type="text"
                        placeholder="Nhập nội dung"
                        value={filterSystemLog.content ?? ""}
                        onChange={(e) => setFilterSystemLog({ ...filterSystemLog, content: e.target.value })}
                    />
                </div>
                <div>
                    <div className="small text-muted mb-1">Từ ngày - Đến ngày</div>
                    <RangePicker
                        className="w-100"
                        format="DD/MM/YYYY"
                        value={[
                            filterSystemLog.startDate ? dayjs(filterSystemLog.startDate) : null,
                            filterSystemLog.endDate ? dayjs(filterSystemLog.endDate) : null,
                        ]}
                        onChange={(dates) => handleDateRangeChange(dates as [Dayjs | null, Dayjs | null] | null)}
                    />
                </div>
                <div className="d-flex align-items-end">
                    <Button type="primary" onClick={onSearch}>
                        Tìm kiếm
                    </Button>
                </div>
                <ModalTreeDonvi
                    show={showModalChooseDonvi}
                    onHide={onHideModalChooseDonvi}
                    listDonvi={listDonvi}
                    handlerChooseDonvi={(donvi) => {
                        setFilterSystemLog({ ...filterSystemLog, id_Dv: donvi.id });
                    }}
                    usingselectChoose={true}
                />
            </div>
        </div>
    );
});

interface ViewManageSystemLogProps {
    listDonvi: DonVi[];
}

export const ViewManageSystemLog = React.memo((props: ViewManageSystemLogProps) => {
    const { listDonvi } = props;
    const [pagiInfo, setPagiInfo] = useState<PagiInfo>(defaultPagiInfo);
    const [listSystemLog, setListSystemLog] = useState<SystemLog[]>([]);
    const [filterSystemLog, setFilterSystemLog] = useState<SystemLogFilter>({
        startDate: dayjs().subtract(1, "month").startOf("day").toDate(),
        endDate: dayjs().endOf("day").toDate(),
        accountName: "",
        userName: "",
        content: "",
        id_Dv: 0,
    });

    const getListSystemLog = useCallback((page?: string) => {
        SystemLogApi.getPaginateSystemLog(filterSystemLog, page).then((res: { listResult: SystemLog[]; pagiInfo: PagiInfo }) => {
            setPagiInfo(res.pagiInfo);
            setListSystemLog(res.listResult);
        });
    }, [filterSystemLog]);

    const handleSearch = useCallback(() => {
        getListSystemLog();
    }, [getListSystemLog]);

    useEffect(() => {
        getListSystemLog();
    }, [getListSystemLog]);

    return (
        <div className="px-2 py-2">
            <FilterSystemLog filterSystemLog={filterSystemLog} setFilterSystemLog={setFilterSystemLog} onSearch={handleSearch} listDonvi={listDonvi}/>
            <Divider className="my-2" />
            <TableSystemLog listSystemLog={listSystemLog} />
            <ComponentPagination pagiInfo={pagiInfo} callBack={getListSystemLog} />
        </div>
    );
});

const ROOT_ID = "root-manage-system-log";
const bladeProps: ViewManageSystemLogProps = {
    listDonvi: [] as DonVi[],
    ...readRootDataProps<ViewManageSystemLogProps>(ROOT_ID),
};
mountReactComponentOnReady(ROOT_ID, <ViewManageSystemLog {...bladeProps} />);
