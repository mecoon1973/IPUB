import { create } from "zustand";
import type { DonVi } from "../../../user/type";

export interface ManageDonviState {
    listDonvi: DonVi[];
    textSearch: string;
    selectedDonvi: DonVi | null;
    filterNoiBo: boolean;
    filterNhaIn: boolean;
    filterLienKet: boolean;
}

interface ManageDonviActions {
    setData: (data: Partial<ManageDonviState>) => void;
    setListDonvi: (listDonvi: DonVi[] | ((prev: DonVi[]) => DonVi[])) => void;
    setFilterNoiBo: (filterNoiBo: boolean) => void;
    setFilterNhaIn: (filterNhaIn: boolean) => void;
    setFilterLienKet: (filterLienKet: boolean) => void;
    setSelectedDonvi: (selectedDonvi: DonVi|null) => void;
    setTextSearch: (textSearch: string) => void;
}

export type ManageDonviActionsStore = ManageDonviState & ManageDonviActions;

const initialState: ManageDonviState = {
    listDonvi : [],
    selectedDonvi : null,
    filterNoiBo : true,
    filterNhaIn : false,
    filterLienKet : false,
    textSearch : "",
};

export const useManageDonviStore = create<ManageDonviActionsStore>((set, get) => ({
    ...initialState,

    setData: (data: Partial<ManageDonviState>) => set((prev) => ({ ...prev, ...data })),
    setListDonvi: (listDonvi: DonVi[] | ((prev: DonVi[]) => DonVi[])) =>
        set((state) => ({
            listDonvi: typeof listDonvi === "function" ? listDonvi(state.listDonvi) : listDonvi,
        })),
    setFilterNoiBo: (filterNoiBo: boolean) => set({ filterNoiBo }),
    setFilterNhaIn: (filterNhaIn: boolean) => set({ filterNhaIn }),
    setFilterLienKet: (filterLienKet: boolean) => set({ filterLienKet }),
    setSelectedDonvi: (selectedDonvi: DonVi|null) => set({ selectedDonvi }),
    setTextSearch: (textSearch: string) => set({ textSearch }),
}));
