import React, { useCallback, useState } from "react";
import { Button } from "antd";
import { PlusOutlined } from "@ant-design/icons";
import {
    ContentEditGroupPanel,
    type ContentEditGroup,
} from "./ContentEditGroupPanel";
import type { ReplaceEntry, ReplaceEntryField } from "./ContentReplaceEntryRow";
import {
    ContentEditTemplateType,
    type ContentEditTemplate,
    type ContentReplaceTemplate,
} from "../../type/TemplateExport";

function createId(prefix: string): string {
    return `${prefix}-${Date.now()}-${Math.random().toString(36).slice(2, 8)}`;
}

function createEmptyReplaceEntry(): ReplaceEntry {
    return {
        id: createId("replace"),
        key: "",
        value: "",
        callback: "",
    };
}

function createEmptyGroup(): ContentEditGroup {
    return {
        id: createId("group"),
        type: ContentEditTemplateType.TEXT,
        key_data: "",
        mapEntries: [createEmptyReplaceEntry()],
    };
}

function mapReplateToEntries(map: Record<string, ContentReplaceTemplate> | undefined): ReplaceEntry[] {
    if (!map || Object.keys(map).length === 0) {
        return [createEmptyReplaceEntry()];
    }

    return Object.entries(map).map(([key, item]) => ({
        id: createId("replace"),
        key,
        value: item.value ?? "",
        callback: item.callback ?? "",
    }));
}

function contentEditToGroups(value: ContentEditTemplate[] | undefined): ContentEditGroup[] {
    if (!value || value.length === 0) {
        return [createEmptyGroup()];
    }

    return value.map((item) => ({
        id: createId("group"),
        type: item.type ?? ContentEditTemplateType.TEXT,
        key_data: item.key_data ?? "",
        mapEntries: mapReplateToEntries(item.map_replate),
    }));
}

function entriesToMapReplate(entries: ReplaceEntry[]): Record<string, ContentReplaceTemplate> {
    const result: Record<string, ContentReplaceTemplate> = {};

    for (const entry of entries) {
        if (!isReplaceEntryComplete(entry)) {
            continue;
        }

        result[entry.key.trim()] = {
            value: entry.value.trim(),
            callback: entry.callback.trim(),
        };
    }

    return result;
}

function groupsToContentEdit(groups: ContentEditGroup[]): ContentEditTemplate[] {
    const result: ContentEditTemplate[] = [];

    for (const group of groups) {
        if (!isContentEditGroupComplete(group)) {
            continue;
        }

        result.push({
            type: group.type,
            key_data: group.key_data.trim(),
            map_replate: entriesToMapReplate(group.mapEntries),
        });
    }

    return result;
}

function isReplaceEntryComplete(entry: ReplaceEntry): boolean {
    return entry.key.trim() !== "" && entry.value.trim() !== "";
}

function areAllReplaceEntriesComplete(entries: ReplaceEntry[]): boolean {
    return entries.length > 0 && entries.every(isReplaceEntryComplete);
}

function isContentEditGroupComplete(group: ContentEditGroup): boolean {
    if (!areAllReplaceEntriesComplete(group.mapEntries)) {
        return false;
    }

    if (group.type === ContentEditTemplateType.LOOP) {
        return group.key_data.trim() !== "";
    }

    return true;
}

function areAllContentEditGroupsComplete(groups: ContentEditGroup[]): boolean {
    return groups.length > 0 && groups.every(isContentEditGroupComplete);
}

export function isContentEditListComplete(value: ContentEditTemplate[] = []): boolean {
    return groupsToContentEdit(contentEditToGroups(value)).length > 0
        && value.every((item) => {
            const entries = mapReplateToEntries(item.map_replate);
            const group: ContentEditGroup = {
                id: "",
                type: item.type,
                key_data: item.key_data ?? "",
                mapEntries: entries,
            };

            return isContentEditGroupComplete(group);
        });
}

export interface ContentEditEditorProps {
    value?: ContentEditTemplate[];
    onChange: (value: ContentEditTemplate[]) => void;
    disabled?: boolean;
}

