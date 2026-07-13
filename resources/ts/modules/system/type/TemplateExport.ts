export type TemplateFileField = "path_file_template" | "path_file_template_doc";

export interface TemplateExport {
    id: number;
    key: string;
    name: string;
    path_file_template: string;
    path_file_template_doc: string;
    content_edit: ContentEditTemplate[];
    IsDeleted: boolean;
}

export interface ContentEditTemplate {
    type : ContentEditTemplateType;
    key_data: string;
    map_replate: Record<string, ContentReplaceTemplate>;
}

export interface ContentReplaceTemplate {
    value: string;
    callback: string;
}

export enum ContentEditTemplateType {
    TEXT = "text",
    LOOP = "loop",
}
