export interface ChucNang {
    id: number;
    Code: string;
    Title: string;
    ParentID: number;
    NodeID: string;
    Href: string;
    Leaf: boolean;
    ChildFunctionCode: string;
    NameID: string;
    Visible: boolean;
    Root: boolean;
    Position: number;
    Description: string;
    Deleted: boolean;
    NotChange: boolean;
    StatusCode: string;
    PhanHeID: number;
    Order: number;
    FunctionCode: string;
    OnMenu: boolean;
    Icon: string;
    Crumb: string;
    Target: string;
    isLinkFull: boolean;

    ThuTu: number;
}
