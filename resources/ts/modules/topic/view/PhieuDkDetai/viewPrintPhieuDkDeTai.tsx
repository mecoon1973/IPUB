import {
    mountReactComponentOnReady,
    readRootDataProps,
} from "../../../core/utils/helpers";
import React, { useCallback, useMemo } from "react";
import { Dropdown } from "antd";
import type { MenuProps } from "antd";
import { DownloadOutlined } from "@ant-design/icons";
import { ComponentPdfJs } from "../../../page/component/componentPdfJs";
import { PhieuDkDetaiApi } from "../../api/PhieuDkDetaiApi";
import { downloadFileFromUrl } from "../../../core/utils/helperFile";

interface ViewPrintPhieuDkDeTaiProps {
    url: string;
    id: number;
    path_file_docx: string;
}

const TEMPLATE_KEY = "Template_Phieu_DK_DE_TAI";

export const ViewPrintPhieuDkDeTai = React.memo((props: ViewPrintPhieuDkDeTaiProps) => {
    const { url, id, path_file_docx } = props;

    const exportAndDownload = useCallback(async (format: "pdf" | "docx" | "xlsx" | "html" | "txt") => {
        if (!id) {
            window._toastbox("Thiếu ID phiếu đăng ký đề tài", "danger");
            return;
        }

        const fileUrl = await PhieuDkDetaiApi.printPhieuDkDeTai(id, {
            template_name: TEMPLATE_KEY,
            template_format: format,
            path_file_docx: path_file_docx,
        });
        if (!fileUrl) {
            return;
        }

        downloadFileFromUrl(fileUrl);
    }, [id, path_file_docx]);

    const exportMenu: MenuProps["items"] = useMemo(
        () => [
            {
                key: "pdf",
                label: "PDF",
                onClick: () => {
                    void exportAndDownload("pdf");
                },
            },
            {
                key: "docx",
                label: "DOCX",
                onClick: () => {
                    void exportAndDownload("docx");
                },
            },
            {
                key: "excel",
                label: "Excel",
                onClick: () => {
                    void exportAndDownload("xlsx");
                },
            },
            {
                key: "html",
                label: "HTML",
                onClick: () => {
                    void exportAndDownload("html");
                },
            },
            {
                key: "txt",
                label: "TXT",
                onClick: () => {
                    void exportAndDownload("txt");
                },
            },
        ],
        [exportAndDownload]
    );

    const actions = (
        <Dropdown menu={{ items: exportMenu }} trigger={["click"]} placement="bottomRight">
            <button type="button" title="Xuất file" aria-label="Xuất file">
                <DownloadOutlined />
            </button>
        </Dropdown>
    );

    return (
        <div className="px-2">
            <ComponentPdfJs url={url} actions={actions} />
        </div>
    );
});

const ROOT_ID = "root-print-phieu-dk-de-tai";
const bladeProps: ViewPrintPhieuDkDeTaiProps = {
    url: "",
    id: 0,
    path_file_docx: "",
    ...readRootDataProps<ViewPrintPhieuDkDeTaiProps>(ROOT_ID),
};
mountReactComponentOnReady(ROOT_ID, <ViewPrintPhieuDkDeTai {...bladeProps} />);
