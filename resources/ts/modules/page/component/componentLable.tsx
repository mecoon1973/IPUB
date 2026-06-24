import React from "react";

export function LabelReq({ children }: { children: React.ReactNode }) {
    return (
        <span className="mb-0 d-inline-block">
            {children} <span className="text-danger">*</span>
        </span>
    );
}

export function LabelOpt({ children }: { children: React.ReactNode }) {
    return <span className="mb-0 d-inline-block">{children}</span>;
}
