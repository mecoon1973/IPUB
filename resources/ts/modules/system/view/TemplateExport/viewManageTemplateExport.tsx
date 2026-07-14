import {
    mountReactComponentOnReady,
    readRootDataProps,
} from "../../../core/utils/helpers";
import React, { useCallback, useEffect, useMemo, useState } from "react";
import type { TemplateExport } from "../../type/TemplateExport";
import { TemplateExportApi } from "../../api/TemplateExportApi";
import { defaultPagiInfo, type PagiInfo } from "../../../page/type";
import { Button, Divider, Dropdown, Table, type MenuProps } from "antd";
import { ComponentPagination } from "../../../page/component/pagination";
import { getFileNameFromPath } from "../../../core/utils/helperFile";

interface tableTemplateExportProps {
    templateExport: TemplateExport[];
    handleDeleteTemplateExport: (id: number) => void;
}

const TableTemplateExport = React.memo((props: tableTemplateExportProps) => {
    const { templateExport, handleDeleteTemplateExport } = props;
    const columns = useMemo(() => [
        {
            title: "STT",
            dataIndex: "stt",
            key: "stt",
            render: (text: string, record: TemplateExport, index: number) => index + 1,
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
            render: (_value: unknown, record: TemplateExport) => {
                const items: MenuProps["items"] = [
                    {
                        key: "edit",
                        label: <a href={`/he-thong/template-export/cap-nhat/${record.id}`}>Chỉnh sửa</a>,
                    },
                    {
                        key: "delete",
                        label: <span className="text-danger">Xóa</span>,
                        onClick: () => handleDeleteTemplateExport(record.id),
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
        <Table<TemplateExport> rowKey="id" columns={columns} dataSource={templateExport} pagination={false} size="small" />
    );
});

interface ViewManageTemplateExportProps {
}

export const ViewManageTemplateExport = React.memo((props: ViewManageTemplateExportProps) => {
    const {  } = props;
    const [templateExport, setTemplateExport] = useState<TemplateExport[]>([]);
    const [pagiInfo, setPagiInfo] = useState<PagiInfo>(defaultPagiInfo);

    const fetchTemplateExport = useCallback((page?: string) => {
        const conditions = {
            IsDeleted: false
        }
        TemplateExportApi.getPaginateTemplateExport(conditions, page).then((res: { listResult: TemplateExport[], pagiInfo: PagiInfo }) => {
            setTemplateExport(res.listResult);
            setPagiInfo(res.pagiInfo);
        });
    }, [setTemplateExport, setPagiInfo]);

    const handleDeleteTemplateExport = useCallback((id: number) => {
        const isConfirmed = window.confirm("Bạn có chắc chắn muốn xóa Template Excel này không?");
        if (!isConfirmed) return;
        TemplateExportApi.delete(id).then((res: boolean) => {
            if (res) {
                window._toastbox("Xóa Template Excel thành công", "success");
            }
        });
    }, [setTemplateExport]);

    useEffect(() => {
        fetchTemplateExport();
    }, []);

    return (
        <div className="px-2">
            <div className="px-1 py-1">
                <Button type="link" href="/he-thong/template-export/cap-nhat" className="text-success fw-semibold px-0">
                    + Thêm Template Excel
                </Button>
            </div>
            <Divider className="my-2" />
            <TableTemplateExport templateExport={templateExport} handleDeleteTemplateExport={handleDeleteTemplateExport} />
            <ComponentPagination pagiInfo={pagiInfo} callBack={fetchTemplateExport} />
        </div>
    );
});

const ROOT_ID = "root-manage-template-export";
const bladeProps = readRootDataProps<ViewManageTemplateExportProps>(ROOT_ID) ?? {};
mountReactComponentOnReady(ROOT_ID, <ViewManageTemplateExport {...bladeProps} />);
