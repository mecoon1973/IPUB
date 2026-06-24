import { create } from "zustand";
import type { DonVi } from "../../user/type";
import type { Bosach, Doituong, Lop, Mangsach, Monhoc, Tusach } from "../type";


export interface DataViewState {
    listDonvi: DonVi[];
    listMangsach: Mangsach[];
    listDoituong: Doituong[];
    listLop: Lop[];
    listMonhoc: Monhoc[];
    listBosach: Bosach[];
    listTusach: Tusach[];
    mapTrangThai: Record<number, string>;
}

interface DataViewActions {
    setData: (data: Partial<DataViewState>) => void;
    setListDonvi: (listDonvi: DonVi[] | ((prev: DonVi[]) => DonVi[])) => void;
    setListMangsach: (listMangsach: Mangsach[] | ((prev: Mangsach[]) => Mangsach[])) => void;
    setListDoituong: (listDoituong: Doituong[] | ((prev: Doituong[]) => Doituong[])) => void;
    setListLop: (listLop: Lop[] | ((prev: Lop[]) => Lop[])) => void;
    setListMonhoc: (listMonhoc: Monhoc[] | ((prev: Monhoc[]) => Monhoc[])) => void;
    setListBosach: (listBosach: Bosach[] | ((prev: Bosach[]) => Bosach[])) => void;
    setListTusach: (listTusach: Tusach[] | ((prev: Tusach[]) => Tusach[])) => void;
    setMapTrangThai: (mapTrangThai: Record<number, string> | ((prev: Record<number, string>) => Record<number, string>)) => void;
}


export type DataViewActionsStore = DataViewState & DataViewActions;

const initialState: DataViewState = {
    listDonvi: [],
    listMangsach: [],
    listDoituong: [],
    listLop: [],
    listMonhoc: [],
    listBosach: [],
    listTusach: [],
    mapTrangThai: {},
};

export const useDataViewStore = create<DataViewActionsStore>((set, get) => ({
    ...initialState,
    setData: (data: Partial<DataViewState>) => set((prev) => ({ ...prev, ...data })),
    setListDonvi: (listDonvi: DonVi[] | ((prev: DonVi[]) => DonVi[])) => set((state) => ({ listDonvi: typeof listDonvi === "function" ? listDonvi(state.listDonvi) : listDonvi })),
    setListMangsach: (listMangsach: Mangsach[] | ((prev: Mangsach[]) => Mangsach[])) => set((state) => ({ listMangsach: typeof listMangsach === "function" ? listMangsach(state.listMangsach) : listMangsach })),
    setListDoituong: (listDoituong: Doituong[] | ((prev: Doituong[]) => Doituong[])) => set((state) => ({ listDoituong: typeof listDoituong === "function" ? listDoituong(state.listDoituong) : listDoituong })),
    setListLop: (listLop: Lop[] | ((prev: Lop[]) => Lop[])) => set((state) => ({ listLop: typeof listLop === "function" ? listLop(state.listLop) : listLop })),
    setListMonhoc: (listMonhoc: Monhoc[] | ((prev: Monhoc[]) => Monhoc[])) => set((state) => ({ listMonhoc: typeof listMonhoc === "function" ? listMonhoc(state.listMonhoc) : listMonhoc })),
    setListBosach: (listBosach: Bosach[] | ((prev: Bosach[]) => Bosach[])) => set((state) => ({ listBosach: typeof listBosach === "function" ? listBosach(state.listBosach) : listBosach })),
    setListTusach: (listTusach: Tusach[] | ((prev: Tusach[]) => Tusach[])) => set((state) => ({ listTusach: typeof listTusach === "function" ? listTusach(state.listTusach) : listTusach })),
    setMapTrangThai: (mapTrangThai: Record<number, string> | ((prev: Record<number, string>) => Record<number, string>)) => set((state) => ({ mapTrangThai: typeof mapTrangThai === "function" ? mapTrangThai(state.mapTrangThai) : mapTrangThai })),
}));
