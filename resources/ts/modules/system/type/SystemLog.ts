export interface SystemLog {
    id: number;
    UserID: number;
    Desc: string;
    IPAddress: string;
    ActionTime: Date;
    InUse: boolean;
}

export interface SystemLogFilter {
    accountName?: string;
    userName?: string;
    content?: string;
    id_Dv?: number;
    startDate?: Date;
    endDate?: Date;
}
