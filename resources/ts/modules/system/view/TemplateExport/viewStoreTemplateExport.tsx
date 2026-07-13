import {
    mountReactComponentOnReady,
    readRootDataProps,
} from "../../../core/utils/helpers";
import React, { useCallback, useState } from "react";
import { Divider, Input } from "antd";
import { ComponentTitleStore } from "../../../page/component/componentTitleStore";
import { FormField, FormSection } from "../../../page/component/componentHelperForm";
import { LabelReq } from "../../../page/component/componentLable";
import { ComponentUploadFile, TemplateDownload } from "../../../page/component/componentUploadFile";
import {
    ContentEditEditor,
} from "../../component/TemplateExcel/ContentEditEditor";
import type {
    ContentEditTemplate,
    TemplateExport,
    TemplateFileField,
} from "../../type/TemplateExport";
import { TemplateExportApi } from "../../api/TemplateExportApi";
const ICON_WORD_FILE = "/svg/icon-docx-file.svg";
const ACCEPT_EXCEL = ".xlsx,.xls";
const ACCEPT_WORD = ".doc,.docx";
type TemplateExportUpsertBody = Pick<
    TemplateExport,
    "id" | "key" | "name" | "path_file_template" | "path_file_template_doc" | "content_edit"
>;

type TemplateUploadConfig = {
    field: TemplateFileField;
    successLabel: string;
};


function emptyFormState(): Partial<TemplateExport> {
    return {
        id: 0,
        key: "",
        name: "",
        path_file_template: "",
        path_file_template_doc: "",
        content_edit: [],
        IsDeleted: false,
    };
}

function toTemplateExportUpsertBody(form: Partial<TemplateExport>): TemplateExportUpsertBody {
    return {
        id: form.id ?? 0,
        key: form.key ?? "",
        name: form.name ?? "",
        path_file_template: form.path_file_template ?? "",
        path_file_template_doc: form.path_file_template_doc ?? "",
        content_edit: form.content_edit ?? [],
    };
}

interface TemplateExportFormFieldsProps {
    form: Partial<TemplateExport>;
    isEditing: boolean;
    setField: <K extends keyof TemplateExport>(key: K, value: TemplateExport[K]) => void;
    uploading: boolean;
    selectedExcelFiles: File[];
    selectedWordFiles: File[];
    onUploadTemplateFile: (files: File[], config: TemplateUploadConfig) => void;
}

const TemplateExportFormFields = React.memo((props: TemplateExportFormFieldsProps) => {
    const {
        form,
        isEditing,
        setField,
        uploading,
        selectedExcelFiles,
        selectedWordFiles,
        onUploadTemplateFile,
    } = props;

    return (
        <div className="tw-flex tw-flex-col tw-gap-4">
            <FormSection title="Thông tin template Excel">
                <div className="tw-grid tw-grid-cols-1 tw-gap-4 md:tw-grid-cols-2">
                    <FormField label={<LabelReq>Key</LabelReq>} required>
                        <Input
                            value={form.key ?? ""}
                            placeholder="Nhập key template (dùng làm tên file)"
                            onChange={(e) => setField("key", e.target.value)}
                        />
                    </FormField>
                    <FormField label={<LabelReq>Tên template</LabelReq>} required>
                        <Input
                            value={form.name ?? ""}
                            placeholder="Nhập tên template"
                            onChange={(e) => setField("name", e.target.value)}
                        />
                    </FormField>
                </div>
            </FormSection>
            <Divider/>
            <FormSection title="Cấu hình thông số khi xuất file excel">
                <FormField label="Cấu hình nội dung">
                    <ContentEditEditor
                        key={`content-edit-${form.id ?? "new"}`}
                        value={form.content_edit ?? []}
                        disabled={uploading}
                        onChange={(contentEdit) => setField("content_edit", contentEdit)}
                    />
                </FormField>
            </FormSection>

            { isEditing && <React.Fragment>
                <Divider/>
                <FormSection title="File template Excel">
                        <FormField label={<LabelReq>Tải file template</LabelReq>} required>
                            <div className="tw-flex tw-flex-col tw-gap-3">
                                {
                                    form.path_file_template && form.path_file_template.trim() ? (
                                        <React.Fragment>
                                            <TemplateDownload pathFile={form.path_file_template} />
                                            <Divider />
                                        </React.Fragment>
                                    ) : null
                                }
                                <ComponentUploadFile
                                    accept={ACCEPT_EXCEL}
                                    maxFileSizeMb={100}
                                    disabled={uploading}
                                    value={selectedExcelFiles}
                                    onChange={(files) =>
                                        onUploadTemplateFile(files, {
                                            field: "path_file_template",
                                            successLabel: "Excel",
                                        })
                                    }
                                />
                            </div>
                        </FormField>
                    </FormSection>
                    <Divider/>
                    <FormSection title="File template Word">
                            <FormField label={<LabelReq>Tải file template</LabelReq>} required>
                                <div className="tw-flex tw-flex-col tw-gap-3">
                                    {
                                        form.path_file_template_doc && form.path_file_template_doc.trim() ? (
                                            <React.Fragment>
                                                <TemplateDownload pathFile={form.path_file_template_doc} icon={ICON_WORD_FILE}/>
                                                <Divider />
                                            </React.Fragment>
                                        ) : null
                                    }
                                    <ComponentUploadFile
                                        accept={ACCEPT_WORD}
                                        maxFileSizeMb={20}
                                        disabled={uploading}
                                        value={selectedWordFiles}
                                        onChange={(files) =>
                                            onUploadTemplateFile(files, {
                                                field: "path_file_template_doc",
                                                successLabel: "Word",
                                            })
                                        }
                                    />
                                </div>
                            </FormField>
                        </FormSection>

            </React.Fragment>
            }
        </div>
    );
});

