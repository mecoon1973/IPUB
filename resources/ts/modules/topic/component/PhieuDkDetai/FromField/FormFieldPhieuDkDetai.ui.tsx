import React from "react";
import { ComponentCKEditor } from "../../../../page/component/CKEditor/componentCKEditor";

/** Các nhãn / thanh section dùng chung — tách file để section memo import không phụ thuộc form container. */

export function SectionBar({
    children,
    spaced,
}: {
    children: React.ReactNode;
    spaced?: boolean;
}) {
    return (
        <div
            className={`phieu-dk-section-bar${spaced ? " phieu-dk-section-bar--spaced" : ""}`}
        >
            {children}
        </div>
    );
}

export function InlineLabelOpt({
    children,
    strong,
}: {
    children: React.ReactNode;
    strong?: boolean;
}) {
    return (
        <span
            className={`phieu-dk-label${strong ? " phieu-dk-label-strong" : ""}`}
        >
            {children}
        </span>
    );
}

export function InlineLabelReq({
    children,
    strong,
}: {
    children: React.ReactNode;
    strong?: boolean;
}) {
    return (
        <span
            className={`phieu-dk-label${strong ? " phieu-dk-label-strong" : ""}`}
        >
            {children}{" "}
            <span className="phieu-dk-asterisk">(*)</span>
        </span>
    );
}

export function InlineLabelSection({ children }: { children: React.ReactNode }) {
    return <span className="phieu-dk-label phieu-dk-label-section">{children}</span>;
}

/** Editor đề cương — bọc memo ở file sections để tránh render lại khi field khác đổi. */
export function DeCuongEditorChrome({
    value,
    onChange,
}: {
    value: string;
    onChange: (v: string) => void;
}) {
    return (
        <div className="phieu-dk-richtext">
            <ComponentCKEditor data={value ?? ""} onChange={onChange} />
        </div>
    );
}
