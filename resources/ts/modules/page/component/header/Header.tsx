import React, { useEffect } from "react";
import ReactDOM from "react-dom/client";
import { Navbars } from "./Navbars";

const Header = React.memo(function Header() {
    const nameUser = window.__AUTH__?.HoTen || "";
    return (
        <React.Fragment>
            <div className="bg-blue-400 p-2 flex justify-between items-center">
                <div>
                    <span className="">iPub - Hệ thống quản lý xuất bản</span>
                </div>
                <div className="pr-4">
                    <span className="">{nameUser} / thoát</span>
                </div>
            </div>
            <Navbars />
        </React.Fragment>
    );
});


function mount() {
    const el = document.getElementById("root-header");
    if (!el) return;
    ReactDOM.createRoot(el).render(<Header />);
}

if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", mount);
} else {
    mount();
}
