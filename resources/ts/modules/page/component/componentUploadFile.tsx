import React, { useCallback, useRef, useState } from "react";
import { DEFAULT_ACCEPT, DEFAULT_MAX_FILE_SIZE_MB, formatAcceptLabel, formatFileSize, getFileNameFromPath, isAcceptedFile } from "../../core/utils/helperFile";

const ICON_EXCEL_FILE = "/svg/icon-excel-file.svg";
const ICON_DOWNLOAD_WHITE = "/svg/icon-download-outline-white.svg";
const ICON_IMPORT_ILLUSTRATION = "/svg/icon-import-book.svg";


export interface ComponentUploadFileProps {
    /** Cho phép chọn nhiều file. Mặc định: false (chỉ 1 file) */
    multiple?: boolean;
    /** Định dạng file chấp nhận, ví dụ: ".xls,.xlsx,.xlsm" */
    accept?: string;
    /** Dung lượng tối đa mỗi file (MB) */
    maxFileSizeMb?: number;
    disabled?: boolean;
    /** Danh sách file đã chọn (controlled) */
    value?: File[];
    /** Callback khi danh sách file thay đổi — luôn trả về mảng */
    onChange?: (files: File[]) => void;
}

function _ComponentUploadFile(props: ComponentUploadFileProps): React.JSX.Element {
    const {
        multiple = false,
        accept = DEFAULT_ACCEPT,
        maxFileSizeMb = DEFAULT_MAX_FILE_SIZE_MB,
        disabled = false,
        value,
        onChange,
    } = props;

    const [internalFiles, setInternalFiles] = useState<File[]>([]);
    const fileInputRef = useRef<HTMLInputElement>(null);
    const [isDragging, setIsDragging] = useState(false);

    const files = value ?? internalFiles;
    const maxFileSizeBytes = maxFileSizeMb * 1024 * 1024;

    const updateFiles = useCallback(
        (nextFiles: File[]) => {
            if (value === undefined) {
                setInternalFiles(nextFiles);
            }
            onChange?.(nextFiles);
        },
        [onChange, value],
    );

    const validateAndNormalizeFiles = useCallback(
        (incomingFiles: File[]): File[] => {
            const validFiles: File[] = [];

            for (const file of incomingFiles) {
                if (!isAcceptedFile(file, accept)) {
                    window._toastbox(`File "${file.name}" không đúng định dạng cho phép`, "error");
                    continue;
                }
                if (file.size > maxFileSizeBytes) {
                    window._toastbox(`File "${file.name}" vượt quá ${maxFileSizeMb}MB`, "error");
                    continue;
                }
                validFiles.push(file);
            }

            if (validFiles.length === 0) {
                return files;
            }

            if (!multiple) {
                const firstFile = validFiles[0];
                if (!firstFile) {
                    return files;
                }
                return [firstFile];
            }

            const mergedFiles = [...files];
            for (const file of validFiles) {
                const isDuplicated = mergedFiles.some(
                    (existingFile) =>
                        existingFile.name === file.name &&
                        existingFile.size === file.size &&
                        existingFile.lastModified === file.lastModified,
                );
                if (!isDuplicated) {
                    mergedFiles.push(file);
                }
            }
            return mergedFiles;
        },
        [accept, files, maxFileSizeBytes, maxFileSizeMb, multiple],
    );

    const handleIncomingFiles = useCallback(
        (incomingFiles: FileList | File[]) => {
            if (disabled) {
                return;
            }

            const fileArray = Array.from(incomingFiles);
            if (fileArray.length === 0) {
                return;
            }

            const nextFiles = validateAndNormalizeFiles(fileArray);
            updateFiles(nextFiles);
        },
        [disabled, updateFiles, validateAndNormalizeFiles],
    );

    const handleSelectFile = useCallback(() => {
        if (disabled) {
            return;
        }
        fileInputRef.current?.click();
    }, [disabled]);

    const handleFileChange = useCallback(
        (event: React.ChangeEvent<HTMLInputElement>) => {
            if (event.target.files) {
                handleIncomingFiles(event.target.files);
            }
            event.target.value = "";
        },
        [handleIncomingFiles],
    );

    const handleRemoveFile = useCallback(
        (index: number) => {
            const nextFiles = files.filter((_, fileIndex) => fileIndex !== index);
            updateFiles(nextFiles);
        },
        [files, updateFiles],
    );

    const handleDragOver = useCallback(
        (event: React.DragEvent) => {
            event.preventDefault();
            if (!disabled) {
                setIsDragging(true);
            }
        },
        [disabled],
    );

    const handleDragLeave = useCallback(() => {
        setIsDragging(false);
    }, []);

    const handleDrop = useCallback(
        (event: React.DragEvent) => {
            event.preventDefault();
            setIsDragging(false);
            if (event.dataTransfer.files) {
                handleIncomingFiles(event.dataTransfer.files);
            }
        },
        [handleIncomingFiles],
    );

    return (
        <div className="flex w-full flex-col gap-4">
            <input
                ref={fileInputRef}
                type="file"
                accept={accept}
                multiple={multiple}
                className="hidden"
                disabled={disabled}
                onChange={handleFileChange}
            />
            <div
                className={[
                    "flex w-full flex-col items-center justify-center",
                    "rounded-xl border-2 border-dashed px-6 py-10",
                    "bg-gray-50 transition-colors",
                    disabled ? "cursor-not-allowed opacity-60" : "cursor-pointer",
                    isDragging
                        ? "border-blue-500 bg-blue-50"
                        : "border-gray-300",
                ].join(" ")}
                onDragOver={handleDragOver}
                onDragLeave={handleDragLeave}
                onDrop={handleDrop}
                onClick={handleSelectFile}
                onKeyDown={(event) => {
                    if ((event.key === "Enter" || event.key === " ") && !disabled) {
                        event.preventDefault();
                        handleSelectFile();
                    }
                }}
                role="button"
                tabIndex={disabled ? -1 : 0}
                aria-disabled={disabled}
            >
                <div className="flex w-full max-w-[544px] flex-col items-center gap-6 text-center">
                    <div className="flex h-20 w-20 items-center justify-center rounded-2xl bg-green-50">
                        <img
                            src={ICON_IMPORT_ILLUSTRATION}
                            alt=""
                            className="h-12 w-12 object-contain"
                            aria-hidden
                        />
                    </div>
                    <div className="flex w-full flex-col items-center gap-2">
                        <p className="m-0 text-lg font-semibold leading-7 text-gray-900">
                            {multiple
                                ? "Kéo thả file Excel vào đây hoặc bấm Chọn tệp"
                                : "Kéo thả file Excel vào đây hoặc bấm Chọn tệp"}
                        </p>
                        <p className="m-0 text-base leading-6 text-gray-500">
                            Hỗ trợ định dạng {formatAcceptLabel(accept)}
                            <br />
                            Dung lượng tối đa {maxFileSizeMb}MB
                            {multiple ? " / file" : ""}
                        </p>
                    </div>
                    {files.length > 0 ? (
                        <div className="flex w-full flex-col items-center gap-1">
                            <p className="m-0 text-sm font-medium text-gray-700">
                                {multiple
                                    ? `Đã chọn ${files.length} file:`
                                    : "File đã chọn:"}
                            </p>
                            <ul className="m-0 w-full list-none p-0">
                                {files.map((file, index) => (
                                    <li
                                        key={`${file.name}-${file.size}-${file.lastModified}-${index}`}
                                        className="truncate text-sm text-blue-600"
                                        title={file.name}
                                    >
                                        {file.name}
                                    </li>
                                ))}
                            </ul>
                        </div>
                    ) : null}
                    <button
                        type="button"
                        className="min-w-[100px] rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-60"
                        onClick={(event) => {
                            event.stopPropagation();
                            handleSelectFile();
                        }}
                        disabled={disabled}
                    >
                        Chọn tệp
                    </button>
                </div>
            </div>

            {files.length > 0 ? (
                <div className="flex flex-col gap-2">
                    <p className="m-0 text-sm font-semibold text-gray-900">
                        {multiple ? `Đã chọn ${files.length} file` : "File đã chọn"}
                    </p>
                    {files.map((file, index) => (
                        <div
                            key={`${file.name}-${file.size}-${file.lastModified}-${index}`}
                            className="flex items-center gap-3 rounded-xl border border-gray-200 bg-white p-3"
                        >
                            <img
                                src={ICON_EXCEL_FILE}
                                alt=""
                                className="h-10 w-10 shrink-0"
                                aria-hidden
                            />
                            <div className="min-w-0 flex-1">
                                <p className="m-0 truncate text-sm font-medium text-gray-900">
                                    {file.name}
                                </p>
                                <p className="m-0 text-xs text-gray-500">
                                    {formatFileSize(file.size)}
                                </p>
                            </div>
                            <button
                                type="button"
                                className="shrink-0 rounded-md border-0 bg-transparent px-2 py-1 text-sm text-red-600 hover:bg-red-50"
                                onClick={() => handleRemoveFile(index)}
                                disabled={disabled}
                            >
                                Xóa
                            </button>
                        </div>
                    ))}
                </div>
            ) : null}
        </div>
    );
}

