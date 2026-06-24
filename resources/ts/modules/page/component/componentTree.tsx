import React, { useCallback, useEffect, useMemo } from "react";
import { Checkbox, ConfigProvider, Radio, Tree } from "antd";
import type { TreeDataNode } from "antd";
import type { BaseTreeEntity, DataTree, TreeComponentProps } from "../../core/types/baseTreeEntity";
import { useOpenMenuNodeTree } from "../hooks/useHelperTreeNode";

interface IContextMenu<T> {
    open: boolean;
    x: number;
    y: number;
    record: T | null;
}

interface MenuTreeProps<T> {
    contextMenu: IContextMenu<T>;
    menuRef: React.RefObject<HTMLDivElement | null>;
    contextMenuItems: React.ReactNode[];
}

function MenuTreeInner<T>(props: MenuTreeProps<T>) {
    const { contextMenu, menuRef, contextMenuItems } = props;
    return (
        <div
            ref={menuRef}
            className="dropdown-menu show shadow"
            style={{
                position: "fixed",
                left: contextMenu.x,
                top: contextMenu.y,
                zIndex: 2000,
                minWidth: 220,
            }}
            role="menu"
        >
            {contextMenuItems.map((button, index) => (
                <React.Fragment key={`button-${index}`}>{button}</React.Fragment>
            ))}
        </div>
    );
}

const MenuTree = React.memo(MenuTreeInner) as typeof MenuTreeInner;

