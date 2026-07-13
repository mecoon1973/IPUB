import React, { useCallback } from "react";
import { Button, Input, Select } from "antd";
import { CloseOutlined, PlusOutlined } from "@ant-design/icons";
import { ContentEditTemplateType } from "../../type/TemplateExport";
import {
    ContentReplaceEntryRow,
    type ReplaceEntry,
    type ReplaceEntryField,
} from "./ContentReplaceEntryRow";

export type ContentEditGroup = {
    id: string;
    type: ContentEditTemplateType;
    key_data: string;
    mapEntries: ReplaceEntry[];
};

const TYPE_OPTIONS = [
    { label: "Chèn text", value: ContentEditTemplateType.TEXT },
    { label: "Vòng lặp", value: ContentEditTemplateType.LOOP },
];

function isReplaceEntryComplete(entry: ReplaceEntry): boolean {
    return entry.key.trim() !== "" && entry.value.trim() !== "";
}

function areAllReplaceEntriesComplete(entries: ReplaceEntry[]): boolean {
    return entries.length > 0 && entries.every(isReplaceEntryComplete);
}

export interface ContentEditGroupPanelProps {
    group: ContentEditGroup;
    groupIndex: number;
    totalGroups: number;
    disabled?: boolean;
    onUpdateGroupField: (groupIndex: number, field: "type" | "key_data", fieldValue: string) => void;
    onUpdateEntry: (
        groupIndex: number,
        entryIndex: number,
        field: ReplaceEntryField,
        fieldValue: string,
    ) => void;
    onRemoveEntry: (groupIndex: number, entryIndex: number) => void;
    onAddEntry: (groupIndex: number) => void;
    onRemoveGroup: (groupIndex: number) => void;
}

export const ContentEditGroupPanel = React.memo((props: ContentEditGroupPanelProps) => {
    const {
        group,
        groupIndex,
        totalGroups,
        disabled = false,
        onUpdateGroupField,
        onUpdateEntry,
        onRemoveEntry,
        onAddEntry,
        onRemoveGroup,
    } = props;

    const isLoop = group.type === ContentEditTemplateType.LOOP;
    const canAddReplaceEntry = areAllReplaceEntriesComplete(group.mapEntries);

    const handleKeyDataChange = useCallback(
        (event: React.ChangeEvent<HTMLInputElement>) => {
            onUpdateGroupField(groupIndex, "key_data", event.target.value);
        },
        [groupIndex, onUpdateGroupField],
    );

    const handleTypeChange = useCallback(
        (nextType: ContentEditTemplateType) => {
            onUpdateGroupField(groupIndex, "type", nextType);
        },
        [groupIndex, onUpdateGroupField],
    );

    const handleRemoveGroup = useCallback(() => {
        onRemoveGroup(groupIndex);
    }, [groupIndex, onRemoveGroup]);

    const handleAddEntry = useCallback(() => {
        onAddEntry(groupIndex);
    }, [groupIndex, onAddEntry]);

    return (
        <div className="flex flex-col gap-2 rounded-lg border border-gray-200 p-3">
            <div className="flex w-full items-center gap-2">
                <div className="min-w-0 flex-[2]">
                    <Input
                        value={group.key_data}
                        placeholder={isLoop ? "key_data (vd: listItem)" : "key_data (tuỳ chọn)"}
                        disabled={disabled}
                        onChange={handleKeyDataChange}
                    />
                </div>
                <div className="min-w-0 flex-1">
                    <Select
                        className="w-full"
                        value={group.type}
                        options={TYPE_OPTIONS}
                        disabled={disabled}
                        onChange={handleTypeChange}
                    />
                </div>
                <Button
                    type="default"
                    className="shrink-0"
                    icon={<CloseOutlined />}
                    disabled={disabled || totalGroups <= 1}
                    onClick={handleRemoveGroup}
                />
            </div>

            <div className="ml-2 flex flex-col gap-2 border-l border-gray-200 pl-4">
                {group.mapEntries.map((entry, entryIndex) => (
                    <ContentReplaceEntryRow
                        key={entry.id}
                        entry={entry}
                        groupIndex={groupIndex}
                        entryIndex={entryIndex}
                        disabled={disabled}
                        onUpdateEntry={onUpdateEntry}
                        onRemoveEntry={onRemoveEntry}
                    />
                ))}

                <Button
                    type="dashed"
                    block
                    icon={<PlusOutlined />}
                    disabled={disabled || !canAddReplaceEntry}
                    onClick={handleAddEntry}
                />
            </div>
        </div>
    );
});

ContentEditGroupPanel.displayName = "ContentEditGroupPanel";
