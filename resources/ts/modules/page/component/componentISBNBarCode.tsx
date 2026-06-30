import { useEffect, useRef } from "react";
import bwipjs from "bwip-js/browser";

interface ISBNBarcodeProps {
    ISBN: string;
    MaSo: string;
    bcid?: string
    includetext?: boolean
    scale?: number
    height?: number
}

/**
 * Chuẩn hóa chuỗi ISBN: bỏ dấu gạch nối, khoảng trắng và mọi ký tự không phải chữ số.
 * Ví dụ: "978-604-0-19562-3" -> "9786040195623"
 */
export function normalizeISBN(isbn: string): string {
    return (isbn ?? "").replace(/[^0-9]/g, "");
}

export default function ISBNBarcode(props: ISBNBarcodeProps) {
    const { ISBN, MaSo, bcid = "ean13", includetext = true, scale = 3, height = 18 } = props;
    const canvasRef = useRef<HTMLCanvasElement>(null);
    useEffect(() => {
        if (!canvasRef.current) {
            return;
        }
        bwipjs.toCanvas(canvasRef.current, {
            bcid: bcid,
            text: normalizeISBN(ISBN),
            includetext: includetext,
            scale: scale,
            height: height,
        });
    }, [ISBN, bcid, includetext, scale, height]);

    return (
        <div style={{ textAlign: "center" }}>
            <div>ISBN {ISBN}</div>

            <canvas ref={canvasRef} />

            <div>{MaSo}</div>
        </div>
    );
}
