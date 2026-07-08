import {
    mountReactComponentOnReady,
    readRootDataProps,
} from "../../../core/utils/helpers";
import React, { useCallback, useEffect, useMemo, useState } from "react";
import type { TemplateExcel } from "../../type/TemplateExcel";
import { TemplateExcelApi } from "../../api/TemplateExcelApi";
import { defaultPagiInfo, type PagiInfo } from "../../../page/type";
import { Button, Divider, Dropdown, Table, type MenuProps } from "antd";
import { ComponentPagination } from "../../../page/component/pagination";
import { getFileNameFromPath } from "../../../core/utils/helperFile";


interface tableTemplateExcelProps {
    templateExcel: TemplateExcel[];
    handleDeleteTemplateExcel: (id: number) => void;
}

const TableTemplateExcel = React.memo((props: tableTemplateExcelProps) => {
    const { templateExcel, handleDeleteTemplateExcel } = props;
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
            title: "Key",
            dataIndex: "key",
            key: "key",
        },
        {
            title: "Đường dẫn file template",
            dataIndex: "path_file_template",
            key: "path_file_template",
            render: (path_file_template: string) => {
                return <a href={path_file_template} target="_blank" rel="noopener noreferrer">{getFileNameFromPath(path_file_template)}</a>;
            },
        },
        {
            title: "",
            key: "action",
            width: 132,
            render: (_value: unknown, record: TemplateExcel) => {
                const items: MenuProps["items"] = [
                    {
                        key: "edit",
                        label: <a href={`/he-thong/template-excel/cap-nhat/${record.id}`}>Chỉnh sửa</a>,
                    },
                    {
                        key: "delete",
                        label: <span className="text-danger">Xóa</span>,
                        onClick: () => handleDeleteTemplateExcel(record.id),
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

    const handleDeleteTemplateExcel = useCallback((id: number) => {
        const isConfirmed = window.confirm("Bạn có chắc chắn muốn xóa Template Excel này không?");
        if (!isConfirmed) return;
        TemplateExcelApi.delete(id).then((res: boolean) => {
            if (res) {
                window._toastbox("Xóa Template Excel thành công", "success");
            }
        });
    }, [setTemplateExcel]);

    useEffect(() => {
        fetchTemplateExcel();
    }, []);

    return (
        <div className="px-2">
            <div className="px-1 py-1">
                <Button type="link" href="/he-thong/template-excel/cap-nhat" className="text-success fw-semibold px-0">
                    + Thêm Template Excel
                </Button>
            </div>
            <Divider className="my-2" />
            <TableTemplateExcel templateExcel={templateExcel} handleDeleteTemplateExcel={handleDeleteTemplateExcel} />
            <ComponentPagination pagiInfo={pagiInfo} callBack={fetchTemplateExcel} />
        </div>
    );
});


const ROOT_ID = "root-manage-template-excel";
const bladeProps = readRootDataProps<ViewManageTemplateExcelProps>(ROOT_ID) ?? {};
mountReactComponentOnReady(ROOT_ID, <ViewManageTemplateExcel {...bladeProps} />);