TemplateExportFormFields.displayName = "TemplateExportFormFields";

interface ViewStoreTemplateExportProps {
    templateExport?: TemplateExport | null;
}

export const ViewStoreTemplateExport = React.memo((props: ViewStoreTemplateExportProps) => {
    const templateExport = props.templateExport;
    const [form, setForm] = useState<Partial<TemplateExport>>(() => {
        if (templateExport && Object.keys(templateExport).length > 0) {
            return {
                ...emptyFormState(),
                ...templateExport,
            };
        }
        return emptyFormState();
    });
    const isEditing = Boolean(form.id);
    const [submitting, setSubmitting] = useState(false);
    const [uploading, setUploading] = useState(false);
    const [selectedExcelFiles, setSelectedExcelFiles] = useState<File[]>([]);
    const [selectedWordFiles, setSelectedWordFiles] = useState<File[]>([]);

    const setField = useCallback(<K extends keyof TemplateExport>(key: K, value: TemplateExport[K]) => {
        setForm((prev) => ({ ...prev, [key]: value }));
    }, []);

    const handleTemplateFileUpload = useCallback(
        async (files: File[], config: TemplateUploadConfig) => {
            const setSelectedFiles =
                config.field === "path_file_template" ? setSelectedExcelFiles : setSelectedWordFiles;

            setSelectedFiles(files);

            const file = files[0];
            if (!file) {
                return;
            }

            const templateKey = form.key?.trim() ?? "";
            if (!templateKey) {
                window._toastbox("Vui lòng nhập key template trước khi tải file lên", "error");
                setSelectedFiles([]);
                return;
            }

            setUploading(true);
            try {
                const path = await TemplateExportApi.uploadTemplateFile(file, templateKey, config.field);
                if (path) {
                    setField(config.field, path);
                    window._toastbox(
                        isEditing
                            ? `Tải file template ${config.successLabel} mới thành công, file cũ đã được ghi đè theo key`
                            : `Tải file template ${config.successLabel} thành công`,
                        "success",
                    );
                    setSelectedFiles([]);
                }
            } finally {
                setUploading(false);
            }
        },
        [form.key, isEditing, setField],
    );

    const handleSubmit = useCallback(() => {
        const mapKeysRequired = {
            key: "Key",
            name: "Tên template",
        };

        const messageRequired = Object.keys(mapKeysRequired)
            .map((key) =>
                !form[key as keyof typeof mapKeysRequired]
                    ? mapKeysRequired[key as keyof typeof mapKeysRequired]
                    : "",
            )
            .filter(Boolean)
            .join(", ");

        if (messageRequired) {
            window._toastbox(`Vui lòng nhập đầy đủ thông tin: ${messageRequired}`, "error");
            return;
        }

        setSubmitting(true);
        TemplateExportApi.upsert(toTemplateExportUpsertBody(form))
            .then((res) => {
                if (res) {
                    window._toastbox(
                        `${form.id ? "Cập nhật" : "Thêm mới"} template Excel thành công`,
                        "success",
                    );
                    setForm((prev) => ({ ...prev, ...res }));
                }
            })
            .finally(() => {
                setSubmitting(false);
            });
    }, [form]);

    return (
        <div className="tw-px-2">
            <ComponentTitleStore
                title={isEditing ? "Cập nhật Template Excel" : "Thêm mới Template Excel"}
                callbackSubmit={handleSubmit}
                disabledSubmit={submitting || uploading}
            />
            <div className="tw-max-w-3xl">
                <TemplateExportFormFields
                    form={form}
                    isEditing={isEditing}
                    setField={setField}
                    uploading={uploading}
                    selectedExcelFiles={selectedExcelFiles}
                    selectedWordFiles={selectedWordFiles}
                    onUploadTemplateFile={handleTemplateFileUpload}
                />
            </div>
        </div>
    );
});

ViewStoreTemplateExport.displayName = "ViewStoreTemplateExport";

const ROOT_ID = "root-store-template-export";
const bladeProps: ViewStoreTemplateExportProps = {
    ...readRootDataProps<ViewStoreTemplateExportProps>(ROOT_ID),
};
mountReactComponentOnReady(ROOT_ID, <ViewStoreTemplateExport {...bladeProps} />);
