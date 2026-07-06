export interface BienMoiTruong {
    id: number;
    ConfigName: string;
    ConfigNotes: string;
    ConfigValue: string;
    IsDeleted?: boolean;
    InUsed?: boolean;
    CreateBy?: number;
    CreatedOn?: Date;
    EditedBy?: number;
    EditedOn?: Date;
    AllowDelete?: boolean;
    AllowEdit?: boolean;
}

export interface FilterBienMoiTruong {
    ConfigSearch: string;
    id_Dv: number;
}
