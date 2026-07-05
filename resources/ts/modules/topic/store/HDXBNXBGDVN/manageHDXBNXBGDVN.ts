import { create } from "zustand";
import { defaultFilterHDXBNXBGDVN, type FilterHDXBNXBGDVN, type HDXBNXBGDVN } from "../../type";
import { defaultPagiInfo, type PagiInfo } from "../../../page/type";

export type HDXBNXBGDVNModalKey =
    | "phanCongDocDuyet"
    | "docDuyet"
    | "inPhieuTrinh"
    | "xetDuyetDeTai"
    | "pheDuyetDiIn";

export interface ManageHDXBNXBGDVNState {
    listHDXBNXBGD: HDXBNXBGDVN[];
    filter: FilterHDXBNXBGDVN;
    pagiInfo: PagiInfo;
    isLoadingSearch: boolean;
    selectedRowKeys: (string | number)[];
    activeModal: HDXBNXBGDVNModalKey | null;
    /** Danh sách đề tài trong modal phân công đọc duyệt */
    phanCongItems: HDXBNXBGDVN[];
}

interface ManageHDXBNXBGDVNActions {
    setData: (data: Partial<ManageHDXBNXBGDVNState>) => void;
    setListHDXBNXBGD: (listHDXBNXBGD: HDXBNXBGDVN[] | ((prev: HDXBNXBGDVN[]) => HDXBNXBGDVN[])) => void;
    setFilter: (filter: FilterHDXBNXBGDVN | ((prev: FilterHDXBNXBGDVN) => FilterHDXBNXBGDVN)) => void;
    setPagiInfo: (pagiInfo: PagiInfo) => void;
    resetFilter: () => void;
    setIsLoadingSearch: (isLoadingSearch: boolean) => void;
    setSelectedRowKeys: (keys: (string | number)[]) => void;
    setActiveModal: (modal: HDXBNXBGDVNModalKey | null) => void;
    setPhanCongItems: (items: HDXBNXBGDVN[] | ((prev: HDXBNXBGDVN[]) => HDXBNXBGDVN[])) => void;
    openModalWithSelection: (modal: HDXBNXBGDVNModalKey, listHDXBNXBGD: HDXBNXBGDVN[], selectedRowKeys: (string | number)[]) => boolean;
    openModalForItem: (modal: HDXBNXBGDVNModalKey, item: HDXBNXBGDVN) => void;
}

export type ManageHDXBNXBGDVNStore = ManageHDXBNXBGDVNState & ManageHDXBNXBGDVNActions;

const initialState: ManageHDXBNXBGDVNState = {
    listHDXBNXBGD: [],
    filter: defaultFilterHDXBNXBGDVN,
    pagiInfo: defaultPagiInfo,
    isLoadingSearch: false,
    selectedRowKeys: [],
    activeModal: null,
    phanCongItems: [],
};

export const useManageHDXBNXBGDVNStore = create<ManageHDXBNXBGDVNStore>((set, get) => ({
    ...initialState,

    setData: (data: Partial<ManageHDXBNXBGDVNState>) => set((prev) => ({ ...prev, ...data })),

    setListHDXBNXBGD: (listHDXBNXBGD) => set((state) => ({
        listHDXBNXBGD: typeof listHDXBNXBGD === "function" ? listHDXBNXBGD(state.listHDXBNXBGD) : listHDXBNXBGD,
    })),

    setFilter: (filter) => set((state) => ({
        filter: typeof filter === "function" ? filter(state.filter) : filter,
    })),

    setPagiInfo: (pagiInfo) => set({ pagiInfo }),

    resetFilter: () => set({ filter: defaultFilterHDXBNXBGDVN }),

    setIsLoadingSearch: (isLoadingSearch) => set({ isLoadingSearch }),

    setSelectedRowKeys: (selectedRowKeys) => set({ selectedRowKeys }),

    setActiveModal: (activeModal) => set({ activeModal }),

    setPhanCongItems: (phanCongItems) => set((state) => ({
        phanCongItems: typeof phanCongItems === "function" ? phanCongItems(state.phanCongItems) : phanCongItems,
    })),

    openModalWithSelection: (modal, listHDXBNXBGD, selectedRowKeys) => {
        const selectedItems = listHDXBNXBGD.filter((item) => selectedRowKeys.includes(String(item.id)));
        if (selectedItems.length === 0) {
            window._toastbox("Vui lòng chọn ít nhất một đề tài", "danger");
            return false;
        }
        set({
            activeModal: modal,
            selectedRowKeys: selectedItems.map((item) => String(item.id)),
            phanCongItems: modal === "phanCongDocDuyet" ? selectedItems : get().phanCongItems,
        });
        return true;
    },

    openModalForItem: (modal, item) => {
        set({
            activeModal: modal,
            selectedRowKeys: [String(item.id)],
            phanCongItems: modal === "phanCongDocDuyet" ? [item] : get().phanCongItems,
        });
    },
}));
