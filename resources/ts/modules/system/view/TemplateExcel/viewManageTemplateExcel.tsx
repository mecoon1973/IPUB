import {
    mountReactComponentOnReady,
    readRootDataProps,
} from "../../../core/utils/helpers";
import React, { useCallback, useEffect, useMemo, useState } from "react";
import type { TemplateExcel } from "../../type/TemplateExcel";
import { TemplateExcelApi } from "../../api/TemplateExcelApi";
import { defaultPagiInfo, type PagiInfo } from "../../../page/type";
import { Table } from "antd";


interface tableTemplateExcelProps {
    templateExcel: TemplateExcel[];
}

const TableTemplateExcel = React.memo((props: tableTemplateExcelProps) => {
    const { templateExcel } = props;
    const columns = useMemo(() => [
        {
            title: "STT",
            dataIndex: "stt",
            key: "stt",
            render: (text: string, record: TemplateExcel, index: number) => index + 1,
        },
        {
            title: "Tên template",
            dataIndex: "name",
            key: "name",
        },
        {
            title: "Tên template",
            dataIndex: "name",
            key: "name",
        },
        {
            title: "Key",
            dataIndex: "key",
            key: "key",
        },
        {
            title: "Đường dẫn file template",
            dataIndex: "path_file_template",
            key: "path_file_template",
        },
        {
            title: "Trạng thái",
            dataIndex: "IsDeleted",
            key: "IsDeleted",
            render: (text: string) => text ? "Đã xóa" : "Hiện tại",
        },
    ], []);
    return (
        <Table<TemplateExcel> rowKey="id" columns={columns} dataSource={templateExcel} pagination={false} size="small" />
    );
});

interface ViewManageTemplateExcelProps {


}

export const ViewManageTemplateExcel = React.memo((props: ViewManageTemplateExcelProps) => {
    const {  } = props;
    const [templateExcel, setTemplateExcel] = useState<TemplateExcel[]>([]);
    const [pagiInfo, setPagiInfo] = useState<PagiInfo>(defaultPagiInfo);

    const fetchTemplateExcel = useCallback((page?: string) => {
        const conditions = {
            IsDeleted: false
        }
        TemplateExcelApi.getPaginateTemplateExcel(conditions, page).then((res: { listResult: TemplateExcel[], pagiInfo: PagiInfo }) => {
            setTemplateExcel(res.listResult);
            setPagiInfo(res.pagiInfo);
        });
    }, [setTemplateExcel, setPagiInfo]);

    useEffect(() => {
        fetchTemplateExcel();
    }, [fetchTemplateExcel]);

    return (
        <div className="px-2">

        </div>
    );
});


const ROOT_ID = "root-manage-template-excel";
const bladeProps = readRootDataProps<ViewManageTemplateExcelProps>(ROOT_ID) ?? {};
mountReactComponentOnReady(ROOT_ID, <ViewManageTemplateExcel {...bladeProps} />);
