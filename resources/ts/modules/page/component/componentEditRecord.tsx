import React, { useCallback, useState } from "react";
import { Button, Input } from "antd";
import { CloseOutlined, PlusOutlined } from "@ant-design/icons";

type MapDataEntry = {
    key: string;
    value: string;
};

function recordToEntries(map: Record<string, string> | undefined): MapDataEntry[] {
    if (!map) {
        return [];
    }

    return Object.entries(map).map(([key, value]) => ({
        key,
        value,
    }));
}

function entriesToRecord(entries: MapDataEntry[]): Record<string, string> {
    const result: Record<string, string> = {};

    for (const entry of entries) {
        if (!isEntryComplete(entry)) {
            continue;
        }
        result[entry.key.trim()] = entry.value.trim();
    }

    return result;
}

function createEmptyEntry(): MapDataEntry {
    return { key: "", value: "" };
}

function isEntryComplete(entry: MapDataEntry): boolean {
    return entry.key.trim() !== "" && entry.value.trim() !== "";
}

function areAllEntriesComplete(entries: MapDataEntry[]): boolean {
    return entries.length > 0 && entries.every(isEntryComplete);
}

export function isMapDataRecordComplete(map: Record<string, string> = {}): boolean {
    const entries = recordToEntries(map);

    return entries.length > 0 && areAllEntriesComplete(entries);
}

export interface ComponentEditRecordProps {
    value?: Record<string, string>;
    onChange: (value: Record<string, string>) => void;
    disabled?: boolean;
}

export const ComponentEditRecord = React.memo((props: ComponentEditRecordProps) => {
    const { value, onChange, disabled = false } = props;
    const [entries, setEntries] = useState<MapDataEntry[]>(() => {
        const initialEntries = recordToEntries(value);
        return initialEntries.length > 0 ? initialEntries : [createEmptyEntry()];
    });

    const syncEntries = useCallback(
        (nextEntries: MapDataEntry[]) => {
            const normalizedEntries = nextEntries.length > 0 ? nextEntries : [createEmptyEntry()];
            setEntries(normalizedEntries);
            onChange(entriesToRecord(normalizedEntries));
        },
        [onChange],
    );

    const handleAddEntry = useCallback(() => {
        if (!areAllEntriesComplete(entries)) {
            window._toastbox("Vui lòng nhập đầy đủ key và value trước khi thêm dòng mới", "error");
            return;
        }

        syncEntries([...entries, createEmptyEntry()]);
    }, [entries, syncEntries]);

    const canAddEntry = areAllEntriesComplete(entries);

    const handleUpdateEntry = useCallback(
        (index: number, field: keyof MapDataEntry, fieldValue: string) => {
            const nextEntries = entries.map((entry, entryIndex) => {
                if (entryIndex !== index) {
                    return entry;
                }

                return {
                    ...entry,
                    [field]: fieldValue,
                };
            });

            syncEntries(nextEntries);
        },
        [entries, syncEntries],
    );

    const handleRemoveEntry = useCallback(
        (index: number) => {
            const nextEntries = entries.filter((_, entryIndex) => entryIndex !== index);
            syncEntries(nextEntries);
        },
        [entries, syncEntries],
    );

    return (
        <div className="flex flex-col gap-2">
            {entries.map((entry, index) => (
                <div
                    key={`map-data-row-${index}`}
                    className="flex w-full items-center gap-2"
                >
                    <div className="min-w-0 flex-1">
                        <Input
                            value={entry.key}
                            placeholder="key"
                            disabled={disabled}
                            onChange={(event) => handleUpdateEntry(index, "key", event.target.value)}
                        />
                    </div>
                    <div className="min-w-0 flex-1">
                        <Input
                            value={entry.value}
                            placeholder="value"
                            disabled={disabled}
                            onChange={(event) => handleUpdateEntry(index, "value", event.target.value)}
                        />
                    </div>
                    <Button
                        type="default"
                        className="shrink-0"
                        icon={<CloseOutlined />}
                        disabled={disabled}
                        onClick={() => handleRemoveEntry(index)}
                    />
                </div>
            ))}

            <Button
                type="dashed"
                block
                icon={<PlusOutlined />}
                disabled={disabled || !canAddEntry}
                onClick={handleAddEntry}
            />
        </div>
    );
});

ComponentEditRecord.displayName = "ComponentEditRecord";
