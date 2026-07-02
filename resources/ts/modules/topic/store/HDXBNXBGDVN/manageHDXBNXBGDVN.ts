import { create } from "zustand";
import { defaultFilterHDXBNXBGDVN, type FilterHDXBNXBGDVN, type HDXBNXBGDVN } from "../../type";
import { defaultPagiInfo, type PagiInfo } from "../../../page/type";

export interface ManageHDXBNXBGDVNState {
    /** list Phiếu đăng ký đề tài  */
    listHDXBNXBGD: HDXBNXBGDVN[];
    /** filter truy vấn Phiếu đăng ký đề tài */
    filter: FilterHDXBNXBGDVN;
    /** pagiInfo */
    pagiInfo: PagiInfo;
    /** isLoadingSearch */
    isLoadingSearch: boolean;
}

interface ManageHDXBNXBGDVNActions {
    setData: (data: Partial<ManageHDXBNXBGDVNState>) => void;
    setListHDXBNXBGD: (listHDXBNXBGD: HDXBNXBGDVN[] | ((prev: HDXBNXBGDVN[]) => HDXBNXBGDVN[])) => void;
    setFilter: (filter: FilterHDXBNXBGDVN | ((prev: FilterHDXBNXBGDVN) => FilterHDXBNXBGDVN)) => void;
    setPagiInfo: (pagiInfo: PagiInfo) => void;
    resetFilter: () => void;
    setIsLoadingSearch: (isLoadingSearch: boolean) => void;
}

export type ManageHDXBNXBGDVNStore = ManageHDXBNXBGDVNState & ManageHDXBNXBGDVNActions;

const initialState: ManageHDXBNXBGDVNState = {
    listHDXBNXBGD : [],
    filter : defaultFilterHDXBNXBGDVN,
    pagiInfo : defaultPagiInfo,
    isLoadingSearch : false,
};

export const useManageHDXBNXBGDVNStore = create<ManageHDXBNXBGDVNStore>((set, get) => ({
    ...initialState,

    setData: (data: Partial<ManageHDXBNXBGDVNState>) => set((prev) => ({ ...prev, ...data })),

    setListHDXBNXBGD: (listHDXBNXBGD: HDXBNXBGDVN[] | ((prev: HDXBNXBGDVN[]) => HDXBNXBGDVN[])) => set((state) => ({
        listHDXBNXBGD: typeof listHDXBNXBGD === "function" ? listHDXBNXBGD(state.listHDXBNXBGD) : listHDXBNXBGD,
    })),

    setFilter: (filter: FilterHDXBNXBGDVN | ((prev: FilterHDXBNXBGDVN) => FilterHDXBNXBGDVN)) => set((state) => ({
        filter: typeof filter === "function" ? filter(state.filter) : filter,
    })),

    setPagiInfo: (pagiInfo: PagiInfo) => set({ pagiInfo }),

    resetFilter: () => set({ filter: defaultFilterHDXBNXBGDVN }),

    setIsLoadingSearch: (isLoadingSearch: boolean) => set({ isLoadingSearch }),
}));
