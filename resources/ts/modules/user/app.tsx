import "../../_setup";
import React, { useEffect } from "react";
import ReactDOM from "react-dom/client";

/**
 * Component React nhúng trong Blade.
 * Nhận props từ data-* của thẻ chứa (ví dụ data-title).
 */
type ReactWidgetProps = {
    title?: string;
};

function ReactWidget({ title }: ReactWidgetProps) {
    useEffect(() => {
        window._apiGet("https://api.example.com/data", {}).then((res) => {
            console.log(res);
        });
    }, []);

    return (
        <div className="react-widget">
            <h2>{title ?? ""}</h2>
        </div>
    );
}

function mount() {
    const el = document.getElementById("react-root");
    if (!el) return;
    const title = el.getAttribute("data-title");
    const props = title ? { title } : {};
    ReactDOM.createRoot(el).render(<ReactWidget {...props} />);
}

if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", mount);
} else {
    mount();
}
