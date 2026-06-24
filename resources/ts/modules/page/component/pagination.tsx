import React, { useMemo } from "react";
import type { PaginationProps as AntdPaginationProps } from "antd";
import { Pagination } from "antd";
import type { PagiInfo } from "../type";

interface ComponentPaginationProps {
    /** thông tin phân trang */
    pagiInfo: PagiInfo;
    /** callback khi chọn trang */
    callBack: (page?: string) => void;
    /** kích thước của pagination */
    size?: "sm" | "md" | "lg";
}

export const ComponentPagination = React.memo((props: ComponentPaginationProps) => {
    const { pagiInfo, callBack } = props;
    const size = props.size ?? "md";

    const last = Math.max(0, Math.floor(Number(pagiInfo.last) || 0));
    const current = Math.max(1, Math.floor(Number(pagiInfo.current_page) || 1));

    const showPagination = useMemo(() => last > 1, [last]);

    const pageSize = useMemo(() => {
        const limit = Math.floor(Number(pagiInfo.limit) || 0);
        if (limit > 0) {
            return limit;
        }
        const totalNum = Number(pagiInfo.total) || 0;
        if (last > 0 && totalNum > 0) {
            return Math.max(1, Math.ceil(totalNum / last));
        }
        return 10;
    }, [pagiInfo.limit, pagiInfo.total, last]);

    const antdSize: AntdPaginationProps["size"] =
        size === "sm" ? "small" : size === "lg" ? "large" : "middle";

    const totalRecords = Math.max(0, Math.floor(Number(pagiInfo.total) || 0));

    if (!showPagination) {
        return null;
    }

    return (
        <Pagination
            align="center"
            current={current}
            total={totalRecords}
            pageSize={pageSize}
            onChange={(page) => callBack(`page-${page}`)}
            showSizeChanger={false}
            responsive
            rootClassName="mb-0"
            size={antdSize}
        />
    );
});

ComponentPagination.displayName = "ComponentPagination";
