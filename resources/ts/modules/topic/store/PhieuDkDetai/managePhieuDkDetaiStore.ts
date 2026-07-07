import { create } from "zustand";
import { defaultFilterPhieuDkDetai, type Detai_Congdoan, type FilterPhieuDkDetai, type PhieuDkDetai } from "../../type";
import { defaultPagiInfo, type PagiInfo } from "../../../page/type";

export interface ManagePhieuDkDetaiState {
    /** list Phiếu đăng ký đề tài  */
    listPhieuDkDetai: PhieuDkDetai[];
    /** filter truy vấn Phiếu đăng ký đề tài */
    filter: FilterPhieuDkDetai;
    /** pagiInfo */
    pagiInfo: PagiInfo;
    /** isLoadingSearch */
    isLoadingSearch: boolean;
    /** showModalInfoPhieuDkDetai mở modal chi tiết Phiếu đăng ký đề tài */
    showModalInfoPhieuDkDetai: boolean;
    /** showProcessStepInfoModal mở modal chi tiết CT_Detai_Congdoan */
    showProcessStepInfoModal: boolean;
    /** showModalXetDuyetNxbgdvn mở modal xét duyệt NXBGDVN */
    showModalXetDuyetNxbgdvn: boolean;
    /** showModalCapMaSoNxbgd mở modal cấp mã số NXBGD */
    showModalCapMaSoNxbgd: boolean;
    /** PhieuDkDetai chi tiết Phiếu đăng ký đề tài */
    PhieuDkDetaiContext: PhieuDkDetai|null;
    /** CT_Detai_Congdoan chi tiết CT_Detai_Congdoan */
    listDetaiCongdoan: Detai_Congdoan[];
}

interface ManagePhieuDkDetaiActions {
    setData: (data: Partial<ManagePhieuDkDetaiState>) => void;
    setListPhieuDkDetai: (listPhieuDkDetai: PhieuDkDetai[] | ((prev: PhieuDkDetai[]) => PhieuDkDetai[])) => void;
    setFilter: (filter: FilterPhieuDkDetai | ((prev: FilterPhieuDkDetai) => FilterPhieuDkDetai)) => void;
    setPagiInfo: (pagiInfo: PagiInfo) => void;
    resetFilter: () => void;
    setIsLoadingSearch: (isLoadingSearch: boolean) => void;
    setShowProcessStepInfoModal: (showProcessStepInfoModal: boolean) => void;
    setShowModalXetDuyetNxbgdvn: (showModalXetDuyetNxbgdvn: boolean) => void;
    setShowModalCapMaSoNxbgd: (showModalCapMaSoNxbgd: boolean) => void;
    setShowModalInfoPhieuDkDetai: (showModalInfoPhieuDkDetai: boolean) => void;
    setPhieuDkDetaiContext: (PhieuDkDetaiContext: PhieuDkDetai|null) => void;
    setListDetaiCongdoan: (listDetaiCongdoan: Detai_Congdoan[] | ((prev: Detai_Congdoan[]) => Detai_Congdoan[])) => void;
}

export type ManagePhieuDkDetaiStore = ManagePhieuDkDetaiState & ManagePhieuDkDetaiActions;

const initialState: ManagePhieuDkDetaiState = {
    listPhieuDkDetai : [],
    filter : defaultFilterPhieuDkDetai,
    pagiInfo : defaultPagiInfo,
    isLoadingSearch : false,
    showModalInfoPhieuDkDetai : false,
    showProcessStepInfoModal : false,
    showModalXetDuyetNxbgdvn : false,
    showModalCapMaSoNxbgd : false,
    PhieuDkDetaiContext : null,
    listDetaiCongdoan : [],
};

export const useManagePhieuDkDetaiStore = create<ManagePhieuDkDetaiStore>((set, get) => ({
    ...initialState,

    setData: (data: Partial<ManagePhieuDkDetaiState>) => set((prev) => ({ ...prev, ...data })),

    setListPhieuDkDetai: (listPhieuDkDetai: PhieuDkDetai[] | ((prev: PhieuDkDetai[]) => PhieuDkDetai[])) => set((state) => ({
        listPhieuDkDetai: typeof listPhieuDkDetai === "function" ? listPhieuDkDetai(state.listPhieuDkDetai) : listPhieuDkDetai,
    })),

    setFilter: (filter: FilterPhieuDkDetai | ((prev: FilterPhieuDkDetai) => FilterPhieuDkDetai)) => set((state) => ({
        filter: typeof filter === "function" ? filter(state.filter) : filter,
    })),

    resetFilter: () => set({ filter: defaultFilterPhieuDkDetai }),

    setPagiInfo: (pagiInfo: PagiInfo) => set({ pagiInfo }),

    setIsLoadingSearch: (isLoadingSearch: boolean) => set({ isLoadingSearch }),

    setShowModalInfoPhieuDkDetai: (showModalInfoPhieuDkDetai: boolean) => set({ showModalInfoPhieuDkDetai }),

    setShowProcessStepInfoModal: (showProcessStepInfoModal: boolean) => set({ showProcessStepInfoModal }),

    setShowModalXetDuyetNxbgdvn: (showModalXetDuyetNxbgdvn: boolean) => set({ showModalXetDuyetNxbgdvn }),

    setShowModalCapMaSoNxbgd: (showModalCapMaSoNxbgd: boolean) => set({ showModalCapMaSoNxbgd }),

    setPhieuDkDetaiContext: (PhieuDkDetaiContext: PhieuDkDetai|null) => set({ PhieuDkDetaiContext }),

    setListDetaiCongdoan: (listDetaiCongdoan: Detai_Congdoan[] | ((prev: Detai_Congdoan[]) => Detai_Congdoan[])) => set((state) => ({ listDetaiCongdoan: typeof listDetaiCongdoan === "function" ? listDetaiCongdoan(state.listDetaiCongdoan) : listDetaiCongdoan })),
}));
