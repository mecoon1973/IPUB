export interface Quyen {
    id: number;
    ParentID: number;
    ThuTu: number;
    MaQuyen: string;
    TenQuyen: string;
    IsDeleted: boolean;
    InUsed: boolean;

    listIdFunctions: number[];
}
