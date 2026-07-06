import type { User } from "../../user/type/User";
import type { Relationships } from "../../page/type";

export interface Detai_Congdoan {
    id: number;
    IDCongDoan: number;
    IDDeTai: number;
    IDSach: number;
    MaCD: string;
    GhiChu: string;
    NewValue: string;
    NoiDung: string;
    OldValue: string;
    CreatedOn: Date;
    CreatedBy: number;
    EditedOn: Date;
    EditedBy: number;
    IsDeleted: boolean;

    user_create?: User;
}

export type FilterDetaiCongdoan = Partial<Detai_Congdoan> & Relationships;
