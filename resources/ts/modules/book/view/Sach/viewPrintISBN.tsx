import React, { useEffect, useRef, useState } from "react";
import { PrinterOutlined } from "@ant-design/icons";
import { ComponentCKEditor, insertHtmlToEditor } from "../../../page/component/CKEditor/componentCKEditor";
import { buildBarcodeSheetHtml } from "../../../page/component/CKEditor/barcodeHtml";
import { mountReactComponentOnReady, readRootDataProps } from "../../../core/utils/helpers";
import type { Sach } from "../../type/Sach";

interface ViewPrintISBNProps {
    sach: Sach|null;
}

export const ModalPrintISBN = React.memo((props: ViewPrintISBNProps) => {
    const { sach } = props;
    const [data, setData] = useState("");
    const [row, setRow] = useState(7);
    const [column, setColumn] = useState(4);
    const editorRef = useRef<any>(null);
    useEffect(() => {
        if (!editorRef.current) return;
        const html = buildBarcodeSheetHtml(sach?.ISBNCode ?? "", sach?.MaSo ?? "", column, row, {
            bcid: "ean13",
            scale: 2,
            height: 12,
        });
        insertHtmlToEditor(editorRef.current, html);
    }, [row, column, sach])

    const handleReset = () => {
        setColumn(4);
        editorRef.current?.setData("");
    };

    const handlePrint = () => {
        const iframe = document.createElement("iframe");
        iframe.style.position = "fixed";
        iframe.style.right = "0";
        iframe.style.bottom = "0";
        iframe.style.width = "0";
        iframe.style.height = "0";
        iframe.style.border = "0";
        document.body.appendChild(iframe);

        const doc = iframe.contentWindow?.document;
        if (!doc) {
            document.body.removeChild(iframe);
            return;
        }
        doc.open();
        doc.write(
            `<!DOCTYPE html><html><head><meta charset="utf-8"><title>In mã ISBN</title>` +
            `<style>@page{margin:10mm}body{font-family:Arial,sans-serif;margin:0}` +
            `table{width:90%;border-collapse:collapse}` +
            `td{text-align:center;vertical-align:top;padding:16px 12px}` +
            `tr{page-break-inside:avoid}` +
            `img{max-width:100%}</style></head><body>${data}</body></html>`,
        );
        doc.close();

        const win = iframe.contentWindow;
        if (!win) {
            document.body.removeChild(iframe);
            return;
        }
        win.focus();
        setTimeout(() => {
            win.print();
            setTimeout(() => document.body.removeChild(iframe), 1000);
        }, 300);
    };

    return (
        <div className="px-2 py-2">
            <div className="d-flex align-items-center justify-content-between border-bottom pb-2 mb-2">
                <div className="d-flex align-items-center gap-2">
                    <span className="fw-bold">In mã ISBN</span>
                </div>
                <div className="d-flex align-items-center gap-2">
                    <label className="mb-0 small text-muted text-nowrap" htmlFor="so-bo-can-in">
                        Số bộ cần in:
                    </label>
                    <input
                        id="so-bo-can-in"
                        type="number"
                        min={1}
                        className="form-control form-control-sm"
                        style={{ width: 80 }}
                        value={row}
                        onChange={(e) => setRow(Number(e.target.value))}
                    />
                    <button type="button" className="btn btn-sm btn-secondary" onClick={() => {}}>
                        <PrinterOutlined /> Xuất file PDF
                    </button>
                    <button type="button" className="btn btn-sm btn-secondary" onClick={handlePrint}>
                        <PrinterOutlined /> In
                    </button>
                </div>
            </div>
            <ComponentCKEditor
                data={data}
                onChange={(data) => {
                    setData(data);
                }}
                onReady={(editor) => { editorRef.current = editor; }}
            />
        </div>
    );
});

const ROOT_ID = "root-print-isbn";
const bladeProps: ViewPrintISBNProps = {
    sach: null,
    ...readRootDataProps<ViewPrintISBNProps>(ROOT_ID)
};
mountReactComponentOnReady(ROOT_ID, <ModalPrintISBN {...bladeProps} />);
