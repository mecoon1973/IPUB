import ReactDOM from 'react-dom/client';
/**
 * Các hàm hỗ trợ dùng chung (helpers / utilities).
 */

/**
 * Hàm mount component React vào DOM
 * @param id - id của element trong DOM
 * @param component - component React cần mount
 */
export function mountReactComponent(id: string, component: React.ReactNode) {
    const el = document.getElementById(id);
    if (!el) return;

    if (import.meta.hot) {
        const data = import.meta.hot.data as { root?: ReturnType<typeof ReactDOM.createRoot> };
        data.root ??= ReactDOM.createRoot(el);
        data.root.render(component);
    } else {
        ReactDOM.createRoot(el).render(component);
    }
}

/**
 * Hàm mount component React vào DOM khi DOM đã sẵn sàng
 * @param id - id của element trong DOM
 * @param component - component React cần mount
 */
export function mountReactComponentOnReady(id: string, component: React.ReactNode) {
    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", () => mountReactComponent(id, component));
    } else {
        mountReactComponent(id, component);
    }
}

/**
 * Đọc JSON từ thuộc tính `data-props` trên phần tử `#rootId` (chuỗi do Blade `@json` / `json_encode` ghi ra).
 * Dùng để truyền cấu hình từ Laravel Blade vào entry React.
 */
export function readRootDataProps<T>(rootId: string): T | null {
    const el = document.getElementById(rootId);
    if (!el) {
        return null;
    }
    const raw = el.getAttribute("data-props");
    if (raw == null || raw === "") {
        return null;
    }
    try {
        return JSON.parse(raw);
    } catch {
        console.warn(`[readRootDataProps] JSON không hợp lệ cho #${rootId}`);
        return null;
    }
}

