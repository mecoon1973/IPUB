import React, { useMemo } from "react";
import SelectAnt from "../../core/utils/SelectAntd";

type BaseSelectProps = React.ComponentProps<typeof SelectAnt>;

/** Khi true: viền đỏ cảnh báo (Ant Design `status="error"`). */
export type ComponentSelectAntWarningProps = {
    warning?: boolean;
};

export interface ComponentSelectAntObjectProps<T> extends BaseSelectProps, ComponentSelectAntWarningProps {
    listData: T[];
    keyValue: keyof T;
    labelValue: keyof T;
    onCustomLabel?: (item: T) => string;
}

function ComponentSelectAntObjectInner<T>(props: ComponentSelectAntObjectProps<T>) {
    const { listData, keyValue, labelValue, onCustomLabel, warning, ...selectProps } = props;

    const options = useMemo(() => {
        return listData.map(item => ({
            label: onCustomLabel ? onCustomLabel(item) : item[labelValue],
            value: item[keyValue],
        }));
    }, [listData, keyValue, labelValue, onCustomLabel]);

    return (
        <SelectAnt
            {...selectProps}
            options={options}
            {...(warning ? { status: "error" as const } : {})}
        />
    );
}

export const ComponentSelectAntObject = React.memo(ComponentSelectAntObjectInner) as <T>(props: ComponentSelectAntObjectProps<T>) => React.ReactElement;


export interface ComponentSelectAntMapProps<T> extends BaseSelectProps, ComponentSelectAntWarningProps {
    mapData: Record<any, any>;
}

function ComponentSelectAntMapInner<T>(props: ComponentSelectAntMapProps<T>) {
    const { mapData, warning, ...selectProps } = props;

    const options = useMemo(() => {
        return Object.entries(mapData).map(([key, value]) => ({
            label: value,
            value: key,
        }));
    }, [mapData]);

    return (
        <SelectAnt
            {...selectProps}
            options={options}
            {...(warning ? { status: "error" as const } : {})}
        />
    );
}

export const ComponentSelectAntMap = React.memo(ComponentSelectAntMapInner) as <T>(props: ComponentSelectAntMapProps<T>) => React.ReactElement;
