import { create } from "zustand";
import type { DonVi } from "../../../user/type/DonVi";
import type { User } from "../../../user/type/User";
import { defaultPagiInfo, type PagiInfo } from "../../../page/type";
import type Nhom from "../../type/Nhom";

export interface ManageCanboInNhomState {
    listCanbo: User[];
    listDonvi: DonVi[];
    pagiInfo: PagiInfo;
    selectedDonvi: DonVi | null;
    usernameSearch: string;
    selectedCanbo: User | null;
    nhom: Nhom | null;
}

interface ManageCanboInNhomActions {
    setData: (data: Partial<ManageCanboInNhomState>) => void;
    setListCanbo: (listCanbo: User[] | ((prev: User[]) => User[])) => void;
    setPagiInfo: (pagiInfo: PagiInfo | ((prev: PagiInfo) => PagiInfo)) => void;
    setListDonvi: (listDonvi: DonVi[] | ((prev: DonVi[]) => DonVi[])) => void;
    setUsernameSearch: (usernameSearch: string) => void;
    setSelectedCanbo: (selectedCanbo: User | null) => void;
    setSelectedDonvi: (selectedDonvi: DonVi | null) => void;
    setNhom: (nhom: Nhom | null) => void;
}

export type ManageCanboInNhomActionsStore = ManageCanboInNhomState & ManageCanboInNhomActions;

const initialState: ManageCanboInNhomState = {
    nhom: null,
    listCanbo: [],
    listDonvi: [],
    pagiInfo: defaultPagiInfo,
    usernameSearch: "",
    selectedCanbo: null,
    selectedDonvi: null,
};

export const useManageCanboInNhomStore = create<ManageCanboInNhomActionsStore>((set, get) => ({
    ...initialState,
    setData: (data: Partial<ManageCanboInNhomState>) => set((prev) => ({ ...prev, ...data })),
    setListCanbo: (listCanbo: User[] | ((prev: User[]) => User[])) =>
        set((state) => ({
            listCanbo: typeof listCanbo === "function" ? listCanbo(state.listCanbo) : listCanbo,
        })),
    setListDonvi: (listDonvi: DonVi[] | ((prev: DonVi[]) => DonVi[])) =>
        set((state) => ({
            listDonvi: typeof listDonvi === "function" ? listDonvi(state.listDonvi) : listDonvi,
        })),
    setPagiInfo: (pagiInfo: PagiInfo | ((prev: PagiInfo) => PagiInfo)) =>
        set((state) => ({
            pagiInfo: typeof pagiInfo === "function" ? pagiInfo(state.pagiInfo) : pagiInfo,
        })),
    setUsernameSearch: (usernameSearch: string) => set({ usernameSearch }),
    setSelectedCanbo: (selectedCanbo: User | null) => set({ selectedCanbo }),
    setSelectedDonvi: (selectedDonvi: DonVi | null) => set({ selectedDonvi }),
    setNhom: (nhom: Nhom | null) => set({ nhom }),
}));
