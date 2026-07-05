import React, { useCallback } from "react";
import { FileTextOutlined, PrinterOutlined, UnorderedListOutlined } from "@ant-design/icons";
import { useManageHDXBNXBGDVNStore, type HDXBNXBGDVNModalKey } from "../../store/HDXBNXBGDVN/manageHDXBNXBGDVN";

interface ToolbarAction {
    key: HDXBNXBGDVNModalKey;
    label: string;
    icon: React.ReactNode;
    requireSelection?: boolean;
}

const TOOLBAR_ACTIONS: ToolbarAction[] = [
    { key: "phanCongDocDuyet", label: "Phân công đọc duyệt", icon: <FileTextOutlined />, requireSelection: true },
    { key: "docDuyet", label: "Đọc duyệt", icon: <FileTextOutlined />, requireSelection: true },
    { key: "inPhieuTrinh", label: "In phiếu trình HĐXB NXBGDVN", icon: <PrinterOutlined />, requireSelection: true },
    { key: "xetDuyetDeTai", label: "Xét duyệt đề tài", icon: <UnorderedListOutlined />, requireSelection: true },
    { key: "pheDuyetDiIn", label: "Phê duyệt đi in", icon: <UnorderedListOutlined />, requireSelection: true },
];

function ActionToolbarHDXBNXBGDVN() {
    const listHDXBNXBGD = useManageHDXBNXBGDVNStore((state) => state.listHDXBNXBGD);
    const selectedRowKeys = useManageHDXBNXBGDVNStore((state) => state.selectedRowKeys);
    const openModalWithSelection = useManageHDXBNXBGDVNStore((state) => state.openModalWithSelection);
    const setActiveModal = useManageHDXBNXBGDVNStore((state) => state.setActiveModal);

    const handleAction = useCallback(
        (action: ToolbarAction) => {
            if (action.requireSelection) {
                openModalWithSelection(action.key, listHDXBNXBGD, selectedRowKeys);
                return;
            }
            setActiveModal(action.key);
        },
        [listHDXBNXBGD, openModalWithSelection, selectedRowKeys, setActiveModal],
    );

    return (
        <div className="d-flex flex-wrap align-items-center gap-3 py-2 px-2 border-bottom bg-white">
            {TOOLBAR_ACTIONS.map((action) => (
                <button
                    key={action.key}
                    type="button"
                    className="btn btn-link btn-sm text-primary p-0 d-inline-flex align-items-center gap-1 text-decoration-none"
                    onClick={() => handleAction(action)}
                >
                    {action.icon}
                    <span>{action.label}</span>
                </button>
            ))}
        </div>
    );
}

export default React.memo(ActionToolbarHDXBNXBGDVN);
