import { create } from "zustand";
import dayjs from "dayjs";
import {
    defaultFilterPhieuChuyenBanThao,
    type FilterPhieuChuyenBanThao,
    type PhieuChuyenBanThao,
} from "../../type";
import { defaultPagiInfo, type PagiInfo } from "../../../page/type";

export interface ManagePhieuChuyenBanThaoState {
    listPhieuChuyenBanThao: PhieuChuyenBanThao[];
    filter: FilterPhieuChuyenBanThao;
    pagiInfo: PagiInfo;
    isLoadingSearch: boolean;
}

interface ManagePhieuChuyenBanThaoActions {
    setListPhieuChuyenBanThao: (
        list: PhieuChuyenBanThao[] | ((prev: PhieuChuyenBanThao[]) => PhieuChuyenBanThao[]),
    ) => void;
    setFilter: (
        filter: FilterPhieuChuyenBanThao | ((prev: FilterPhieuChuyenBanThao) => FilterPhieuChuyenBanThao),
    ) => void;
    setPagiInfo: (pagiInfo: PagiInfo) => void;
    setIsLoadingSearch: (isLoadingSearch: boolean) => void;
    resetFilter: () => void;
}

export type ManagePhieuChuyenBanThaoStore = ManagePhieuChuyenBanThaoState & ManagePhieuChuyenBanThaoActions;

const initialFilter: FilterPhieuChuyenBanThao = {
    ...defaultFilterPhieuChuyenBanThao,
    startDate: dayjs().subtract(1, "month").startOf("day").toDate(),
    endDate: dayjs().endOf("day").toDate(),
};

export const useManagePhieuChuyenBanThaoStore = create<ManagePhieuChuyenBanThaoStore>((set) => ({
    listPhieuChuyenBanThao: [],
    filter: initialFilter,
    pagiInfo: defaultPagiInfo,
    isLoadingSearch: false,

    setListPhieuChuyenBanThao: (listPhieuChuyenBanThao) => set((state) => ({
        listPhieuChuyenBanThao: typeof listPhieuChuyenBanThao === "function"
            ? listPhieuChuyenBanThao(state.listPhieuChuyenBanThao)
            : listPhieuChuyenBanThao,
    })),

    setFilter: (filter) => set((state) => ({
        filter: typeof filter === "function" ? filter(state.filter) : filter,
    })),

    setPagiInfo: (pagiInfo) => set({ pagiInfo }),

    setIsLoadingSearch: (isLoadingSearch) => set({ isLoadingSearch }),

    resetFilter: () => set({ filter: initialFilter }),
}));
