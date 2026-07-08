import React, { useCallback, useState } from "react";
import { Button, Input } from "antd";
import { CloseOutlined, DownOutlined, PlusOutlined, UpOutlined } from "@ant-design/icons";
import { ComponentEditRecord, isMapDataRecordComplete } from "./componentEditRecord";

type MapForeachValue = Record<string, Record<string, string>>;

type ParentGroup = {
    id: string;
    parentKey: string;
    children: Record<string, string>;
    collapsed: boolean;
};

function createParentGroup(parentKey = "", children: Record<string, string> = {}): ParentGroup {
    return {
        id: `parent-${Date.now()}-${Math.random().toString(36).slice(2, 8)}`,
        parentKey,
        children,
        collapsed: false,
    };
}

function recordToGroups(map: MapForeachValue | undefined): ParentGroup[] {
    if (!map || Object.keys(map).length === 0) {
        return [createParentGroup()];
    }

    return Object.entries(map).map(([parentKey, children]) => createParentGroup(parentKey, children));
}

function groupsToRecord(groups: ParentGroup[]): MapForeachValue {
    const result: MapForeachValue = {};

    for (const group of groups) {
        const parentKey = group.parentKey.trim();
        if (parentKey === "" || !isMapDataRecordComplete(group.children)) {
            continue;
        }

        result[parentKey] = group.children;
    }

    return result;
}

function isParentGroupComplete(group: ParentGroup): boolean {
    return group.parentKey.trim() !== "" && isMapDataRecordComplete(group.children);
}

function areAllParentGroupsComplete(groups: ParentGroup[]): boolean {
    return groups.length > 0 && groups.every(isParentGroupComplete);
}

export interface ComponentEditRecordV2Props {
    value?: MapForeachValue;
    onChange: (value: MapForeachValue) => void;
    disabled?: boolean;
}

export const ComponentEditRecordV2 = React.memo((props: ComponentEditRecordV2Props) => {
    const { value, onChange, disabled = false } = props;
    const [groups, setGroups] = useState<ParentGroup[]>(() => recordToGroups(value));

    const syncGroups = useCallback(
        (nextGroups: ParentGroup[]) => {
            const normalizedGroups = nextGroups.length > 0 ? nextGroups : [createParentGroup()];
            setGroups(normalizedGroups);
            onChange(groupsToRecord(normalizedGroups));
        },
        [onChange],
    );

    const handleUpdateParentKey = useCallback(
        (index: number, parentKey: string) => {
            const nextGroups = groups.map((group, groupIndex) => {
                if (groupIndex !== index) {
                    return group;
                }

                return {
                    ...group,
                    parentKey,
                };
            });

            syncGroups(nextGroups);
        },
        [groups, syncGroups],
    );

    const handleUpdateChildren = useCallback(
        (index: number, children: Record<string, string>) => {
            const group = groups[index];
            if (!group || group.parentKey.trim() === "") {
                return;
            }

            const nextGroups = groups.map((item, groupIndex) => {
                if (groupIndex !== index) {
                    return item;
                }

                return {
                    ...item,
                    children,
                };
            });

            syncGroups(nextGroups);
        },
        [groups, syncGroups],
    );

    const handleToggleCollapse = useCallback(
        (index: number) => {
            const nextGroups = groups.map((group, groupIndex) => {
                if (groupIndex !== index) {
                    return group;
                }

                return {
                    ...group,
                    collapsed: !group.collapsed,
                };
            });

            setGroups(nextGroups);
        },
        [groups],
    );

    const handleRemoveParent = useCallback(
        (index: number) => {
            const nextGroups = groups.filter((_, groupIndex) => groupIndex !== index);
            syncGroups(nextGroups);
        },
        [groups, syncGroups],
    );

    const handleAddParent = useCallback(() => {
        if (!areAllParentGroupsComplete(groups)) {
            window._toastbox(
                "Vui lòng nhập đầy đủ key_parent và các cặp key/value trước khi thêm nhóm mới",
                "error",
            );
            return;
        }

        const duplicatedParent = groups.some((group, index) => {
            const parentKey = group.parentKey.trim();
            return parentKey !== "" && groups.findIndex((item) => item.parentKey.trim() === parentKey) !== index;
        });
        if (duplicatedParent) {
            window._toastbox("key_parent không được trùng nhau", "error");
            return;
        }

        syncGroups([...groups, createParentGroup()]);
    }, [groups, syncGroups]);

    const canAddParent = areAllParentGroupsComplete(groups);

    return (
        <div className="flex flex-col gap-3">
            {groups.map((group, index) => (
                <div key={group.id} className="flex flex-col gap-2 rounded-lg border border-gray-200 p-3">
                    <div className="flex w-full items-center gap-2">
                        <div className="min-w-0 flex-1">
                            <Input
                                value={group.parentKey}
                                placeholder="key_parent"
                                disabled={disabled}
                                onChange={(event) => handleUpdateParentKey(index, event.target.value)}
                            />
                        </div>
                        <Button
                            type="default"
                            className="shrink-0"
                            icon={group.collapsed ? <DownOutlined /> : <UpOutlined />}
                            disabled={disabled}
                            onClick={() => handleToggleCollapse(index)}
                        />
                        <Button
                            type="default"
                            className="shrink-0"
                            icon={<CloseOutlined />}
                            disabled={disabled || groups.length <= 1}
                            onClick={() => handleRemoveParent(index)}
                        />
                    </div>

                    {!group.collapsed ? (
                        <div className="ml-4 border-l border-gray-200 pl-4">
                            {group.parentKey.trim() === "" ? (
                                <p className="m-0 text-sm text-gray-400">
                                    Nhập key_parent trước khi thêm key/value con
                                </p>
                            ) : (
                                <ComponentEditRecord
                                    value={group.children}
                                    disabled={disabled}
                                    onChange={(children: Record<string, string>) => handleUpdateChildren(index, children)}
                                />
                            )}
                        </div>
                    ) : null}
                </div>
            ))}

            <Button
                type="dashed"
                block
                icon={<PlusOutlined />}
                disabled={disabled || !canAddParent}
                onClick={handleAddParent}
            />
        </div>
    );
});

ComponentEditRecordV2.displayName = "ComponentEditRecordV2";
