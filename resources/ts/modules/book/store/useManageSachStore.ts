import { create } from "zustand";
import type { FilterSach, Sach } from "../type/Sach";
import { defaultPagiInfo, type PagiInfo } from "../../page/type";

export interface ManageSachState {
    listSach: Sach[];
    sachSelected: Sach | null;
    isLoadingSearch: boolean;
    openModalChangeKey: boolean;
    openModalUpdateCount: boolean;
    openModalUpdateLicense: boolean;
    openModalUpdatePrice: boolean;
    openModalInfo: boolean;
    pagiInfo: PagiInfo;
    filterSach: FilterSach;
}

interface ManageSachActions {
    setData: (data: Partial<ManageSachState>) => void;
    setListSach: (listSach: Sach[] | ((prev: Sach[]) => Sach[])) => void;
    setPagiInfo: (pagiInfo: PagiInfo | ((prev: PagiInfo) => PagiInfo)) => void;
    setSachSelected: (sachSelected: Sach | null) => void;
    setFilterSach: (filter: FilterSach | ((prev: FilterSach) => FilterSach)) => void;
    resetFilter: () => void;
    setOpenModalChangeKey: (openModalChangeKey: boolean) => void;
    setOpenModalUpdateCount: (openModalUpdateCount: boolean) => void;
    setOpenModalUpdateLicense: (openModalUpdateLicense: boolean) => void;
    setOpenModalUpdatePrice: (openModalUpdatePrice: boolean) => void;
    setOpenModalInfo: (openModalInfo: boolean) => void;
}

export type ManageSachActionsStore = ManageSachState & ManageSachActions;

const initialState: ManageSachState = {
    listSach: [],
    sachSelected: null,
    isLoadingSearch: false,
    pagiInfo: defaultPagiInfo,
    filterSach: {
        title: "",
        ID_MangSach: 0,
        ID_DonVi: 0,
        NamXuatBan: "",
        NamTaiBan: "",
        HTXB: -1,
        NgayDK: [],
        IsDeleted: false,
        relations: ["don_vi", "mang_sach"],
    },
    openModalChangeKey: false,
    openModalUpdateCount: false,
    openModalUpdateLicense: false,
    openModalUpdatePrice: false,
    openModalInfo: false,
};

export const useManageSachStore = create<ManageSachActionsStore>((set, get) => ({
    ...initialState,

    setData: (data: Partial<ManageSachState>) => set((prev) => ({ ...prev, ...data })),
    setListSach: (listSach: Sach[] | ((prev: Sach[]) => Sach[])) => set((state) => ({
        listSach: typeof listSach === "function" ? listSach(state.listSach) : listSach,
    })),
    setPagiInfo: (pagiInfo: PagiInfo | ((prev: PagiInfo) => PagiInfo)) => set((state) => ({
        pagiInfo: typeof pagiInfo === "function" ? pagiInfo(state.pagiInfo) : pagiInfo,
    })),
    setSachSelected: (sachSelected: Sach | null) => set({ sachSelected }),
    setFilterSach: (filter: FilterSach | ((prev: FilterSach) => FilterSach)) => set((state) => ({
        filterSach: typeof filter === "function" ? filter(state.filterSach) : filter,
    })),
    resetFilter: () => set({ filterSach: initialState.filterSach }),
    setOpenModalChangeKey: (openModalChangeKey: boolean) => set({ openModalChangeKey }),
    setOpenModalUpdateCount: (openModalUpdateCount: boolean) => set({ openModalUpdateCount }),
    setOpenModalUpdateLicense: (openModalUpdateLicense: boolean) => set({ openModalUpdateLicense }),
    setOpenModalUpdatePrice: (openModalUpdatePrice: boolean) => set({ openModalUpdatePrice }),
    setOpenModalInfo: (openModalInfo: boolean) => set({ openModalInfo }),
}));