function ComponentTreeInner<T extends BaseTreeEntity>(props: TreeComponentProps<T>) {
    const {
        listData,
        getLabel,
        rootParentKey,
        rootParent,
        handlerChooseData,
        handlerSelectedId,
        selectedId,
        usingselectChoose,
        autoSelectChild,
        openByDefault,
        height,
        width,
        rowHeight,
        indent,
        contextMenuItems,
        onContextMenuRecordChange,
    } = props;

    const isSelectChoose = usingselectChoose ?? false;
    const isMultiSelect = Array.isArray(selectedId);
    const isAutoSelectChild = (autoSelectChild ?? false) && isMultiSelect;
    const mergedRowHeight = rowHeight ?? 30;
    const mergedIndent = indent ?? 24;
    const treeHeight = height ?? 500;

    const { activeNodeId, setActiveNodeId, contextMenu, setContextMenu, menuRef } = useOpenMenuNodeTree<T>();

    const activeVisualId = isSelectChoose
        ? isMultiSelect
            ? ""
            : String(selectedId ?? "")
        : String(activeNodeId ?? "");

    const selectedIdSet = useMemo(() => {
        if (!isMultiSelect || !selectedId) return null;
        return new Set(selectedId);
    }, [isMultiSelect, selectedId]);

    /** tạo cây dữ liệu cho tree */
    const dataTree: DataTree[] = useMemo(() => {
        const getNodeId = (row: T): string => String(row.id);

        const mapByParent = listData.reduce((acc, row) => {
            const parentKey = String(row.ParentID ?? 0);
            const group = acc[parentKey] ?? [];
            group.push(row);
            acc[parentKey] = group;
            return acc;
        }, {} as Record<string, T[]>);

        const buildTree = (parentKey: string): DataTree[] => {
            const children = mapByParent[parentKey] || [];
            return children.sort((a, b) => (a.ThuTu ?? 0) - (b.ThuTu ?? 0)).map((row) => {
                const nodeId = getNodeId(row);
                return {
                    id: nodeId,
                    name: getLabel(row),
                    children: buildTree(nodeId),
                };
            });
        };

        if (rootParent) {
            (rootParent as DataTree).children = buildTree(rootParentKey ?? "0");
            return [rootParent as DataTree];
        }

        return buildTree(rootParentKey ?? "0");
    }, [listData, getLabel, rootParentKey, rootParent]);

    /** lấy dữ liệu của node theo id */
    const rowById = useMemo(() => {
        const map = new Map<number, T>();
        for (const row of listData) {
            map.set(row.id, row);
        }
        return map;
    }, [listData]);

    /** lấy id của tất cả các node con theo id của node cha */
    const childrenIdsByParentId = useMemo(() => {
        const map = new Map<number, number[]>();
        for (const row of listData) {
            const parentId = row.ParentID ?? 0;
            const group = map.get(parentId) ?? [];
            group.push(row.id);
            map.set(parentId, group);
        }
        return map;
    }, [listData]);

    /** kiểm tra node đã chọn trong mode chọn đơn vị */
    const isNodeSelectedInChooseMode = useCallback((nodeId: number) => {
            if (isMultiSelect && selectedIdSet) {
                return selectedIdSet.has(nodeId);
            }
            return String(selectedId ?? 0) === String(nodeId);
        },
        [isMultiSelect, selectedIdSet, selectedId]);

    /** lấy id của tất cả các node con */
    const getDescendantIds = useCallback(
        (nodeId: number): number[] => {
            const result: number[] = [];
            const stack = [...(childrenIdsByParentId.get(nodeId) ?? [])];
            while (stack.length > 0) {
                const childId = stack.pop()!;
                result.push(childId);
                const grandchildren = childrenIdsByParentId.get(childId);
                if (grandchildren) {
                    stack.push(...grandchildren);
                }
            }
            return result;
        },
        [childrenIdsByParentId]
    );

    /** lấy id của node cha và tất cả các node con */
    const getIdsWithDescendants = useCallback(
        (nodeId: number): number[] => [nodeId, ...getDescendantIds(nodeId)],
        [getDescendantIds]
    );

    /** xử lý sự kiện chọn node */
    const handleChooseRow = useCallback(
        (id: number) => {
            const row = listData.find((d) => d.id === id);
            if (row) {
                handlerChooseData?.(row);
            }
        },
        [handlerChooseData, listData]
    );

    const handleSelectedIdCb = useCallback(
        (nodeId: number) => {
            if (!isSelectChoose) return;

            if (isAutoSelectChild) {
                const current = new Set(selectedId as number[]);
                const affectedIds = getIdsWithDescendants(nodeId);
                const isChecked = current.has(nodeId);

                if (isChecked) {
                    affectedIds.forEach((id) => current.delete(id));
                } else {
                    affectedIds.forEach((id) => current.add(id));
                }
                handlerSelectedId?.(Array.from(current));
                return;
            }

            handlerSelectedId?.(nodeId);
        },
        [handlerSelectedId, isSelectChoose, isAutoSelectChild, selectedId, getIdsWithDescendants]
    );

    /** tạo cây dữ liệu cho tree */
    const treeData = useMemo((): TreeDataNode[] => {
        const mapNodes = (nodes: DataTree[]): TreeDataNode[] =>
            nodes.map((dt) => {
                const hasChildren = dt.children.length > 0;
                const nodeKeyStr = String(dt.id);
                const nodeId = Number(dt.id);
                const isActive =
                    nodeKeyStr !== "" &&
                    (isSelectChoose
                        ? isNodeSelectedInChooseMode(nodeId)
                        : nodeKeyStr === activeVisualId);

                const titleNode = isSelectChoose ? (
                    <div
                        className="d-flex align-items-center gap-2 py-1 w-100"
                        role="presentation"
                        onClick={(e) => e.stopPropagation()}
                    >
                        {isMultiSelect ? (
                            <Checkbox
                                className="m-0 shrink-0"
                                checked={isNodeSelectedInChooseMode(nodeId)}
                                onChange={() => handleSelectedIdCb(nodeId)}
                            />
                        ) : (
                            <Radio
                                className="m-0 shrink-0"
                                checked={isNodeSelectedInChooseMode(nodeId)}
                                onChange={() => handleSelectedIdCb(nodeId)}
                            />
                        )}
                        <span
                            className="text-break lh-sm small grow"
                            style={{ cursor: "pointer" }}
                            onClick={() => handleSelectedIdCb(nodeId)}
                        >
                            {dt.name}
                        </span>
                    </div>
                ) : (
                    <div className="py-1 border-bottom border-light">
                        <span className="text-break lh-sm small">{dt.name}</span>
                    </div>
                );

                const node: TreeDataNode = {
                    key: dt.id,
                    title: titleNode,
                };
                if (isActive) {
                    node.className = "bg-primary bg-opacity-10";
                }
                if (hasChildren) {
                    node.children = mapNodes(dt.children);
                }
                return node;
            });

        return mapNodes(dataTree);
    }, [
        dataTree,
        isSelectChoose,
        isMultiSelect,
        activeVisualId,
        handleSelectedIdCb,
        isNodeSelectedInChooseMode,
    ]);

    /** xử lý sự kiện mở menu context */
    useEffect(() => {
        if (!onContextMenuRecordChange) return;
        onContextMenuRecordChange(contextMenu.open ? (contextMenu.record ?? null) : null);
    }, [contextMenu.open, contextMenu.record, onContextMenuRecordChange]);

    return (
        <div className="px-2 position-relative" style={{ width: `${width ?? 100}%` }}>
            <ConfigProvider
                theme={{
                    components: {
                        Tree: {
                            indentSize: mergedIndent,
                            titleHeight: mergedRowHeight,
                        },
                    },
                }}
            >
                <Tree
                    treeData={treeData}
                    height={treeHeight}
                    itemHeight={mergedRowHeight}
                    virtual
                    blockNode
                    showLine={true}
                    showIcon={false}
                    selectable={!isSelectChoose}
                    multiple={false}
                    defaultExpandAll={openByDefault ?? true}
                    selectedKeys={
                        isSelectChoose
                            ? []
                            : activeVisualId
                              ? [activeVisualId]
                              : []
                    }
                    onSelect={(selectedKeys, info) => {
                        if (isSelectChoose) return;
                        if (info.selected === false) {
                            return;
                        }
                        const k = selectedKeys[0];
                        if (k === undefined || k === null) return;
                        setActiveNodeId(String(k));
                        handleChooseRow(Number(k));
                    }}
                    onRightClick={({ event, node }) => {
                        event.preventDefault();
                        const id = Number(node.key);
                        const row = rowById.get(id) ?? null;
                        if (!row) return;

                        if (!isSelectChoose) {
                            setActiveNodeId(String(node.key));
                            handlerChooseData?.(row);
                        }

                        setContextMenu({
                            open: true,
                            x: event.clientX,
                            y: event.clientY,
                            record: row,
                        });
                    }}
                />
            </ConfigProvider>

            {contextMenu.open && contextMenu.record != null && !isSelectChoose ? (
                <MenuTree
                    contextMenu={contextMenu}
                    menuRef={menuRef}
                    contextMenuItems={contextMenuItems ?? []}
                />
            ) : null}
        </div>
    );
}

/** Cây phân cấp generic — truyền `getLabel` và (tuỳ chọn) `rootParentKey` theo từng model. */
const ComponentTree = React.memo(ComponentTreeInner) as <T extends BaseTreeEntity>(
    props: TreeComponentProps<T>
) => React.ReactElement;

export default ComponentTree;
