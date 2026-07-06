import type { Relationships } from "../../page/type";
import type { User } from "../../user/type/User";

export type TypeDSDocRaSoat = "KIEM_DINH" | "RA_SOAT";

export interface FilterDSDocRaSoat extends Relationships {
    Title?: string;
    Type?: TypeDSDocRaSoat | "";
    IsSach?: boolean | "";
    Deleted?: boolean;
    TuNgay?: Date;
    DenNgay?: Date;
}

export interface DSDocRaSoat {
    id: number;
    Title: string;
    Type: TypeDSDocRaSoat;
    IsSach: boolean;
    Deleted: boolean;
    CreatedBy: number;
    CreatedOn: Date;
    EditedBy: number;
    EditedOn: Date;

    user_create?: User;
}

export const TYPE_DOC_RA_SOAT_OPTIONS: { label: string; value: TypeDSDocRaSoat }[] = [
    { label: "Rà soát", value: "RA_SOAT" },
    { label: "Kiểm định", value: "KIEM_DINH" },
];

export const TYPE_IS_SACH_OPTIONS: { label: string; value: boolean }[] = [
    { label: "Đề tài", value: false },
    { label: "Sách", value: true },
];
