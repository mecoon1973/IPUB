export interface TemplateExcel {
    id: number;
    key: string;
    name: string;
    path_file_template: string;
    content_edit: ContentEditTemplate[];
    IsDeleted: boolean;
}

export interface ContentEditTemplate {
    type : ContentEditTemplateType;
    key_data: string; // key data trong map_data
    map_replate: Record<string, ContentReplaceTemplate>; // map placeholder và value
}

export interface ContentReplaceTemplate {
    value: string; // value cần thay thế
    callback: string; // map callback và value
}

/** các dạng chèn nội dung */
export enum ContentEditTemplateType {
    TEXT = "text", // chèn text
    LOOP = "loop", // chèn vòng lặp
}
