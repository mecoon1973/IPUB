import { create } from "zustand";
import dayjs from "dayjs";
import { defaultFilterPhieuDkKhxbCxb, type FilterPhieuDkKhxbCxb, type PhieuDkKhxbCxb } from "../../type";
import { defaultPagiInfo, type PagiInfo } from "../../../page/type";

export interface ManagePhieuDkKhxbCxbState {
    listPhieuDkKhxbCxb: PhieuDkKhxbCxb[];
    filter: FilterPhieuDkKhxbCxb;
    pagiInfo: PagiInfo;
    isLoadingSearch: boolean;
    showModalCapMaCxb: boolean;
    phieuCapMaCxbContext: PhieuDkKhxbCxb | null;
    showModalKetChuyen: boolean;
    phieuKetChuyenContext: PhieuDkKhxbCxb | null;
    showModalXetDuyet: boolean;
    phieuXetDuyetContext: PhieuDkKhxbCxb | null;
}

interface ManagePhieuDkKhxbCxbActions {
    setListPhieuDkKhxbCxb: (
        list: PhieuDkKhxbCxb[] | ((prev: PhieuDkKhxbCxb[]) => PhieuDkKhxbCxb[]),
    ) => void;
    setFilter: (
        filter: FilterPhieuDkKhxbCxb | ((prev: FilterPhieuDkKhxbCxb) => FilterPhieuDkKhxbCxb),
    ) => void;
    setPagiInfo: (pagiInfo: PagiInfo) => void;
    setIsLoadingSearch: (isLoadingSearch: boolean) => void;
    resetFilter: () => void;
    openModalCapMaCxb: (phieu: PhieuDkKhxbCxb) => void;
    setShowModalCapMaCxb: (show: boolean) => void;
    openModalKetChuyen: (phieu: PhieuDkKhxbCxb) => void;
    setShowModalKetChuyen: (show: boolean) => void;
    openModalXetDuyet: (phieu: PhieuDkKhxbCxb) => void;
    setShowModalXetDuyet: (show: boolean) => void;
}

export type ManagePhieuDkKhxbCxbStore = ManagePhieuDkKhxbCxbState & ManagePhieuDkKhxbCxbActions;

const initialFilter: FilterPhieuDkKhxbCxb = {
    ...defaultFilterPhieuDkKhxbCxb,
    startDate: dayjs().subtract(1, "month").startOf("day").toDate(),
    endDate: dayjs().endOf("day").toDate(),
};

export const useManagePhieuDkKhxbCxbStore = create<ManagePhieuDkKhxbCxbStore>((set) => ({
    listPhieuDkKhxbCxb: [],
    filter: initialFilter,
    pagiInfo: defaultPagiInfo,
    isLoadingSearch: false,
    showModalCapMaCxb: false,
    phieuCapMaCxbContext: null,
    showModalKetChuyen: false,
    phieuKetChuyenContext: null,
    showModalXetDuyet: false,
    phieuXetDuyetContext: null,

    setListPhieuDkKhxbCxb: (listPhieuDkKhxbCxb) => set((state) => ({
        listPhieuDkKhxbCxb: typeof listPhieuDkKhxbCxb === "function"
            ? listPhieuDkKhxbCxb(state.listPhieuDkKhxbCxb)
            : listPhieuDkKhxbCxb,
    })),

    setFilter: (filter) => set((state) => ({
        filter: typeof filter === "function" ? filter(state.filter) : filter,
    })),

    setPagiInfo: (pagiInfo) => set({ pagiInfo }),

    setIsLoadingSearch: (isLoadingSearch) => set({ isLoadingSearch }),

    resetFilter: () => set({ filter: initialFilter }),

    openModalCapMaCxb: (phieu) => set({ showModalCapMaCxb: true, phieuCapMaCxbContext: phieu }),

    setShowModalCapMaCxb: (showModalCapMaCxb) => set({ showModalCapMaCxb }),

    openModalKetChuyen: (phieu) => set({ showModalKetChuyen: true, phieuKetChuyenContext: phieu }),

    setShowModalKetChuyen: (showModalKetChuyen) => set({ showModalKetChuyen }),

    openModalXetDuyet: (phieu) => set({ showModalXetDuyet: true, phieuXetDuyetContext: phieu }),

    setShowModalXetDuyet: (showModalXetDuyet) => set({ showModalXetDuyet }),
}));
