import React from "react";

const controlClass =
    "w-full [&_.ant-input]:w-full [&_.ant-input-number]:w-full [&_.ant-picker]:w-full [&_.ant-input]:rounded-md [&_.ant-input-number]:rounded-md [&_.ant-picker]:rounded-md [&_.ant-input-disabled]:bg-gray-100 [&_.ant-input-disabled]:text-gray-500";

export function FormSection({
    title,
    children,
}: {
    title: string;
    children: React.ReactNode;
}) {
    return (
        <section className="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm mb-2">
            <header className="border-b border-gray-200 bg-gray-50 px-4 py-2.5 text-[11px] font-semibold uppercase tracking-wider text-gray-500">
                {title}
            </header>
            <div className="p-4">{children}</div>
        </section>
    );
}

export function FieldLabel({
    children,
    required,
}: {
    children: React.ReactNode;
    required?: boolean;
}) {
    return (
        <label className="m-0 text-[13px] font-medium leading-snug text-gray-500">
            {children}
            {required ? <span className="ml-0.5 text-red-600">*</span> : null}
        </label>
    );
}

export function FormField({
    label,
    required,
    className,
    children,
}: {
    label: React.ReactNode;
    required?: boolean;
    className?: string;
    children: React.ReactNode;
}) {
    return (
        <div className={`flex min-w-0 flex-col gap-1.5 ${className ?? ""}`}>
            <FieldLabel required={required === true}>{label}</FieldLabel>
            <div className={controlClass}>{children}</div>
        </div>
    );
}

export function SplitField({ children }: { children: React.ReactNode }) {
    return <div className="flex items-center gap-2">{children}</div>;
}

export function SplitSep({ children }: { children: React.ReactNode }) {
    return <span className="shrink-0 select-none text-sm text-gray-400">{children}</span>;
}

export function RadioRow({ children }: { children: React.ReactNode }) {
    return (
        <div className="flex min-h-8 flex-wrap items-center gap-x-5 gap-y-2 pt-0.5 text-sm text-gray-800">
            {children}
        </div>
    );
}

export function CheckboxRow({ children }: { children: React.ReactNode }) {
    return <div className="flex min-h-8 items-center pt-0.5">{children}</div>;
}
