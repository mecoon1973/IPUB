import { useEffect, useState, useCallback, useRef } from "react";

/** Tên sự kiện global — dùng với `dispatchTreeContextMenu`. */
export const TREE_CONTEXT_MENU_EVENT = "close-tree-context-menu";

export type TreeContextMenuEventDetail = {
    /** Mặc định: đóng menu context cây. */
    type?: "close";
};

/** Phát sự kiện để hook cây lắng nghe (vd: đóng menu từ modal / store). */
export function dispatchTreeContextMenu(detail: TreeContextMenuEventDetail = { type: "close" }) {
    window.dispatchEvent(new CustomEvent(TREE_CONTEXT_MENU_EVENT, { detail }));
}

export interface IContextMenu<T> {
    open: boolean;
    x: number;
    y: number;
    record: T | null;
}

/** hook để mở menu context cho node trong tree */
export function useOpenMenuNodeTree<T>() {
    const [activeNodeId, setActiveNodeId] = useState<string | null>(null);
    const menuRef = useRef<HTMLDivElement | null>(null);
    const [contextMenu, setContextMenu] = useState<IContextMenu<T>>({ open: false, x: 0, y: 0, record: null });

    const closeContextMenu = useCallback(() => {
        setContextMenu({ open: false, x: 0, y: 0, record: null });
    }, []);

    /** Lắng nghe `dispatchTreeContextMenu` để đóng hoặc mở context*/
    useEffect(() => {
        const onDispatch = (e: Event) => {
            const ce = e as CustomEvent<TreeContextMenuEventDetail>;
            const t = ce.detail?.type ?? "close";
            if (t === "close") {
                closeContextMenu();
            }
        };
        window.addEventListener(TREE_CONTEXT_MENU_EVENT, onDispatch);
        return () => window.removeEventListener(TREE_CONTEXT_MENU_EVENT, onDispatch);
    }, [closeContextMenu]);

    useEffect(() => {
        if (!contextMenu.open) return;

        const onMouseDown = (e: MouseEvent) => {
            const el = menuRef.current;
            if (el && e.target instanceof Node && el.contains(e.target)) return;
            closeContextMenu();
        };
        const onKeyDown = (e: KeyboardEvent) => {
            if (e.key === "Escape") {
                closeContextMenu();
            }
        };
        window.addEventListener("mousedown", onMouseDown);
        window.addEventListener("keydown", onKeyDown);
        return () => {
            window.removeEventListener("mousedown", onMouseDown);
            window.removeEventListener("keydown", onKeyDown);
        };
    }, [contextMenu.open, closeContextMenu]);

    return {
        activeNodeId,
        setActiveNodeId,
        contextMenu,
        setContextMenu,
        closeContextMenu,
        menuRef
    };
}