export const ContentEditEditor = React.memo((props: ContentEditEditorProps) => {
    const { value, onChange, disabled = false } = props;
    const [groups, setGroups] = useState<ContentEditGroup[]>(() => contentEditToGroups(value));

    const applyGroupsUpdate = useCallback(
        (updater: (prev: ContentEditGroup[]) => ContentEditGroup[]) => {
            setGroups((prev) => {
                const nextGroups = updater(prev);
                const normalizedGroups = nextGroups.length > 0 ? nextGroups : [createEmptyGroup()];
                onChange(groupsToContentEdit(normalizedGroups));
                return normalizedGroups;
            });
        },
        [onChange],
    );

    const handleUpdateGroupField = useCallback(
        (groupIndex: number, field: "type" | "key_data", fieldValue: string) => {
            applyGroupsUpdate((prev) =>
                prev.map((group, index) => {
                    if (index !== groupIndex) {
                        return group;
                    }

                    return {
                        ...group,
                        [field]: fieldValue,
                    };
                }),
            );
        },
        [applyGroupsUpdate],
    );

    const handleUpdateReplaceEntry = useCallback(
        (groupIndex: number, entryIndex: number, field: ReplaceEntryField, fieldValue: string) => {
            applyGroupsUpdate((prev) =>
                prev.map((group, gIndex) => {
                    if (gIndex !== groupIndex) {
                        return group;
                    }

                    return {
                        ...group,
                        mapEntries: group.mapEntries.map((entry, eIndex) => {
                            if (eIndex !== entryIndex) {
                                return entry;
                            }

                            return {
                                ...entry,
                                [field]: fieldValue,
                            };
                        }),
                    };
                }),
            );
        },
        [applyGroupsUpdate],
    );

    const handleAddReplaceEntry = useCallback(
        (groupIndex: number) => {
            setGroups((prev) => {
                const group = prev[groupIndex];
                if (!group || !areAllReplaceEntriesComplete(group.mapEntries)) {
                    window._toastbox("Vui lòng nhập đầy đủ key, value trước khi thêm dòng mới", "error");
                    return prev;
                }

                const nextGroups = prev.map((item, index) => {
                    if (index !== groupIndex) {
                        return item;
                    }

                    return {
                        ...item,
                        mapEntries: [...item.mapEntries, createEmptyReplaceEntry()],
                    };
                });

                const normalizedGroups = nextGroups.length > 0 ? nextGroups : [createEmptyGroup()];
                onChange(groupsToContentEdit(normalizedGroups));
                return normalizedGroups;
            });
        },
        [onChange],
    );

    const handleRemoveReplaceEntry = useCallback(
        (groupIndex: number, entryIndex: number) => {
            applyGroupsUpdate((prev) =>
                prev.map((group, gIndex) => {
                    if (gIndex !== groupIndex) {
                        return group;
                    }

                    const nextEntries = group.mapEntries.filter((_, eIndex) => eIndex !== entryIndex);

                    return {
                        ...group,
                        mapEntries: nextEntries.length > 0 ? nextEntries : [createEmptyReplaceEntry()],
                    };
                }),
            );
        },
        [applyGroupsUpdate],
    );

    const handleRemoveGroup = useCallback(
        (groupIndex: number) => {
            applyGroupsUpdate((prev) => prev.filter((_, index) => index !== groupIndex));
        },
        [applyGroupsUpdate],
    );

    const handleAddGroup = useCallback(() => {
        setGroups((prev) => {
            if (!areAllContentEditGroupsComplete(prev)) {
                window._toastbox(
                    "Vui lòng nhập đầy đủ key_data (với vòng lặp), type và các cặp key/value trước khi thêm nhóm mới",
                    "error",
                );
                return prev;
            }

            const duplicatedKeyData = prev.some((group, index) => {
                const keyData = group.key_data.trim();
                return (
                    group.type === ContentEditTemplateType.LOOP
                    && keyData !== ""
                    && prev.findIndex((item) => item.key_data.trim() === keyData && item.type === ContentEditTemplateType.LOOP) !== index
                );
            });

            if (duplicatedKeyData) {
                window._toastbox("key_data vòng lặp không được trùng nhau", "error");
                return prev;
            }

            const nextGroups = [...prev, createEmptyGroup()];
            onChange(groupsToContentEdit(nextGroups));
            return nextGroups;
        });
    }, [onChange]);

    const canAddGroup = areAllContentEditGroupsComplete(groups);

    return (
        <div className="flex flex-col gap-3">
            {groups.map((group, groupIndex) => (
                <ContentEditGroupPanel
                    key={group.id}
                    group={group}
                    groupIndex={groupIndex}
                    totalGroups={groups.length}
                    disabled={disabled}
                    onUpdateGroupField={handleUpdateGroupField}
                    onUpdateEntry={handleUpdateReplaceEntry}
                    onRemoveEntry={handleRemoveReplaceEntry}
                    onAddEntry={handleAddReplaceEntry}
                    onRemoveGroup={handleRemoveGroup}
                />
            ))}

            <Button
                type="dashed"
                block
                icon={<PlusOutlined />}
                disabled={disabled || !canAddGroup}
                onClick={handleAddGroup}
            />
        </div>
    );
});

ContentEditEditor.displayName = "ContentEditEditor";
