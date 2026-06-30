import bwipjs from "bwip-js/browser";
import { normalizeISBN } from "../componentISBNBarCode";

export interface BarcodeOptions {
    bcid?: string;
    includetext?: boolean;
    scale?: number;
    height?: number;
}

const DEFAULT_OPTIONS: Required<BarcodeOptions> = {
    bcid: "ean13",
    includetext: true,
    scale: 3,
    height: 18,
};

/**
 * Vẽ mã vạch ra canvas ẩn (dùng nội bộ để lấy được cả kích thước thật).
 */
function renderBarcodeCanvas(text: string, options: BarcodeOptions = {}): HTMLCanvasElement {
    const opts = { ...DEFAULT_OPTIONS, ...options };
    const canvas = document.createElement("canvas");
    bwipjs.toCanvas(canvas, {
        bcid: opts.bcid,
        text: normalizeISBN(text),
        includetext: opts.includetext,
        scale: opts.scale,
        height: opts.height,
    });
    return canvas;
}

/**
 * Vẽ mã vạch ra canvas ẩn rồi trả về data URL (PNG).
 * Dùng data URL để có thể nhúng thẳng vào nội dung HTML của CKEditor.
 */
export function generateBarcodeDataUrl(text: string, options: BarcodeOptions = {}): string {
    return renderBarcodeCanvas(text, options).toDataURL("image/png");
}

/**
 * HTML cho một nhãn mã vạch: dòng ISBN, ảnh mã vạch, dòng mã số.
 */
export function buildBarcodeLabelHtml(isbn: string, maSo: string, options: BarcodeOptions = {}): string {
    const src = generateBarcodeDataUrl(isbn, options);
    return (
        `<span style="display:inline-block;text-align:center;margin:6px 10px;vertical-align:top">` +
        `<span style="display:block;font-size:12px">ISBN ${isbn}</span>` +
        `<img src="${src}" alt="ISBN ${isbn}" style="display:block;margin:0 auto" />` +
        `<span style="display:block;font-size:13px">${maSo}</span>` +
        `</span>`
    );
}

/**
 * HTML cho một "tờ" nhãn mã vạch vừa trên một trang (giống ảnh lưới nhãn in).
 * Mặc định 4 cột x 7 hàng = 28 nhãn.
 * @param columns số cột (mặc định 4)
 * @param rows    số hàng (mặc định 7)
 */
export function buildBarcodeSheetHtml(
    isbn: string,
    maSo: string,
    columns: number = 4,
    rows: number = 7,
    options: BarcodeOptions = {},
): string {
    const canvas = renderBarcodeCanvas(isbn, options);
    const src = canvas.toDataURL("image/png");
    const cols = Math.max(1, columns);
    const numRows = Math.max(1, rows);
    const cellWidth = (100 / cols).toFixed(4);

    const cell =
        `<td style="text-align:center;width:${cellWidth}%;border-width:0px;">` +
        `<span style="font-size:12px">ISBN ${isbn}</span><br>` +
        `<img src="${src}" alt="ISBN ${isbn}" width="${canvas.width}" height="${canvas.height}" style="max-width:100%" /><br>` +
        `<span style="font-size:13px">${maSo}</span>` +
        `</td>`;
    const row = `<tr style="border-width:0px;">${cell.repeat(cols)}</tr>`;
    const body = Array.from({ length: numRows }, () => row).join("");
    return `<figure class="table"><table style="width:100%;border-width:0px;"><tbody>${body}</tbody></table></figure>`;
}
