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
import type { ContentEditTemplate, TemplateExcel } from "../../type/TemplateExcel";
import { TemplateExcelApi } from "../../api/TemplateExcelApi";

const ACCEPT_EXCEL = ".xlsx,.xls";

type TemplateExcelUpsertBody = Pick<TemplateExcel, "id" | "key" | "name" | "path_file_template" | "content_edit">;


function emptyFormState(): Partial<TemplateExcel> {
    return {
        id: 0,
        key: "",
        name: "",
        path_file_template: "",
        content_edit: [],
        IsDeleted: false,
    };
}

function toTemplateExcelUpsertBody(form: Partial<TemplateExcel>): TemplateExcelUpsertBody {
    return {
        id: form.id ?? 0,
        key: form.key ?? "",
        name: form.name ?? "",
        path_file_template: form.path_file_template ?? "",
        content_edit: form.content_edit ?? [],
    };
}

interface TemplateExcelFormFieldsProps {
    form: Partial<TemplateExcel>;
    isEditing: boolean;
    setField: <K extends keyof TemplateExcel>(key: K, value: TemplateExcel[K]) => void;
    uploading: boolean;
    selectedFiles: File[];
    onUploadFilesChange: (files: File[]) => void;
}

const TemplateExcelFormFields = React.memo((props: TemplateExcelFormFieldsProps) => {
    const { form, isEditing, setField, uploading, selectedFiles, onUploadFilesChange } = props;

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

            { isEditing && <FormSection title="File template Excel">
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
                                value={selectedFiles}
                                onChange={onUploadFilesChange}
                            />
                        </div>
                    </FormField>
                </FormSection>
            }
        </div>
    );
});

TemplateExcelFormFields.displayName = "TemplateExcelFormFields";

interface ViewStoreTemplateExcelProps {
    templateExcel?: TemplateExcel | null;
}

export const ViewStoreTemplateExcel = React.memo((props: ViewStoreTemplateExcelProps) => {
    const templateExcel = props.templateExcel;
    const [form, setForm] = useState<Partial<TemplateExcel>>(() => {
        if (templateExcel && Object.keys(templateExcel).length > 0) {
            return {
                ...emptyFormState(),
                ...templateExcel,
            };
        }
        return emptyFormState();
    });
    const isEditing = Boolean(form.id);
    const [submitting, setSubmitting] = useState(false);
    const [uploading, setUploading] = useState(false);
    const [selectedFiles, setSelectedFiles] = useState<File[]>([]);

    const setField = useCallback(<K extends keyof TemplateExcel>(key: K, value: TemplateExcel[K]) => {
        setForm((prev) => ({ ...prev, [key]: value }));
    }, []);

    const handleUploadFilesChange = useCallback(
        async (files: File[]) => {
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
                const path = await TemplateExcelApi.uploadTemplateFile(file, templateKey);
                if (path) {
                    setField("path_file_template", path);
                    window._toastbox(
                        isEditing ? "Tải file mới thành công, file cũ đã được ghi đè theo key" : "Tải file template thành công",
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
        TemplateExcelApi.upsert(toTemplateExcelUpsertBody(form))
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
                <TemplateExcelFormFields
                    form={form}
                    isEditing={isEditing}
                    setField={setField}
                    uploading={uploading}
                    selectedFiles={selectedFiles}
                    onUploadFilesChange={handleUploadFilesChange}
                />
            </div>
        </div>
    );
});

ViewStoreTemplateExcel.displayName = "ViewStoreTemplateExcel";

const ROOT_ID = "root-store-template-excel";
const bladeProps: ViewStoreTemplateExcelProps = {
    ...readRootDataProps<ViewStoreTemplateExcelProps>(ROOT_ID),
};
mountReactComponentOnReady(ROOT_ID, <ViewStoreTemplateExcel {...bladeProps} />);
