export interface SimpleLoaiXbpLc {
    ID_LOAI_XBP_LC: number;
    SoLuong: number;
}

/** Đơn vị lưu chuyển */
export interface DonviLC {
    id: number;
    Ten: string;
    ThuTu: number;
    KhoaGuiNhan: string;
    IsDeleted: boolean;
    InUsed: boolean;
    DaGui: boolean;
    LoaiXbpLc: SimpleLoaiXbpLc[];
}