_ComponentUploadFile.displayName = "ComponentUploadFile";
export const ComponentUploadFile = React.memo(_ComponentUploadFile);

interface TemplateDownloadProps {
    pathFile: string;
}

function _TemplateDownloadComponent(props: TemplateDownloadProps): React.JSX.Element | null {
    const { pathFile } = props;
    const handleDownload = useCallback(() => {
        window.open(pathFile, "_blank");
    }, [pathFile]);
    if (!pathFile) {
        return null;
    }
    return (
        <div className="flex w-full items-center gap-4 rounded-2xl border border-gray-200 bg-white p-4">
            <img
                src={ICON_EXCEL_FILE}
                alt=""
                className="h-12 w-12 shrink-0"
                aria-hidden
            />
            <div className="flex min-w-0 flex-1 flex-col gap-1">
                <p className="text-sm font-semibold leading-5 text-gray-900">
                    {getFileNameFromPath(pathFile)}
                </p>
                <p className="text-sm leading-5 text-gray-500">
                    Tải về file chuẩn để điền đúng định dạng yêu cầu
                </p>
            </div>
            <button
                type="button"
                className="flex shrink-0 items-center gap-1 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700"
                onClick={handleDownload}
            >
                <img
                    src={ICON_DOWNLOAD_WHITE}
                    alt=""
                    className="h-5 w-5 shrink-0"
                    aria-hidden
                />
                <span>Tải template</span>
            </button>
        </div>
    );
}

_TemplateDownloadComponent.displayName = "TemplateDownload";
export const TemplateDownload = React.memo(_TemplateDownloadComponent);
