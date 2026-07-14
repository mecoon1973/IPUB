import { create } from "zustand";
import { defaultFilterPheDuyetDiIn, type FilterPheDuyetDiIn, type PheDuyetDiInRow } from "../../type";
import { defaultPagiInfo, type PagiInfo } from "../../../page/type";

export interface PheDuyetDiInState {
    listRows: PheDuyetDiInRow[];
    filter: FilterPheDuyetDiIn;
    pagiInfo: PagiInfo;
    isLoadingSearch: boolean;
    selectedRowKeys: (string | number)[];
}

interface PheDuyetDiInActions {
    setListRows: (rows: PheDuyetDiInRow[] | ((prev: PheDuyetDiInRow[]) => PheDuyetDiInRow[])) => void;
    setFilter: (filter: FilterPheDuyetDiIn | ((prev: FilterPheDuyetDiIn) => FilterPheDuyetDiIn)) => void;
    setPagiInfo: (pagiInfo: PagiInfo) => void;
    setIsLoadingSearch: (isLoadingSearch: boolean) => void;
    setSelectedRowKeys: (keys: (string | number)[]) => void;
    resetFilter: () => void;
}

export type PheDuyetDiInStore = PheDuyetDiInState & PheDuyetDiInActions;

const initialState: PheDuyetDiInState = {
    listRows: [],
    filter: defaultFilterPheDuyetDiIn,
    pagiInfo: defaultPagiInfo,
    isLoadingSearch: false,
    selectedRowKeys: [],
};

export const usePheDuyetDiInStore = create<PheDuyetDiInStore>((set) => ({
    ...initialState,

    setListRows: (listRows) => set((state) => ({
        listRows: typeof listRows === "function" ? listRows(state.listRows) : listRows,
    })),

    setFilter: (filter) => set((state) => ({
        filter: typeof filter === "function" ? filter(state.filter) : filter,
    })),

    setPagiInfo: (pagiInfo) => set({ pagiInfo }),

    setIsLoadingSearch: (isLoadingSearch) => set({ isLoadingSearch }),

    setSelectedRowKeys: (selectedRowKeys) => set({ selectedRowKeys }),

    resetFilter: () => set({ filter: defaultFilterPheDuyetDiIn }),
}));
