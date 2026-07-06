import { create } from "zustand";
import { defaultFilterPhieuDkDetai, type FilterPhieuDkDetai, type PhieuDkDetai } from "../../type/PhieuDkDetai";

export interface PhieuDkDetaiStoreState {
    /** filter */
    filter: FilterPhieuDkDetai;
    /** isLoadingSearch */
    isLoadingSearch: boolean;
    /** isLoadingSearch */
    showModalChonDeTai: boolean;
    /** isLoadingSearch */
    showModalChonMangSach: boolean;
    /** list DetaiTaiBan */
    listDetaiTaiBan: PhieuDkDetai[];

    yearTaiBan: string;
    idDonvi: number;
}

interface PhieuDkDetaiStoreActions {
    setData: (data: Partial<PhieuDkDetaiStoreState>) => void;
    setListDetaiTaiBan: (listDetaiTaiBan: PhieuDkDetai[] | ((prev: PhieuDkDetai[]) => PhieuDkDetai[])) => void;
    setFilter: (filter: FilterPhieuDkDetai | ((prev: FilterPhieuDkDetai) => FilterPhieuDkDetai)) => void;
    setShowModalChonDeTai: (showModalChonDeTai: boolean) => void;
    setShowModalChonMangSach: (showModalChonMangSach: boolean) => void;
    setYearTaiBan: (yearTaiBan: string) => void;
    setIdDonvi: (idDonvi: number) => void;
    setIsLoadingSearch: (isLoadingSearch: boolean) => void;
}

export type PhieuDkDetaiStore = PhieuDkDetaiStoreState & PhieuDkDetaiStoreActions;

const initialState: PhieuDkDetaiStoreState = {
    filter : defaultFilterPhieuDkDetai,
    isLoadingSearch : false,
    listDetaiTaiBan : [],
    showModalChonDeTai : false,
    showModalChonMangSach : false,
    yearTaiBan : "",
    idDonvi : 0,
}

export const usePhieuDkDetaiStore = create<PhieuDkDetaiStore>((set, get) => ({
    ...initialState,

    setData: (data: Partial<PhieuDkDetaiStoreState>) => set((prev) => ({ ...prev, ...data })),

    setListDetaiTaiBan: (listDetaiTaiBan: PhieuDkDetai[] | ((prev: PhieuDkDetai[]) => PhieuDkDetai[])) => set((state) => ({
        listDetaiTaiBan: typeof listDetaiTaiBan === "function" ? listDetaiTaiBan(state.listDetaiTaiBan) : listDetaiTaiBan,
    })),

    setFilter: (filter: FilterPhieuDkDetai | ((prev: FilterPhieuDkDetai) => FilterPhieuDkDetai)) => set((state) => ({
        filter: typeof filter === "function" ? filter(state.filter) : filter,
    })),

    setShowModalChonDeTai: (showModalChonDeTai: boolean) => set({ showModalChonDeTai }),
    setShowModalChonMangSach: (showModalChonMangSach: boolean) => set({ showModalChonMangSach }),
    setYearTaiBan: (yearTaiBan: string) => set({ yearTaiBan }),
    setIdDonvi: (idDonvi: number) => set({ idDonvi }),
    setIsLoadingSearch: (isLoadingSearch: boolean) => set({ isLoadingSearch }),
}));
