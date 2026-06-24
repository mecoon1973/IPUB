import React, { useCallback, useEffect, useState } from "react";
import dayjs from "dayjs";
import { mountReactComponentOnReady, readRootDataProps } from "../../../core/utils/helpers";
import { FilterQDIn } from "../../component/QDIn/FilterQDIn";
import type { QDIn, QDInFilter } from "../../type";
import { defaultPagiInfo, type PagiInfo } from "../../../page/type";
import { QDInApi } from "../../api/QDInApi";
import { TableQDIn } from "../../component/QDIn/TableQDIn";
import { ComponentPagination } from "../../../page/component/pagination";


interface ViewManageQDInProps {

}

export const ViewManageQDIn = React.memo((props: ViewManageQDInProps) => {
    const [listQDIn, setListQDIn] = useState<QDIn[]>([]);
    const [pagiInfo, setPagiInfo] = useState<PagiInfo>(defaultPagiInfo);
    const [filterQDIn, setFilterQDIn] = useState<QDInFilter>(() => ({
        SoQD: "",
        ID_DV_QD: 0,
        startDate: dayjs().subtract(1, "month").startOf("day").toDate(),
        endDate: dayjs().endOf("day").toDate(),
    }));
    const [isLoadingSearch, setIsLoadingSearch] = useState(false);

    const handleChangeFilterQDIn = useCallback((key: keyof QDInFilter, value: QDInFilter[keyof QDInFilter]) => {
        setFilterQDIn((prev: QDInFilter) => ({
            ...prev,
            [key]: value,
        }));
    }, [setFilterQDIn]);

    const handleSearch = useCallback((page?: string) => {
        setIsLoadingSearch(true);
        QDInApi.getPaginate(filterQDIn, page).then((res: { listResult: QDIn[], pagiInfo: PagiInfo }) => {
            setListQDIn(res.listResult);
            setPagiInfo(res.pagiInfo);
        }).finally(() => {
            setIsLoadingSearch(false);
        });
    }, [filterQDIn]);

    useEffect(() => {
        handleSearch();
    }, []);

    return (
        <div className="px-2">
            <FilterQDIn
                filter={filterQDIn}
                onChangeFilter={handleChangeFilterQDIn}
                isLoadingSearch={isLoadingSearch}
                handleSearch={handleSearch}
            />

            <TableQDIn listQDIn={listQDIn} />
            <ComponentPagination pagiInfo={pagiInfo} callBack={handleSearch} />
        </div>
    );
});


const ROOT_ID = "root-manage-qd-in";
const bladeProps: ViewManageQDInProps = {};
mountReactComponentOnReady(ROOT_ID, <ViewManageQDIn {...bladeProps} />);
