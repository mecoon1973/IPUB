import React, { useCallback } from "react";
import { Button, Input } from "antd";
import { CloseOutlined } from "@ant-design/icons";

export type ReplaceEntry = {
    id: string;
    key: string;
    value: string;
    callback: string;
};

export type ReplaceEntryField = "key" | "value" | "callback";

export interface ContentReplaceEntryRowProps {
    entry: ReplaceEntry;
    groupIndex: number;
    entryIndex: number;
    disabled?: boolean;
    onUpdateEntry: (
        groupIndex: number,
        entryIndex: number,
        field: ReplaceEntryField,
        fieldValue: string,
    ) => void;
    onRemoveEntry: (groupIndex: number, entryIndex: number) => void;
}

export const ContentReplaceEntryRow = React.memo((props: ContentReplaceEntryRowProps) => {
    const {
        entry,
        groupIndex,
        entryIndex,
        disabled = false,
        onUpdateEntry,
        onRemoveEntry,
    } = props;

    const handleKeyChange = useCallback(
        (event: React.ChangeEvent<HTMLInputElement>) => {
            onUpdateEntry(groupIndex, entryIndex, "key", event.target.value);
        },
        [groupIndex, entryIndex, onUpdateEntry],
    );

    const handleValueChange = useCallback(
        (event: React.ChangeEvent<HTMLInputElement>) => {
            onUpdateEntry(groupIndex, entryIndex, "value", event.target.value);
        },
        [groupIndex, entryIndex, onUpdateEntry],
    );

    const handleCallbackChange = useCallback(
        (event: React.ChangeEvent<HTMLInputElement>) => {
            onUpdateEntry(groupIndex, entryIndex, "callback", event.target.value);
        },
        [groupIndex, entryIndex, onUpdateEntry],
    );

    const handleRemove = useCallback(() => {
        onRemoveEntry(groupIndex, entryIndex);
    }, [groupIndex, entryIndex, onRemoveEntry]);

    return (
        <div className="flex w-full items-center gap-2">
            <div className="min-w-0 flex-1">
                <Input
                    value={entry.key}
                    placeholder="key (placeholder / ô Excel)"
                    disabled={disabled}
                    onChange={handleKeyChange}
                />
            </div>
            <div className="min-w-0 flex-1">
                <Input
                    value={entry.value}
                    placeholder="value (đường dẫn dữ liệu)"
                    disabled={disabled}
                    onChange={handleValueChange}
                />
            </div>
            <div className="min-w-0 flex-1">
                <Input
                    value={entry.callback}
                    placeholder="callback (tuỳ chọn)"
                    disabled={disabled}
                    onChange={handleCallbackChange}
                />
            </div>
            <Button
                type="default"
                className="shrink-0"
                icon={<CloseOutlined />}
                disabled={disabled}
                onClick={handleRemove}
            />
        </div>
    );
});

ContentReplaceEntryRow.displayName = "ContentReplaceEntryRow";
