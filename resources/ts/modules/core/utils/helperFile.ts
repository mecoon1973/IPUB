export const DEFAULT_ACCEPT = ".xls,.xlsx,.xlsm";
export const DEFAULT_MAX_FILE_SIZE_MB = 100;

export function getFileNameFromPath(path: string): string {
    const trimmed = path.trim();
    if (!trimmed) {
        return "";
    }

    const withoutQuery = trimmed.split(/[?#]/)[0] ?? trimmed;
    const fileName = withoutQuery.split(/[\\/]/).pop() ?? withoutQuery;

    try {
        return decodeURIComponent(fileName);
    } catch {
        return fileName;
    }
}

export function isAcceptedFile(file: File, accept: string): boolean {
    const acceptItems = accept
        .split(",")
        .map((item) => item.trim().toLowerCase())
        .filter(Boolean);

    if (acceptItems.length === 0) {
        return true;
    }

    const fileName = file.name.toLowerCase();
    const fileType = file.type.toLowerCase();

    return acceptItems.some((acceptItem) => {
        if (acceptItem.startsWith(".")) {
            return fileName.endsWith(acceptItem);
        }
        if (acceptItem.endsWith("/*")) {
            const prefix = acceptItem.slice(0, -1);
            return fileType.startsWith(prefix);
        }
        return fileType === acceptItem;
    });
}



export function formatAcceptLabel(accept: string): string {
    return accept
        .split(",")
        .map((item) => item.trim().toUpperCase())
        .filter(Boolean)
        .join(", ");
}

export function formatFileSize(bytes: number): string {
    if (bytes < 1024) {
        return `${bytes} B`;
    }
    if (bytes < 1024 * 1024) {
        return `${(bytes / 1024).toFixed(1)} KB`;
    }
    return `${(bytes / (1024 * 1024)).toFixed(1)} MB`;
}
